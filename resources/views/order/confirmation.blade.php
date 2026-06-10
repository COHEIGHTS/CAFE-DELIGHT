@extends('layouts.customer-layout')

@section('title', 'Order #{{ str_pad($order->id, 6, "0", STR_PAD_LEFT) }} — Cafe Delight')
@php $activeNav = 'My Orders'; @endphp

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    {{-- Header --}}
    <div data-aos="fade-up" class="flex items-center gap-3">
        <a href="{{ route('order.index') }}"
           class="grid h-9 w-9 place-items-center rounded-xl glass text-ink/60 transition hover:text-brand-600">
            <i data-lucide="arrow-left" class="h-5 w-5"></i>
        </a>
        <div>
            <h1 class="font-display text-2xl font-extrabold">
                Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
            </h1>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">
                Placed on {{ $order->created_at->format('d M Y, h:i A') }}
            </p>
        </div>
        <div class="ml-auto">
            {!! $order->getStatusBadgeAttribute() !!}
        </div>
    </div>

    {{-- Status timeline --}}
    <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
        <h2 class="mb-5 text-base font-extrabold">Order Status</h2>
        @php
            $steps = [
                ['key' => 'pending',    'label' => 'Order Placed',   'icon' => 'check-circle'],
                ['key' => 'confirmed',  'label' => 'Confirmed',       'icon' => 'clipboard-check'],
                ['key' => 'preparing',  'label' => 'Preparing',       'icon' => 'utensils'],
                ['key' => 'on_the_way', 'label' => 'On the Way',      'icon' => 'bike'],
                ['key' => 'delivered',  'label' => 'Delivered',       'icon' => 'package-check'],
            ];
            $statusOrder = ['pending','confirmed','preparing','on_the_way','delivered'];
            $currentIndex = array_search($order->status, $statusOrder);
        @endphp

        @if($order->status === 'cancelled')
            <div class="flex items-center gap-3 rounded-2xl bg-red-500/10 border border-red-500/20 px-5 py-4">
                <i data-lucide="x-circle" class="h-6 w-6 text-red-500 shrink-0"></i>
                <div>
                    <p class="font-bold text-red-500">Order Cancelled</p>
                    <p class="text-xs text-ink/55 dark:text-orange-50/55">This order has been cancelled.</p>
                </div>
            </div>
        @else
            <div class="flex items-center gap-0">
                @foreach($steps as $i => $step)
                    @php $done = $currentIndex !== false && $i <= $currentIndex; @endphp
                    <div class="flex flex-1 flex-col items-center">
                        <div class="grid h-10 w-10 place-items-center rounded-full transition
                            {{ $done ? 'bg-gradient-to-br from-brand-500 to-brand-600 text-white shadow-glow' : 'bg-black/8 dark:bg-white/10 text-ink/30 dark:text-orange-50/30' }}">
                            <i data-lucide="{{ $step['icon'] }}" class="h-5 w-5"></i>
                        </div>
                        <p class="mt-2 text-center text-[10px] font-bold leading-tight
                            {{ $done ? 'text-brand-600' : 'text-ink/40 dark:text-orange-50/40' }}">
                            {{ $step['label'] }}
                        </p>
                    </div>
                    @if(!$loop->last)
                        <div class="mb-5 h-0.5 flex-1
                            {{ $currentIndex !== false && $i < $currentIndex ? 'bg-brand-500' : 'bg-black/8 dark:bg-white/10' }}"></div>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- Estimated delivery --}}
        @if($order->estimated_delivery_time && !in_array($order->status, ['delivered','cancelled']))
        <div class="mt-5 flex items-center gap-3 rounded-2xl bg-brand-500/8 border border-brand-500/20 px-4 py-3">
            <i data-lucide="clock" class="h-5 w-5 text-brand-600 shrink-0"></i>
            <p class="text-sm font-semibold">
                Estimated delivery:
                <strong class="text-brand-600">{{ \Carbon\Carbon::parse($order->estimated_delivery_time)->format('h:i A') }}</strong>
            </p>
        </div>
        @endif
    </div>

    {{-- Order items --}}
    <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
        <h2 class="mb-5 text-base font-extrabold">Items Ordered</h2>
        <div class="space-y-4">
            @foreach($order->items as $item)
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 shrink-0 overflow-hidden rounded-xl bg-gradient-to-br from-brand-100 to-brand-200 dark:from-brand-900/30 dark:to-brand-800/30">
                    @if($item->dish->primary_image)
                        <img src="{{ asset('storage/' . $item->dish->primary_image) }}"
                             alt="{{ $item->dish->name }}"
                             class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-2xl">🍽️</div>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-bold">{{ $item->dish->name }}</p>
                    <p class="text-xs text-ink/55 dark:text-orange-50/55">
                        KSh {{ number_format($item->price, 0) }} × {{ $item->quantity }}
                    </p>
                </div>
                <span class="font-extrabold text-brand-600">
                    KSh {{ number_format($item->price * $item->quantity, 0) }}
                </span>
            </div>
            @endforeach
        </div>

        {{-- Totals --}}
        <div class="mt-5 space-y-2.5 border-t border-black/5 pt-5 dark:border-white/5">
            <div class="flex justify-between text-sm">
                <span class="text-ink/60 dark:text-orange-50/60">Subtotal</span>
                <span class="font-semibold">KSh {{ number_format($order->subtotal, 0) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-ink/60 dark:text-orange-50/60">Delivery Fee</span>
                <span class="font-semibold">KSh {{ number_format($order->delivery_fee, 0) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-ink/60 dark:text-orange-50/60">Tax (16%)</span>
                <span class="font-semibold">KSh {{ number_format($order->tax, 0) }}</span>
            </div>
            <div class="flex justify-between border-t border-black/5 pt-3 dark:border-white/5">
                <span class="font-extrabold text-lg">Total</span>
                <span class="text-xl font-extrabold text-brand-600">KSh {{ number_format($order->total, 0) }}</span>
            </div>
        </div>
    </div>

    {{-- Delivery details --}}
    <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
        <h2 class="mb-5 text-base font-extrabold">Delivery Details</h2>
        <div class="space-y-3">
            <div class="flex items-start gap-3">
                <i data-lucide="map-pin" class="mt-0.5 h-5 w-5 shrink-0 text-brand-600"></i>
                <div>
                    <p class="text-xs font-semibold text-ink/45 dark:text-orange-50/45">Address</p>
                    <p class="font-semibold">{{ $order->delivery_address }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <i data-lucide="phone" class="h-5 w-5 shrink-0 text-brand-600"></i>
                <div>
                    <p class="text-xs font-semibold text-ink/45 dark:text-orange-50/45">Phone</p>
                    <p class="font-semibold">{{ $order->phone }}</p>
                </div>
            </div>
            @if($order->special_instructions)
            <div class="flex items-start gap-3">
                <i data-lucide="message-square" class="mt-0.5 h-5 w-5 shrink-0 text-brand-600"></i>
                <div>
                    <p class="text-xs font-semibold text-ink/45 dark:text-orange-50/45">Special Instructions</p>
                    <p class="font-semibold">{{ $order->special_instructions }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    <div data-aos="fade-up" class="flex flex-wrap gap-3">
        @if(in_array($order->status, ['pending', 'confirmed']))
        <form method="POST" action="{{ route('order.cancel', $order) }}">
            @csrf
            <button type="submit"
                    onclick="return confirm('Cancel this order?')"
                    class="flex items-center gap-2 rounded-xl border border-red-500/30 bg-red-500/10 px-5 py-3 text-sm font-bold text-red-500 transition hover:bg-red-500/20">
                <i data-lucide="x-circle" class="h-4 w-4"></i> Cancel Order
            </button>
        </form>
        @endif
        <a href="{{ route('menu.index') }}"
           class="flex items-center gap-2 rounded-xl glass px-5 py-3 text-sm font-bold transition hover:bg-brand-500/10 hover:text-brand-600">
            <i data-lucide="utensils" class="h-4 w-4"></i> Order Again
        </a>
    </div>

</div>
@endsection