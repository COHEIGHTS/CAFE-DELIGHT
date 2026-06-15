<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get orders in date range
        $orders = Order::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->with('items.dish', 'user')
            ->get();

        // Sales data by day
        $salesByDay = $orders->groupBy(function($order) {
            return $order->created_at->format('Y-m-d');
        })->map(function($dayOrders) {
            return [
                'date' => $dayOrders->first()->created_at->format('M d'),
                'revenue' => $dayOrders->sum('total'),
                'orders' => $dayOrders->count(),
            ];
        })->sortBy('date')->values();

        // Sales by payment method
        $salesByPayment = $orders->groupBy('payment_method')->map(function($paymentOrders) use ($orders) {
            return [
                'method' => $paymentOrders->first()->paymentMethodLabel(),
                'revenue' => $paymentOrders->sum('total'),
                'orders' => $paymentOrders->count(),
                'percentage' => $orders->sum('total') > 0 
                    ? round(($paymentOrders->sum('total') / $orders->sum('total')) * 100, 1) 
                    : 0,
            ];
        })->values();

        // Top selling dishes
        $topDishes = $orders->flatMap->items
            ->groupBy('dish_id')
            ->map(function($items) {
                $dish = $items->first()->dish;
                return [
                    'name' => $dish->name,
                    'quantity' => $items->sum('quantity'),
                    'revenue' => $items->sum(function($item) {
                        return $item->price * $item->quantity;
                    }),
                ];
            })
            ->sortByDesc('quantity')
            ->take(10)
            ->values();

        // Customer analytics
        $customerOrders = $orders->groupBy('user_id')->map(function($userOrders) {
            $user = $userOrders->first()->user;
            return [
                'name' => $user->name,
                'email' => $user->email,
                'orders' => $userOrders->count(),
                'total_spent' => $userOrders->sum('total'),
                'avg_order_value' => $userOrders->avg('total'),
            ];
        })->sortByDesc('total_spent')->take(10)->values();

        // Order status breakdown
        $orderStatusBreakdown = $orders->groupBy('status')->map(function($statusOrders) use ($orders) {
            return [
                'status' => ucfirst(str_replace('_', ' ', $statusOrders->first()->status)),
                'count' => $statusOrders->count(),
                'percentage' => $orders->count() > 0 
                    ? round(($statusOrders->count() / $orders->count()) * 100, 1) 
                    : 0,
            ];
        })->values();

        // Summary stats
        $totalRevenue = $orders->sum('total');
        $totalOrders = $orders->count();
        $totalCustomers = $orders->pluck('user_id')->unique()->count();
        $avgOrderValue = $orders->avg('total');

        // Comparison with previous period
        $prevStart = $start->copy()->subDays($start->diffInDays($end));
        $prevEnd = $start->copy()->subDay();
        
        $prevOrders = Order::whereBetween('created_at', [$prevStart, $prevEnd])
            ->where('status', '!=', 'cancelled')
            ->get();
        
        $prevRevenue = $prevOrders->sum('total');
        $revenueGrowth = $prevRevenue > 0 
            ? round((($totalRevenue - $prevRevenue) / $prevRevenue) * 100, 1) 
            : 0;

        return view('admin.analytics', compact(
            'startDate',
            'endDate',
            'salesByDay',
            'salesByPayment',
            'topDishes',
            'customerOrders',
            'orderStatusBreakdown',
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'avgOrderValue',
            'revenueGrowth',
        ));
    }

    public function downloadPDF(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get orders in date range
        $orders = Order::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->with('items.dish', 'user')
            ->get();

        // Calculate analytics data
        $totalRevenue = $orders->sum('total');
        $totalOrders = $orders->count();
        $totalCustomers = $orders->pluck('user_id')->unique()->count();
        $avgOrderValue = $orders->avg('total');

        $salesByPayment = $orders->groupBy('payment_method')->map(function($paymentOrders) use ($totalRevenue) {
            return [
                'method' => $paymentOrders->first()->paymentMethodLabel(),
                'revenue' => $paymentOrders->sum('total'),
                'orders' => $paymentOrders->count(),
                'percentage' => $totalRevenue > 0 
                    ? round(($paymentOrders->sum('total') / $totalRevenue) * 100, 1) 
                    : 0,
            ];
        })->values();

        $topDishes = $orders->flatMap->items
            ->groupBy('dish_id')
            ->map(function($items) {
                $dish = $items->first()->dish;
                return [
                    'name' => $dish->name,
                    'quantity' => $items->sum('quantity'),
                    'revenue' => $items->sum(function($item) {
                        return $item->price * $item->quantity;
                    }),
                ];
            })
            ->sortByDesc('quantity')
            ->take(10)
            ->values();

        return view('admin.analytics-pdf', compact(
            'startDate',
            'endDate',
            'orders',
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'avgOrderValue',
            'salesByPayment',
            'topDishes',
        ));
    }
}
