@extends('layouts.admin-layout')

@section('title', 'Orders — Admin · Cafe Delight')
@php $activeNav = 'Orders'; @endphp

@section('content')
<div class="space-y-6" x-data="adminOrders()">

    {{-- Header --}}
    <div data-aos="fade-up" class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-extrabold">Orders</h1>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Manage and update customer order statuses.</p>
        </div>
        {{-- Live order count badges --}}
        <div class="flex flex-wrap gap-2">
            @foreach([
                ['status' => 'pending',    'label' => 'Pending',    'cls' => 'bg-amber-500/15 text-amber-600'],
                ['status' => 'confirmed',  'label' => 'Confirmed',  'cls' => 'bg-blue-500/15 text-blue-600'],
                ['status' => 'preparing',  'label' => 'Preparing',  'cls' => 'bg-purple-500/15 text-purple-600'],
                ['status' => 'on_the_way', 'label' => 'On the Way', 'cls' => 'bg-orange-500/15 text-orange-600'],
            ] as $badge)
            <span class="rounded-full px-3 py-1.5 text-xs font-extrabold {{ $badge['cls'] }}">
                {{ $badge['label'] }}:
                {{ $orders->where('status', $badge['status'])->count() }}
            </span>
            @endforeach
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div data-aos="fade-up" class="flex items-center gap-3 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 px-5 py-4 text-sm font-semibold text-emerald-600">
        <i data-lucide="check-circle-2" class="h-5 w-5 shrink-0"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Filter tabs --}}
    <div data-aos="fade-up" class="flex flex-wrap gap-2 rounded-2xl glass p-3">
        @foreach([
            'all'        => 'All Orders',
            'pending'    => 'Pending',
            'confirmed'  => 'Confirmed',
            'preparing'  => 'Preparing',
            'on_the_way' => 'On the Way',
            'delivered'  => 'Delivered',
            'cancelled'  => 'Cancelled',
        ] as $value => $label)
        <a href="{{ request()->fullUrlWithQuery(['status' => $value === 'all' ? null : $value]) }}"
           class="rounded-xl px-4 py-2 text-sm font-bold transition
               {{ (request('status', 'all') === $value || ($value === 'all' && !request('status')))
                   ? 'bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-glow'
                   : 'text-ink/60 hover:bg-brand-500/10 hover:text-brand-600 dark:text-orange-50/60' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Orders list --}}
    <div class="space-y-4">
        @forelse($orders as $order)
        <div data-aos="fade-up" class="rounded-3xl glass shadow-soft overflow-hidden">

            {{-- Order header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-black/5 p-5 dark:border-white/5">
                <div class="flex flex-wrap items-center gap-4">
                    {{-- Avatar + customer --}}
                    <div class="flex items-center gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-brand-400 to-brand-700 text-sm font-extrabold text-white">
                            {{ strtoupper(substr($order->user->name, 0, 2)) }}
                        </span>
                        <div>
                            <p class="font-extrabold leading-none">{{ $order->user->name }}</p>
                            <p class="text-xs text-ink/50 dark:text-orange-50/50">{{ $order->user->email }}</p>
                        </div>
                    </div>

                    <div class="h-6 w-px bg-black/10 dark:bg-white/10 hidden sm:block"></div>

                    <div>
                        <p class="text-xs text-ink/45 dark:text-orange-50/45 font-semibold">Order ID</p>
                        <p class="font-extrabold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-ink/45 dark:text-orange-50/45 font-semibold">Placed</p>
                        <p class="font-semibold text-sm">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-ink/45 dark:text-orange-50/45 font-semibold">Total</p>
                        <p class="font-extrabold text-brand-600">KSh {{ number_format($order->total, 0) }}</p>
                    </div>
                </div>

                {{-- Status badge --}}
                <div class="flex items-center gap-3">
                    {!! $order->getStatusBadgeAttribute() !!}
                    {{-- Expand toggle --}}
                    <button x-on:click="toggle({{ $order->id }})"
                            class="grid h-9 w-9 place-items-center rounded-xl glass transition hover:bg-brand-500/10 hover:text-brand-600">
                        <i :data-lucide="open === {{ $order->id }} ? 'chevron-up' : 'chevron-down'" class="h-5 w-5"></i>
                    </button>
                </div>
            </div>

            {{-- Expandable details --}}
            <div x-show="open === {{ $order->id }}" x-transition x-cloak>

                <div class="grid gap-0 lg:grid-cols-3">

                    {{-- Items --}}
                    <div class="border-b border-black/5 p-5 dark:border-white/5 lg:col-span-2 lg:border-b-0 lg:border-r">
                        <p class="mb-3 text-xs font-extrabold uppercase tracking-widest text-ink/40 dark:text-orange-50/40">Items Ordered</p>
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 shrink-0 overflow-hidden rounded-xl bg-gradient-to-br from-brand-100 to-brand-200 dark:from-brand-900/30 dark:to-brand-800/30">
                                    @if($item->dish->primary_image)
                                        <img src="{{ asset('storage/' . $item->dish->primary_image) }}"
                                             alt="{{ $item->dish->name }}"
                                             class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-xl">🍽️</div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-bold text-sm">{{ $item->dish->name }}</p>
                                    <p class="text-xs text-ink/55 dark:text-orange-50/55">
                                        KSh {{ number_format($item->price, 0) }} × {{ $item->quantity }}
                                    </p>
                                </div>
                                <span class="font-extrabold text-sm text-brand-600">
                                    KSh {{ number_format($item->price * $item->quantity, 0) }}
                                </span>
                            </div>
                            @endforeach
                        </div>

                        {{-- Totals --}}
                        <div class="mt-4 space-y-1.5 border-t border-black/5 pt-4 dark:border-white/5 text-sm">
                            <div class="flex justify-between">
                                <span class="text-ink/55 dark:text-orange-50/55">Subtotal</span>
                                <span class="font-semibold">KSh {{ number_format($order->subtotal, 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ink/55 dark:text-orange-50/55">Delivery Fee</span>
                                <span class="font-semibold">KSh {{ number_format($order->delivery_fee, 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ink/55 dark:text-orange-50/55">Tax (16%)</span>
                                <span class="font-semibold">KSh {{ number_format($order->tax, 0) }}</span>
                            </div>
                            <div class="flex justify-between border-t border-black/5 pt-2 dark:border-white/5">
                                <span class="font-extrabold">Total</span>
                                <span class="font-extrabold text-brand-600">KSh {{ number_format($order->total, 0) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Delivery + status actions --}}
                    <div class="p-5 space-y-5">

                        {{-- Delivery details --}}
                        <div>
                            <p class="mb-3 text-xs font-extrabold uppercase tracking-widest text-ink/40 dark:text-orange-50/40">Delivery Details</p>
                            <div class="space-y-2.5">
                                <div class="flex items-start gap-2.5">
                                    <i data-lucide="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600"></i>
                                    <p class="text-sm font-semibold">{{ $order->delivery_address }}</p>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <i data-lucide="phone" class="h-4 w-4 shrink-0 text-brand-600"></i>
                                    <p class="text-sm font-semibold">{{ $order->phone }}</p>
                                </div>
                                @if($order->special_instructions)
                                <div class="flex items-start gap-2.5">
                                    <i data-lucide="message-square" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600"></i>
                                    <p class="text-sm font-semibold">{{ $order->special_instructions }}</p>
                                </div>
                                @endif
                                @if($order->estimated_delivery_time)
                                <div class="flex items-center gap-2.5">
                                    <i data-lucide="clock" class="h-4 w-4 shrink-0 text-brand-600"></i>
                                    <p class="text-sm font-semibold">ETA: {{ $order->estimated_delivery_time->format('h:i A') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Status update actions --}}
                        @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                        <div>
                            <p class="mb-3 text-xs font-extrabold uppercase tracking-widest text-ink/40 dark:text-orange-50/40">Update Status</p>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $nextStatuses = [
                                        'pending'    => [['value' => 'confirmed',  'label' => 'Confirm',      'icon' => 'clipboard-check', 'cls' => 'from-blue-500 to-blue-600']],
                                        'confirmed'  => [['value' => 'preparing',  'label' => 'Start Preparing','icon' => 'utensils',       'cls' => 'from-purple-500 to-purple-600']],
                                        'preparing'  => [['value' => 'on_the_way', 'label' => 'Out for Delivery','icon' => 'bike',          'cls' => 'from-orange-500 to-orange-600']],
                                        'on_the_way' => [['value' => 'delivered',  'label' => 'Mark Delivered','icon' => 'package-check',  'cls' => 'from-emerald-500 to-emerald-600']],
                                    ];
                                    $actions = $nextStatuses[$order->status] ?? [];
                                @endphp

                                @foreach($actions as $action)
                                <form method="POST" action="{{ route('admin.order.updateStatus', $order) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $action['value'] }}">
                                    <button type="submit"
                                            class="flex items-center gap-2 rounded-xl bg-gradient-to-r {{ $action['cls'] }} px-4 py-2.5 text-xs font-extrabold text-white shadow-soft transition hover:scale-105">
                                        <i data-lucide="{{ $action['icon'] }}" class="h-4 w-4"></i>
                                        {{ $action['label'] }}
                                    </button>
                                </form>
                                @endforeach

                                {{-- Cancel button for pending/confirmed --}}
                                @if(in_array($order->status, ['pending', 'confirmed']))
                                <form method="POST" action="{{ route('admin.order.updateStatus', $order) }}"
                                      onsubmit="return confirm('Cancel this order?')">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit"
                                            class="flex items-center gap-2 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-2.5 text-xs font-extrabold text-red-500 transition hover:bg-red-500/20">
                                        <i data-lucide="x-circle" class="h-4 w-4"></i> Cancel
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="rounded-2xl {{ $order->status === 'delivered' ? 'bg-emerald-500/10 border border-emerald-500/20' : 'bg-red-500/10 border border-red-500/20' }} px-4 py-3">
                            <p class="text-sm font-extrabold {{ $order->status === 'delivered' ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $order->status === 'delivered' ? '✅ Order delivered successfully' : '❌ Order was cancelled' }}
                            </p>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="rounded-3xl glass p-12 text-center shadow-soft">
            <div class="text-5xl mb-4">📋</div>
            <p class="font-bold text-lg">No orders found</p>
            <p class="text-sm text-ink/55 mt-1">Orders placed by customers will appear here.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="flex justify-center">{{ $orders->links() }}</div>
    @endif

</div>
@endsection

@push('scripts')
<script>
function adminOrders() {
    return {
        open: null,
        toggle(id) {
            this.open = this.open === id ? null : id;
            this.$nextTick(() => lucide.createIcons());
        },
        init() {
            // Auto-open first pending order
            const firstPending = {{ $orders->where('status', 'pending')->first()?->id ?? 'null' }};
            if (firstPending) this.open = firstPending;
            this.$nextTick(() => lucide.createIcons());
        }
    }
}
</script>
@endpush