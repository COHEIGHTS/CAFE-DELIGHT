@extends('layouts.customer-layout')

@section('title', 'Dashboard — Cafe Delight')

@php $activeNav = 'Dashboard'; @endphp

@section('content')
<div class="space-y-6">

    {{-- ===== Welcome banner ===== --}}
    <div data-aos="fade-up"
         class="overflow-hidden rounded-3xl bg-gradient-to-br from-brand-500 via-brand-600 to-brand-800 p-6 text-white sm:p-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-extrabold sm:text-3xl">
                    Welcome back, {{ auth()->user()->name }}! 👋
                </h1>
                <p class="mt-1 text-orange-50/90">Hungry again? Your favorites are just a click away.</p>
            </div>
            <a href="{{ route('menu.index') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-brand-700 transition hover:scale-105">
                <i data-lucide="plus" class="h-4 w-4"></i> New Order
            </a>
        </div>
    </div>

    {{-- ===== Stat cards ===== --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

        <div data-aos="fade-up" data-aos-delay="0" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white">
                    <i data-lucide="shopping-bag" class="h-6 w-6"></i>
                </span>
                <span class="text-xs font-bold {{ $ordersDelta >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                    {{ $ordersDelta >= 0 ? '+' : '' }}{{ $ordersDelta }}% this month
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">{{ $totalOrders }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Total Orders</p>
        </div>

        <div data-aos="fade-up" data-aos-delay="80" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white">
                    <i data-lucide="wallet" class="h-6 w-6"></i>
                </span>
                <span class="text-xs font-bold {{ $spentDelta >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                    {{ $spentDelta >= 0 ? '+' : '' }}{{ $spentDelta }}% this month
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">KSh {{ number_format($totalSpent, 0) }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Total Spent</p>
        </div>

        <div data-aos="fade-up" data-aos-delay="160" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-amber-500 to-amber-700 text-white">
                    <i data-lucide="clock" class="h-6 w-6"></i>
                </span>
                <span class="text-xs font-bold {{ $activeOrders > 0 ? 'text-amber-500' : 'text-emerald-500' }}">
                    {{ $activeOrders > 0 ? 'In progress' : 'All clear' }}
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">{{ $activeOrders }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Active Orders</p>
        </div>

    </div>

    {{-- ===== Recent Orders + Quick Reorder ===== --}}
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Recent orders --}}
        <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold">Recent Orders</h2>
                <a href="{{ route('order.index') }}"
                   class="text-sm font-bold text-brand-600 hover:underline">View all</a>
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
                            <th class="pb-3 font-bold">Items</th>
                            <th class="pb-3 font-bold">Total</th>
                            <th class="pb-3 font-bold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr class="border-t border-black/5 dark:border-white/5">
                            <td class="py-3.5 font-bold">
                                <a href="{{ route('order.confirmation', $order->id) }}"
                                   class="hover:text-brand-600">
                                    #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td class="py-3.5 text-ink/65 dark:text-orange-50/65">
                                {{ $order->items->take(2)->map(fn($i) => $i->dish->name)->join(', ') }}
                                @if($order->items->count() > 2)
                                    <span class="text-xs text-ink/40">+{{ $order->items->count() - 2 }} more</span>
                                @endif
                            </td>
                            <td class="py-3.5 font-semibold">KSh {{ number_format($order->total, 0) }}</td>
                            <td class="py-3.5">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold {{ $order->statusClass() }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                    {{ $order->statusLabel() }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Quick Reorder --}}
        <div data-aos="fade-up" data-aos-delay="100" class="rounded-3xl glass p-6 shadow-soft">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold">Quick Reorder</h2>
                <i data-lucide="repeat" class="h-5 w-5 text-brand-600"></i>
            </div>

            @if($favoriteDishes->isEmpty())
            <div class="py-10 text-center">
                <i data-lucide="utensils" class="mx-auto h-10 w-10 text-ink/20 dark:text-orange-50/20"></i>
                <p class="mt-3 text-sm font-semibold text-ink/50 dark:text-orange-50/50">
                    Order something to see your favourites here.
                </p>
            </div>
            @else
            <div class="space-y-3">
                @foreach($favoriteDishes as $dish)
                <div class="flex items-center gap-3 rounded-2xl glass p-3 transition hover:shadow-soft">
                    <span class="grid h-12 w-12 shrink-0 place-items-center overflow-hidden rounded-xl bg-gradient-to-br from-brand-100 to-brand-200 dark:from-brand-500/30 dark:to-brand-700/30">
                        @if($dish->primary_image)
                            <img src="{{ asset('storage/' . $dish->primary_image) }}"
                                 alt="{{ $dish->name }}"
                                 class="h-full w-full object-cover">
                        @else
                            <i data-lucide="utensils" class="h-5 w-5 text-brand-500"></i>
                        @endif
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold">{{ $dish->name }}</p>
                        <p class="text-xs font-semibold text-brand-600">KSh {{ number_format($dish->price, 0) }}</p>
                    </div>

                    {{-- ✅ fetch-based button — no form, no page navigation --}}
                    <button type="button"
                            onclick="quickReorder({{ $dish->id }}, this)"
                            class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 text-white transition hover:scale-110 disabled:opacity-60 disabled:cursor-not-allowed">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                    </button>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

