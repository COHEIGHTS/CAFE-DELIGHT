<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Notifications\OrderDeliveredNotification;
use App\Notifications\PaymentConfirmedNotification;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    // Customer - Dashboard
    public function dashboard()
    {
        $user = auth()->user();

        $orders = $user->orders()->with('items.dish')->latest()->get();

        // Stat cards
        $totalOrders = $orders->count();

        $totalSpent = $orders->where('status', '!=', 'cancelled')->sum('total');

        $activeOrder = $orders->whereNotIn('status', ['delivered', 'cancelled'])->first();

        $recentOrders = $orders->take(5);

        // Quick reorder — top 4 most ordered dishes by this customer
        $topDishes = $user->orders()
            ->with('items.dish')
            ->where('status', 'delivered')
            ->get()
            ->flatMap(fn($o) => $o->items)
            ->groupBy('dish_id')
            ->map(fn($items) => [
                'dish'  => $items->first()->dish,
                'count' => $items->sum('quantity'),
            ])
            ->sortByDesc('count')
            ->take(4)
            ->values();

        return view('dashboard', compact(
            'totalOrders',
            'totalSpent',
            'activeOrder',
            'recentOrders',
            'topDishes'
        ));
    }

    // Customer - View all their orders
    public function index(Request $request)
    {
        $query = auth()->user()->orders()->with('items.dish');

        // Search by order ID
        if ($request->filled('search')) {
            $query->where('id', 'like', "%{$request->search}%");
        }

        $orders = $query->latest()->paginate(10)->withQueryString();
        return view('order.index', compact('orders'));
    }

    // Customer - View order confirmation/details
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.dish');

        return view('order.confirmation', compact('order'));
    }

    // Customer - Cancel order
    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Cannot cancel this order');
        }

        $order->update(['status' => 'cancelled']);
        return back()->with('success', 'Order cancelled successfully');
    }

    // Customer - Mark payment as paid (for cash on delivery)
    public function markPaymentAsPaid(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Only allow marking as paid for cash on delivery orders that are delivered
        if ($order->payment_method !== 'cash_on_delivery') {
            return back()->with('error', 'This payment method does not require manual payment confirmation');
        }

        if ($order->status !== 'delivered') {
            return back()->with('error', 'Payment can only be marked as paid after delivery');
        }

        if ($order->payment_status === 'paid') {
            return back()->with('info', 'Payment is already marked as paid');
        }

        $order->update(['payment_status' => 'awaiting_approval']);
        return back()->with('success', 'Payment marked as paid. Waiting for admin approval.');
    }

    // Customer - View payment history
    public function payments()
    {
        $orders = auth()->user()->orders()->with('items.dish')->latest()->get();

        $paidOrders = $orders->where('payment_status', 'paid');
        $pendingOrders = $orders->where('payment_status', 'pending');

        $totalPaid = $paidOrders->sum('total');
        $totalPending = $pendingOrders->sum('total');

        return view('payments.index', compact('paidOrders', 'pendingOrders', 'totalPaid', 'totalPending'));
    }

    // Admin - View all orders with filters
    public function adminIndex(Request $request)
    {
        $query = Order::with('user', 'items.dish')->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders', compact('orders'));
    }

    // Admin - Update order status
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,on_the_way,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update($validated);

        // Log order status update
        AuditLogService::logOrderUpdated($order, $oldStatus);

        // Send thank you email to customer when order is delivered
        if ($request->status === 'delivered' && $oldStatus !== 'delivered') {
            $order->user->notify(new OrderDeliveredNotification($order));
        }

        // Don't auto-mark as paid anymore - let customer mark it and admin approve
        $labels = [
            'confirmed'  => 'Order confirmed.',
            'preparing'  => 'Order is now being prepared.',
            'on_the_way' => 'Order is out for delivery.',
            'delivered'  => 'Order marked as delivered.',
            'cancelled'  => 'Order cancelled.',
        ];

        return back()->with('success', $labels[$request->status] ?? 'Order status updated successfully');
    }

    // Admin - Approve payment
    public function approvePayment(Order $order)
    {
        if ($order->payment_status !== 'awaiting_approval') {
            return back()->with('error', 'This payment is not awaiting approval');
        }

        $order->update(['payment_status' => 'paid']);

        // Log payment approval
        AuditLogService::logPaymentApproved($order);

        // Send payment confirmation email to customer
        $order->user->notify(new PaymentConfirmedNotification($order));

        return back()->with('success', 'Payment approved successfully');
    }
}