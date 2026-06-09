@extends('layouts.app')

@section('title', 'My Orders — Cafe Delight')

@php $activeNav = 'My Orders'; @endphp

@section('content')
<div class="space-y-8" data-aos="fade-up">

    {{-- Header --}}
    <div>
        <h1 class="font-display text-3xl font-bold">My Orders</h1>
        <p class="mt-2 text-ink/60 dark:text-orange-50/60">Track and manage your orders</p>
    </div>

    @if($orders->count() > 0)
        {{-- Orders List --}}
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="rounded-2xl glass p-6 shadow-soft hover:shadow-premium transition" data-aos="fade-up">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <h3 class="font-bold text-lg">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h3>
                            {!! $order->getStatusBadgeAttribute() !!}
                        </div>
                        <p class="text-sm text-ink/60 dark:text-orange-50/60 mt-1">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-extrabold text-gradient">KSh {{ number_format($order->total, 0) }}</p>
                        <p class="text-xs text-ink/60 dark:text-orange-50/60 mt-1">{{ $order->items->count() }} item(s)</p>
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

                {{-- Delivery Info & Actions --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm">
                        <p class="text-ink/60 dark:text-orange-50/60">📍 {{ $order->delivery_address }}</p>
                        <p class="text-ink/60 dark:text-orange-50/60">🚚 Est. Delivery: {{ $order->estimated_delivery_time->format('h:i A') }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('order.confirmation', $order) }}" class="inline-flex items-center gap-2 bg-brand-600 text-white font-bold px-4 py-2 rounded-lg hover:scale-105 transition text-sm">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            View Details
                        </a>
                        @if(in_array($order->status, ['pending', 'confirmed']))
                        <form action="{{ route('order.cancel', $order) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 bg-red-600 text-white font-bold px-4 py-2 rounded-lg hover:scale-105 transition text-sm"
                                    onclick="return confirm('Are you sure?')">
                                <i data-lucide="x" class="w-4 h-4"></i>
                                Cancel
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-12 rounded-2xl glass p-8 shadow-soft" data-aos="fade-up">
            <i data-lucide="shopping-bag" class="w-16 h-16 text-black/20 dark:text-white/20 mx-auto mb-4"></i>
            <p class="text-ink/60 dark:text-orange-50/60 mb-2 font-semibold text-lg">No orders yet</p>
            <p class="text-ink/50 dark:text-orange-50/50 mb-6">Start by placing your first order!</p>
            <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-600 to-brand-700 text-white font-bold px-6 py-3 rounded-xl hover:shadow-glow transition">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                Browse Menu
            </a>
        </div>
    @endif

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
    });
</script>
@endpush
@endsection