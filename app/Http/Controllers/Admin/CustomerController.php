<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $query = User::where('role', 'customer');

        // Search by name or email
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sort
        $query->orderBy($sortBy, $sortOrder);

        $customers = $query->paginate(15);

        // Load order statistics for each customer
        $customers->getCollection()->transform(function($customer) {
            $customer->total_orders = $customer->orders()->count();
            $customer->total_spent = $customer->orders()->where('payment_status', 'paid')->sum('total');
            $customer->avg_order_value = $customer->total_orders > 0 ? $customer->total_spent / $customer->total_orders : 0;
            $customer->last_order = $customer->orders()->latest()->first();
            return $customer;
        });

        // Summary stats
        $totalCustomers = User::where('role', 'customer')->count();
        $activeCustomers = User::where('role', 'customer')
            ->whereHas('orders', function($q) {
                $q->where('created_at', '>=', now()->subDays(30));
            })->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');

        return view('admin.customers', compact(
            'customers',
            'search',
            'sortBy',
            'sortOrder',
            'totalCustomers',
            'activeCustomers',
            'totalRevenue',
        ));
    }

    public function show(User $user)
    {
        if ($user->role === 'admin') {
            abort(403);
        }

        $user->load(['orders' => function($q) {
            $q->latest()->with('items.dish');
        }]);

        $user->total_orders = $user->orders->count();
        $user->total_spent = $user->orders->where('payment_status', 'paid')->sum('total');
        $user->avg_order_value = $user->total_orders > 0 ? $user->total_spent / $user->total_orders : 0;

        return view('admin.customer-detail', compact('user'));
    }
}
