@extends('layouts.app')

@section('title', 'Shopping Cart — Cafe Delight')

@php $activeNav = 'Cart'; @endphp

@section('content')
<div class="max-w-6xl mx-auto" data-aos="fade-up">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="font-display text-3xl font-bold">🛒 Shopping Cart</h1>
        <p class="mt-2 text-ink/60 dark:text-orange-50/60">Review your items before checkout</p>
    </div>

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Cart Items --}}
            <div class="lg:col-span-2 space-y-4">
                @foreach($cartItems as $item)
                <div class="rounded-2xl glass p-4 shadow-soft flex gap-4 items-center group hover:shadow-premium transition" data-aos="fade-up">
                    
                    {{-- Image --}}
                    <div class="w-24 h-24 shrink-0 rounded-xl bg-gradient-to-br from-brand-300 to-brand-500 overflow-hidden">
                        @if($item->dish->primary_image)
                            <img src="{{ asset('storage/' . $item->dish->primary_image) }}" alt="{{ $item->dish->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i data-lucide="image" class="w-8 h-8 text-white/50"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Details --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-lg mb-1">{{ $item->dish->name }}</h3>
                        <p class="text-sm text-ink/60 dark:text-orange-50/60 mb-3 line-clamp-1">{{ $item->dish->description }}</p>
                        <div class="flex items-center gap-4">
                            <span class="text-2xl font-extrabold text-gradient">KSh {{ number_format($item->dish->price, 0) }}</span>
                            
                            {{-- Quantity Controls --}}
                            <div class="flex items-center gap-2 bg-black/5 dark:bg-white/10 rounded-lg px-2 py-1">
                                <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                        class="text-brand-600 hover:text-brand-700 transition p-1">
                                    <i data-lucide="minus" class="w-4 h-4"></i>
                                </button>
                                <span class="w-8 text-center font-bold text-sm">{{ $item->quantity }}</span>
                                <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                        class="text-brand-600 hover:text-brand-700 transition p-1">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Subtotal & Delete --}}
                    <div class="text-right">
                        <p class="text-sm text-ink/60 dark:text-orange-50/60 mb-2">Subtotal</p>
                        <p class="text-xl font-bold text-gradient mb-4">KSh {{ number_format($item->dish->price * $item->quantity, 0) }}</p>
                        <button onclick="removeFromCart({{ $item->id }})"
                                class="text-red-500 hover:text-red-700 transition font-semibold text-sm flex items-center gap-1">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            Remove
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Order Summary --}}
            <div class="rounded-2xl glass p-6 shadow-soft h-fit sticky top-24" data-aos="fade-up" data-aos-delay="100">
                <h2 class="text-lg font-bold mb-6">Order Summary</h2>

                <div class="space-y-3 mb-6 pb-6 border-b border-black/5 dark:border-white/5">
                    <div class="flex justify-between text-sm">
                        <span class="text-ink/60 dark:text-orange-50/60">Subtotal</span>
                        <span class="font-semibold">KSh {{ number_format($total, 0) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-ink/60 dark:text-orange-50/60">Delivery Fee</span>
                        <span class="font-semibold">KSh {{ number_format($deliveryFee, 0) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-ink/60 dark:text-orange-50/60">Tax ({{ number_format($tax / $total * 100, 0) }}%)</span>
                        <span class="font-semibold">KSh {{ number_format($tax, 0) }}</span>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-6">
                    <span class="font-bold text-lg">Total</span>
                    <span class="text-2xl font-extrabold text-gradient">KSh {{ number_format($grandTotal, 0) }}</span>
                </div>

                {{-- ↓ CHANGED: button → anchor tag routed to checkout.index --}}
                <a href="{{ route('checkout.index') }}"
                   class="block w-full text-center bg-gradient-to-r from-brand-600 to-brand-700 text-white font-bold py-3 rounded-xl hover:shadow-glow transition mb-3">
                    Proceed to Checkout
                </a>

                <a href="{{ route('menu.index') }}" class="block w-full text-center bg-black/5 dark:bg-white/10 text-ink dark:text-orange-50 font-bold py-3 rounded-xl hover:scale-105 transition">
                    Continue Shopping
                </a>
            </div>
        </div>
    @else
        {{-- Empty Cart --}}
        <div class="text-center py-12 rounded-2xl glass p-8 shadow-soft">
            <i data-lucide="shopping-bag" class="w-16 h-16 text-black/20 dark:text-white/20 mx-auto mb-4"></i>
            <p class="text-ink/60 dark:text-orange-50/60 mb-2 font-semibold text-lg">Your cart is empty</p>
            <p class="text-ink/50 dark:text-orange-50/50 mb-6">Add some delicious dishes to get started!</p>
            <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-600 to-brand-700 text-white font-bold px-6 py-3 rounded-xl hover:shadow-glow transition">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                Back to Menu
            </a>
        </div>
    @endif

</div>

@push('scripts')
<script>
function removeFromCart(cartItemId) {
    if (!confirm('Remove this item from cart?')) return;

    fetch(`/cart/${cartItemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(err => console.error(err));
}

function updateQuantity(cartItemId, quantity) {
    if (quantity < 1) {
        removeFromCart(cartItemId);
        return;
    }

    fetch(`/cart/${cartItemId}`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ quantity })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(err => console.error(err));
}
</script>
@endpush
@endsection