<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ── Stat cards ────────────────────────────────────────────────

        $totalOrders = Order::where('user_id', $user->id)->count();

        $thisMonthOrders = Order::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonthOrders = Order::where('user_id', $user->id)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $ordersDelta = $lastMonthOrders > 0
            ? round((($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100)
            : ($thisMonthOrders > 0 ? 100 : 0);

        $totalSpent = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->sum('total');

        $thisMonthSpent = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $lastMonthSpent = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');

        $spentDelta = $lastMonthSpent > 0
            ? round((($thisMonthSpent - $lastMonthSpent) / $lastMonthSpent) * 100)
            : ($thisMonthSpent > 0 ? 100 : 0);

        $activeOrders = Order::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'on_the_way'])
            ->count();

        // ── Recent orders ─────────────────────────────────────────────

        $recentOrders = Order::with('items.dish')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // ── Quick reorder: most frequently ordered dishes ─────────────

        $favoriteDishes = Dish::whereHas('orderItems.order', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->withCount(['orderItems as times_ordered' => function ($q) use ($user) {
                $q->whereHas('order', fn ($q) => $q->where('user_id', $user->id));
            }])
            ->orderByDesc('times_ordered')
            ->take(4)
            ->get();

        return view('dashboard', compact(
            'totalOrders',
            'ordersDelta',
            'totalSpent',
            'spentDelta',
            'activeOrders',
            'recentOrders',
            'favoriteDishes',
        ));
    }
}