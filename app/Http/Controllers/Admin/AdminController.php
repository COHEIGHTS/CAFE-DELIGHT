<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Dish;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today     = Carbon::today();
        $yesterday = Carbon::yesterday();

        // ── Today's orders ───────────────────────────────────────────────────
        $todayOrders     = Order::whereDate('created_at', $today)->count();
        $yesterdayOrders = Order::whereDate('created_at', $yesterday)->count();
        $ordersDelta     = $yesterdayOrders > 0
            ? round((($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100)
            : ($todayOrders > 0 ? 100 : 0);

        // ── Today's revenue (non-cancelled) ─────────────────────────────────
        $todayRevenue     = Order::whereDate('created_at', $today)
                                ->where('status', '!=', 'cancelled')
                                ->sum('total');
        $yesterdayRevenue = Order::whereDate('created_at', $yesterday)
                                ->where('status', '!=', 'cancelled')
                                ->sum('total');
        $revenueDelta = $yesterdayRevenue > 0
            ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100)
            : ($todayRevenue > 0 ? 100 : 0);

        // ── Total customers ──────────────────────────────────────────────────
        $totalCustomers    = User::where('role', 'customer')->count();
        $newCustomersToday = User::where('role', 'customer')
                                ->whereDate('created_at', $today)->count();

        // ── Pending orders ───────────────────────────────────────────────────
        $pendingOrders    = Order::where('status', 'pending')->count();
        $pendingYesterday = Order::where('status', 'pending')
                                ->whereDate('created_at', $yesterday)->count();
        $pendingDelta     = $pendingOrders - $pendingYesterday;

        // ── Recent 10 orders ─────────────────────────────────────────────────
        $recentOrders = Order::with('user', 'items.dish')
                            ->latest()
                            ->take(10)
                            ->get();

        // ── Top 5 selling dishes ─────────────────────────────────────────────
        $topDishes = DB::table('order_items')
            ->join('dishes', 'order_items.dish_id', '=', 'dishes.id')
            ->join('orders',  'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->select('dishes.id', 'dishes.name', DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('dishes.id', 'dishes.name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $maxQty    = $topDishes->max('total_qty') ?: 1;
        $topDishes = $topDishes->map(fn($d) => [
            'name'   => $d->name,
            'orders' => (int) $d->total_qty,
            'pct'    => (int) round(($d->total_qty / $maxQty) * 100),
        ]);

        return view('admin.dashboard', compact(
            'todayOrders',    'ordersDelta',
            'todayRevenue',   'revenueDelta',
            'totalCustomers', 'newCustomersToday',
            'pendingOrders',  'pendingDelta',
            'recentOrders',
            'topDishes'
        ));
    }
}