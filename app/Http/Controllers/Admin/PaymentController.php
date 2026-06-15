<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $paymentMethod = $request->input('payment_method', 'all');
        $search = $request->input('search');

        $query = Order::with('user', 'items.dish')
            ->where('status', '!=', 'cancelled');

        // Filter by payment status
        if ($status !== 'all') {
            $query->where('payment_status', $status);
        }

        // Filter by payment method
        if ($paymentMethod !== 'all') {
            $query->where('payment_method', $paymentMethod);
        }

        // Search by order ID or customer name
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->latest()->paginate(15);

        // Calculate summary stats
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $pendingRevenue = Order::whereIn('payment_status', ['pending', 'awaiting_approval'])->sum('total');
        $totalPayments = Order::count();
        $paidPayments = Order::where('payment_status', 'paid')->count();
        $pendingPayments = Order::whereIn('payment_status', ['pending', 'awaiting_approval'])->count();
        $awaitingApproval = Order::where('payment_status', 'awaiting_approval')->count();

        return view('admin.payments', compact(
            'payments',
            'status',
            'paymentMethod',
            'search',
            'totalRevenue',
            'pendingRevenue',
            'totalPayments',
            'paidPayments',
            'pendingPayments',
            'awaitingApproval',
        ));
    }
}