</div>

{{-- ===== Reorder success mini-popup ===== --}}
<div id="reorder-popup"
     class="fixed bottom-6 right-6 z-50 hidden items-center gap-3
            bg-white dark:bg-gray-900 border border-black/5 dark:border-white/10
            rounded-2xl shadow-premium px-5 py-4 max-w-xs w-full
            opacity-0 translate-y-3 transition-all duration-300">
    <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-green-100 dark:bg-green-500/20">
        <i data-lucide="check" class="h-5 w-5 text-green-500"></i>
    </div>
    <div class="flex-1 min-w-0">
        <p id="reorder-popup-title" class="text-sm font-bold truncate"></p>
        <p class="text-xs text-ink/50 dark:text-orange-50/50">Added to your cart</p>
    </div>
    <a href="{{ route('cart.index') }}"
       class="shrink-0 text-xs font-bold text-brand-600 hover:underline">
        View
    </a>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
        if (window.AOS) AOS.init({ duration: 600, once: true, easing: 'ease-out-cubic' });
        updateCartCount();
    });

    let reorderTimer = null;

    function quickReorder(dishId, btn) {
        // Disable button and show spinner
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="h-4 w-4 animate-spin"></i>';
        if (window.lucide) lucide.createIcons();

        fetch(`/cart/add/${dishId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept':       'application/json',
            },
            body: JSON.stringify({ quantity: 1 })
        })
        .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
        .then(data => {
            if (data.success) {
                // Update cart badge
                updateCartCount(data.cartCount);

                // Show success state on button
                btn.innerHTML = '<i data-lucide="check" class="h-4 w-4"></i>';
                if (window.lucide) lucide.createIcons();

                // Show mini popup
                showReorderPopup(data.message.replace(' added to cart!', ''));

                // Reset button after 2s
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i data-lucide="plus" class="h-4 w-4"></i>';
                    if (window.lucide) lucide.createIcons();
                }, 2000);
            } else {
                resetReorderBtn(btn);
            }
        })
        .catch(() => resetReorderBtn(btn));
    }

    function resetReorderBtn(btn) {
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="plus" class="h-4 w-4"></i>';
        if (window.lucide) lucide.createIcons();
    }

    function showReorderPopup(dishName) {
        clearTimeout(reorderTimer);

        const popup = document.getElementById('reorder-popup');
        document.getElementById('reorder-popup-title').textContent = dishName;

        // Show
        popup.classList.remove('hidden');
        popup.style.display = 'flex';
        requestAnimationFrame(() => requestAnimationFrame(() => {
            popup.classList.remove('opacity-0', 'translate-y-3');
            popup.classList.add('opacity-100', 'translate-y-0');
        }));

        if (window.lucide) lucide.createIcons();

        // Auto-hide after 3s
        reorderTimer = setTimeout(() => {
            popup.classList.remove('opacity-100', 'translate-y-0');
            popup.classList.add('opacity-0', 'translate-y-3');
            setTimeout(() => {
                popup.classList.add('hidden');
                popup.style.display = '';
            }, 300);
        }, 3000);
    }

    // updateCartCount is defined globally in app.blade.php
    // but re-defined here as fallback in case layout differs
    if (typeof updateCartCount === 'undefined') {
        function updateCartCount(count = null) {
            if (count !== null) {
                const badge = document.getElementById('cart-count');
                if (badge) {
                    badge.textContent   = count;
                    badge.style.display = count > 0 ? 'grid' : 'none';
                }
                return;
            }
            fetch('/cart/count', { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(d => {
                    const badge = document.getElementById('cart-count');
                    if (badge) {
                        badge.textContent   = d.count;
                        badge.style.display = d.count > 0 ? 'grid' : 'none';
                    }
                }).catch(() => {});
        }
    }
</script>
@endpush