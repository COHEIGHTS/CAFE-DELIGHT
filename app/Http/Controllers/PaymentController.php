<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get all orders for the user with their items
        $orders = Order::with('items.dish')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Separate orders by payment status
        $paidOrders = $orders->where('payment_status', 'paid');
        $pendingOrders = $orders->whereIn('payment_status', ['pending', 'awaiting_approval']);

        // Calculate totals
        $totalPaid = $paidOrders->sum('total');
        $totalPending = $pendingOrders->sum('total');

        return view('payments.index', compact(
            'paidOrders',
            'pendingOrders',
            'totalPaid',
            'totalPending'
        ));
    }
}
