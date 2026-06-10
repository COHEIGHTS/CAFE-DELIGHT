<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Customer - View all their orders
    public function index()
    {
        $orders = auth()->user()->orders()->with('items.dish')->latest()->paginate(10);
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

        $order->update($validated);

        $labels = [
            'confirmed'  => 'Order confirmed.',
            'preparing'  => 'Order is now being prepared.',
            'on_the_way' => 'Order is out for delivery.',
            'delivered'  => 'Order marked as delivered.',
            'cancelled'  => 'Order cancelled.',
        ];

        return back()->with('success', $labels[$request->status] ?? 'Order status updated successfully');
    }
}