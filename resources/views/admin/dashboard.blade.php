@extends('layouts.admin-layout')

@section('title', 'Dashboard — Admin · Cafe Delight')

@php $activeNav = 'Dashboard'; @endphp

@section('content')
<div class="space-y-6">

    {{-- ===== Welcome banner ===== --}}
    <div data-aos="fade-up"
         class="overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 via-brand-700 to-brand-900 p-6 text-white sm:p-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-xs font-extrabold uppercase tracking-widest">
                        <i data-lucide="shield-check" class="h-3.5 w-3.5"></i> Admin Panel
                    </span>
                </div>
                <h1 class="font-display text-2xl font-extrabold sm:text-3xl">
                    Good day, {{ auth()->user()->name ?? 'Admin' }}! 👋
                </h1>
                <p class="mt-1 text-orange-50/90">Here's what's happening at Cafe Delight today.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.order.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-white/15 border border-white/20 px-4 py-2.5 text-sm font-bold transition hover:bg-white/25">
                    <i data-lucide="shopping-bag" class="h-4 w-4"></i> View Orders
                </a>
                <a href="{{ route('admin.menu.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-brand-700 transition hover:scale-105">
                    <i data-lucide="plus" class="h-4 w-4"></i> New Menu Item
                </a>
            </div>
        </div>
    </div>

    {{-- ===== Stat cards ===== --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Today's Orders --}}
        <div data-aos="fade-up" data-aos-delay="0" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white">
                    <i data-lucide="shopping-bag" class="h-6 w-6"></i>
                </span>
                <span class="text-xs font-bold {{ $ordersDelta >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                    {{ $ordersDelta >= 0 ? '+' : '' }}{{ $ordersDelta }}% vs yesterday
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">{{ $todayOrders }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Today's Orders</p>
        </div>

        {{-- Today's Revenue --}}
        <div data-aos="fade-up" data-aos-delay="80" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white">
                    <i data-lucide="banknote" class="h-6 w-6"></i>
                </span>
                <span class="text-xs font-bold {{ $revenueDelta >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                    {{ $revenueDelta >= 0 ? '+' : '' }}{{ $revenueDelta }}% vs yesterday
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">KSh {{ number_format($todayRevenue, 0) }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Today's Revenue</p>
        </div>

        {{-- Total Customers --}}
        <div data-aos="fade-up" data-aos-delay="160" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 text-white">
                    <i data-lucide="users" class="h-6 w-6"></i>
                </span>
                @if($newCustomersToday > 0)
                <span class="text-xs font-bold text-emerald-500">+{{ $newCustomersToday }} today</span>
                @else
                <span class="text-xs font-bold text-ink/40 dark:text-orange-50/40">No new today</span>
                @endif
            </div>
            <p class="mt-4 text-2xl font-extrabold">{{ number_format($totalCustomers) }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Total Customers</p>
        </div>

        {{-- Pending Orders --}}
        <div data-aos="fade-up" data-aos-delay="240" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-amber-500 to-amber-700 text-white">
                    <i data-lucide="clock" class="h-6 w-6"></i>
                </span>
                <span class="text-xs font-bold {{ $pendingDelta <= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                    {{ $pendingDelta > 0 ? '+' : '' }}{{ $pendingDelta }} vs yesterday
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">{{ $pendingOrders }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Pending Orders</p>
        </div>

    </div>

    {{-- ===== Recent Orders + Top Sellers ===== --}}
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Recent orders --}}
        <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft lg:col-span-2">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-bold">Recent Orders</h2>
                <div class="flex items-center gap-2">
                    @if($pendingOrders > 0)
                    <span class="rounded-full bg-amber-500/15 px-2.5 py-1 text-xs font-bold text-amber-600">
                        {{ $pendingOrders }} pending
                    </span>
                    @endif
                    <a href="{{ route('admin.order.index') }}"
                       class="text-sm font-bold text-brand-600 hover:underline">View all</a>
                </div>
            </div>

            @if($recentOrders->isEmpty())
            <div class="py-10 text-center">
                <i data-lucide="inbox" class="mx-auto h-10 w-10 text-ink/20 dark:text-orange-50/20"></i>
                <p class="mt-3 text-sm font-semibold text-ink/50 dark:text-orange-50/50">No orders yet</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-ink/45 dark:text-orange-50/45">
                            <th class="pb-3 font-bold">Order</th>
                            <th class="pb-3 font-bold">Customer</th>
                            <th class="pb-3 font-bold">Items</th>
                            <th class="pb-3 font-bold">Total</th>
                            <th class="pb-3 font-bold">Status</th>
                            <th class="pb-3 font-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $statusStyles = [
                            'pending'    => 'bg-amber-500/15 text-amber-600',
                            'confirmed'  => 'bg-blue-500/15 text-blue-600',
                            'preparing'  => 'bg-purple-500/15 text-purple-600',
                            'on_the_way' => 'bg-orange-500/15 text-orange-600',
                            'delivered'  => 'bg-emerald-500/15 text-emerald-600',
                            'cancelled'  => 'bg-red-500/15 text-red-600',
                        ];
                        $statusLabels = [
                            'pending'    => 'Pending',
                            'confirmed'  => 'Confirmed',
                            'preparing'  => 'Preparing',
                            'on_the_way' => 'On the Way',
                            'delivered'  => 'Delivered',
                            'cancelled'  => 'Cancelled',
                        ];
                        @endphp
                        @foreach($recentOrders as $order)
                        <tr class="border-t border-black/5 dark:border-white/5">
                            <td class="py-3.5 font-bold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3.5">
                                <div class="flex items-center gap-2">
                                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-gradient-to-br from-brand-400 to-brand-700 text-xs font-bold text-white">
                                        {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                    </span>
                                    <span class="font-semibold">{{ $order->user->name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 text-ink/65 dark:text-orange-50/65">
                                {{ $order->items->sum('quantity') }} item(s)
                            </td>
                            <td class="py-3.5 font-semibold">KSh {{ number_format($order->total, 0) }}</td>
                            <td class="py-3.5">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold {{ $statusStyles[$order->status] ?? 'bg-ink/10 text-ink/60' }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-3.5">
                                <a href="{{ route('admin.order.index') }}"
                                   class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-brand-600 transition hover:bg-brand-500/10">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Top selling items --}}
        <div data-aos="fade-up" data-aos-delay="100" class="rounded-3xl glass p-6 shadow-soft">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-bold">Top Sellers</h2>
                <i data-lucide="trending-up" class="h-5 w-5 text-brand-600"></i>
            </div>

            @if($topDishes->isEmpty())
            <div class="py-10 text-center">
                <i data-lucide="utensils" class="mx-auto h-10 w-10 text-ink/20 dark:text-orange-50/20"></i>
                <p class="mt-3 text-sm font-semibold text-ink/50 dark:text-orange-50/50">No sales data yet</p>
            </div>
            @else
            <div class="space-y-3">
                @foreach($topDishes as $i => $dish)
                <div class="flex items-center gap-3">
                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full text-xs font-extrabold
                                 {{ $i === 0 ? 'bg-gold/20 text-yellow-700' : 'bg-ink/5 text-ink/50 dark:bg-white/10 dark:text-orange-50/50' }}">
                        {{ $i + 1 }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold">{{ $dish['name'] }}</p>
                        <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-ink/8 dark:bg-white/10">
                            <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-brand-600 transition-all duration-700"
                                 style="width: {{ $dish['pct'] }}%"></div>
                        </div>
                    </div>
                    <span class="shrink-0 text-xs font-bold text-ink/55 dark:text-orange-50/55">
                        {{ $dish['orders'] }} sold
                    </span>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Quick actions --}}
            <div class="mt-6 border-t border-black/5 pt-5 dark:border-white/5">
                <p class="mb-3 text-xs font-extrabold uppercase tracking-widest text-ink/40 dark:text-orange-50/40">Quick Actions</p>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('admin.menu.create') }}"
                       class="flex flex-col items-center gap-1.5 rounded-xl bg-brand-500/10 px-3 py-3 text-xs font-bold text-brand-600 transition hover:bg-brand-500/20">
                        <i data-lucide="plus-circle" class="h-5 w-5"></i> Add Item
                    </a>
                    <a href="{{ route('admin.order.index') }}"
                       class="flex flex-col items-center gap-1.5 rounded-xl bg-brand-500/10 px-3 py-3 text-xs font-bold text-brand-600 transition hover:bg-brand-500/20">
                        <i data-lucide="shopping-bag" class="h-5 w-5"></i> Orders
                    </a>
                    <a href="{{ route('admin.menu.index') }}"
                       class="flex flex-col items-center gap-1.5 rounded-xl bg-brand-500/10 px-3 py-3 text-xs font-bold text-brand-600 transition hover:bg-brand-500/20">
                        <i data-lucide="utensils" class="h-5 w-5"></i> Menu
                    </a>
                    <a href="{{ route('admin.analytics.index') }}"
                       class="flex flex-col items-center gap-1.5 rounded-xl bg-brand-500/10 px-3 py-3 text-xs font-bold text-brand-600 transition hover:bg-brand-500/20">
                        <i data-lucide="bar-chart-2" class="h-5 w-5"></i> Analytics
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
        if (window.AOS) AOS.init({ duration: 600, once: true, easing: 'ease-out-cubic' });
    });
</script>
@endpush