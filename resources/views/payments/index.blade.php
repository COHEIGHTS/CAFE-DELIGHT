@extends('layouts.customer-layout')

@section('title', 'Payments — Cafe Delight')

@php $activeNav = 'Payments'; @endphp

@section('content')
<div class="space-y-8" data-aos="fade-up">

    {{-- Header --}}
    <div>
        <h1 class="font-display text-3xl font-bold">Payment History</h1>
        <p class="mt-2 text-ink/60 dark:text-orange-50/60">Track your payments and pending balances</p>
    </div>

    {{-- Summary Cards --}}
    <div class="grid gap-4 sm:grid-cols-2">
        <div data-aos="fade-up" data-aos-delay="0" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white">
                    <i data-lucide="check-circle" class="h-6 w-6"></i>
                </span>
                <div>
                    <p class="text-sm text-ink/60 dark:text-orange-50/60">Total Paid</p>
                    <p class="text-2xl font-extrabold text-emerald-600">KSh {{ number_format($totalPaid, 0) }}</p>
                </div>
            </div>
        </div>

        <div data-aos="fade-up" data-aos-delay="80" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-amber-500 to-amber-700 text-white">
                    <i data-lucide="clock" class="h-6 w-6"></i>
                </span>
                <div>
                    <p class="text-sm text-ink/60 dark:text-orange-50/60">Pending Payments</p>
                    <p class="text-2xl font-extrabold text-amber-600">KSh {{ number_format($totalPending, 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Paid Orders Section --}}
    <div data-aos="fade-up" data-aos-delay="160">
        <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
            <i data-lucide="check-circle" class="h-5 w-5 text-emerald-500"></i>
            Paid Orders
            <span class="text-sm font-normal text-ink/60 dark:text-orange-50/60">({{ $paidOrders->count() }})</span>
        </h2>

        @if($paidOrders->count() > 0)
            <div class="space-y-4">
                @foreach($paidOrders as $order)
                <div class="rounded-2xl glass p-6 shadow-soft hover:shadow-premium transition">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <h3 class="font-bold text-lg">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h3>
                                {!! $order->getStatusBadgeAttribute() !!}
                            </div>
                            <p class="text-sm text-ink/60 dark:text-orange-50/60 mt-1">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-extrabold text-emerald-600">KSh {{ number_format($order->total, 0) }}</p>
                            <p class="text-xs text-ink/60 dark:text-orange-50/60 mt-1">{{ $order->paymentMethodLabel() }}</p>
                        </div>
                    </div>

                    {{-- Order Items Preview --}}
                    <div class="mb-4 pb-4 border-b border-black/5 dark:border-white/5">
                        <div class="space-y-2">
                            @foreach($order->items->take(3) as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-ink/70 dark:text-orange-50/70">{{ $item->dish->name }} × {{ $item->quantity }}</span>
                                <span class="font-semibold">KSh {{ number_format($item->price * $item->quantity, 0) }}</span>
                            </div>
                            @endforeach
                            @if($order->items->count() > 3)
                            <p class="text-xs text-ink/60 dark:text-orange-50/60">+ {{ $order->items->count() - 3 }} more item(s)</p>
                            @endif
                        </div>
                    </div>

                    {{-- Payment Info & Actions --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-sm">
                            <p class="text-ink/60 dark:text-orange-50/60">💳 {{ $order->paymentMethodLabel() }} • {!! $order->payment_status_badge !!}</p>
                        </div>
                        <a href="{{ route('order.confirmation', $order) }}" class="inline-flex items-center gap-2 bg-brand-600 text-white font-bold px-4 py-2 rounded-lg hover:scale-105 transition text-sm">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            View Details
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 rounded-2xl glass p-6 shadow-soft">
                <i data-lucide="check-circle" class="w-12 h-12 text-emerald-500/30 mx-auto mb-3"></i>
                <p class="text-ink/60 dark:text-orange-50/60 font-semibold">No paid orders yet</p>
            </div>
        @endif
    </div>

    {{-- Pending Orders Section --}}
    <div data-aos="fade-up" data-aos-delay="240">
        <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
            <i data-lucide="clock" class="h-5 w-5 text-amber-500"></i>
            Pending Payments
            <span class="text-sm font-normal text-ink/60 dark:text-orange-50/60">({{ $pendingOrders->count() }})</span>
        </h2>

        @if($pendingOrders->count() > 0)
            <div class="space-y-4">
                @foreach($pendingOrders as $order)
                <div class="rounded-2xl glass p-6 shadow-soft hover:shadow-premium transition">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <h3 class="font-bold text-lg">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h3>
                                {!! $order->getStatusBadgeAttribute() !!}
                            </div>
                            <p class="text-sm text-ink/60 dark:text-orange-50/60 mt-1">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-extrabold text-amber-600">KSh {{ number_format($order->total, 0) }}</p>
                            <p class="text-xs text-ink/60 dark:text-orange-50/60 mt-1">{{ $order->paymentMethodLabel() }}</p>
                        </div>
                    </div>

                    {{-- Order Items Preview --}}
                    <div class="mb-4 pb-4 border-b border-black/5 dark:border-white/5">
                        <div class="space-y-2">
                            @foreach($order->items->take(3) as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-ink/70 dark:text-orange-50/70">{{ $item->dish->name }} × {{ $item->quantity }}</span>
                                <span class="font-semibold">KSh {{ number_format($item->price * $item->quantity, 0) }}</span>
                            </div>
                            @endforeach
                            @if($order->items->count() > 3)
                            <p class="text-xs text-ink/60 dark:text-orange-50/60">+ {{ $order->items->count() - 3 }} more item(s)</p>
                            @endif
                        </div>
                    </div>

                    {{-- Payment Info & Actions --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-sm">
                            <p class="text-ink/60 dark:text-orange-50/60">💳 {{ $order->paymentMethodLabel() }} • {!! $order->payment_status_badge !!}</p>
                            @if($order->payment_method === 'cash_on_delivery')
                                <p class="text-xs text-amber-600 mt-1">💡 Payment will be collected on delivery</p>
                            @endif
                        </div>
                        <a href="{{ route('order.confirmation', $order) }}" class="inline-flex items-center gap-2 bg-brand-600 text-white font-bold px-4 py-2 rounded-lg hover:scale-105 transition text-sm">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            View Details
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 rounded-2xl glass p-6 shadow-soft">
                <i data-lucide="clock" class="w-12 h-12 text-amber-500/30 mx-auto mb-3"></i>
                <p class="text-ink/60 dark:text-orange-50/60 font-semibold">No pending payments</p>
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
        if (window.AOS) AOS.init({ duration: 600, once: true, easing: 'ease-out-cubic' });
    });
</script>
@endpush
@endsection
