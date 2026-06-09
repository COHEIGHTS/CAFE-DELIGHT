<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Customer - View all their orders
    public function index()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('order.index', compact('orders'));
    }

    // Customer - View order confirmation/details
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
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

    // Admin - View all orders
    public function adminIndex()
    {
        $orders = Order::latest()->paginate(15);
        return view('admin.order.index', compact('orders'));
    }

    // Admin - Update order status
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,on_the_way,delivered,cancelled',
        ]);

        $order->update($validated);

        return back()->with('success', 'Order status updated successfully');
    }
}