@extends('layouts.app')

@section('title', 'Menu — Cafe Delight')

@php $activeNav = 'Menu'; @endphp

@section('content')
<div class="space-y-8" data-aos="fade-up">

    {{-- ===== Hero Section ===== --}}
    <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-brand-500 via-brand-600 to-brand-800 p-8 text-white sm:p-12 shadow-premium"
         data-aos="fade-up">
        <div class="max-w-2xl">
            <h1 class="font-display text-4xl font-extrabold">Our Menu 🍽️</h1>
            <p class="mt-2 text-orange-50/90">Discover our delicious selection of dishes, crafted with love and the finest ingredients.</p>
        </div>
    </div>

    {{-- ===== Category Filter ===== --}}
    <div class="flex flex-wrap gap-2 pb-4 overflow-x-auto" data-aos="fade-up" data-aos-delay="100">
        <a href="{{ route('menu.index') }}" 
           class="inline-flex items-center gap-2 rounded-full px-4 py-2.5 font-semibold whitespace-nowrap transition
           {{ !isset($activeCategory) ? 'bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-glow' : 'bg-black/5 dark:bg-white/10 text-ink dark:text-orange-50 hover:bg-brand-500/10' }}">
            <i data-lucide="grid" class="w-4 h-4"></i>
            All Items
        </a>

        @foreach($categories as $category)
        <a href="{{ route('menu.category', $category) }}" 
           class="inline-flex items-center gap-2 rounded-full px-4 py-2.5 font-semibold whitespace-nowrap transition
           {{ isset($activeCategory) && $activeCategory === $category ? 'bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-glow' : 'bg-black/5 dark:bg-white/10 text-ink dark:text-orange-50 hover:bg-brand-500/10' }}">
            @switch($category)
                @case('mains')
                    <i data-lucide="utensils" class="w-4 h-4"></i> Main Courses
                    @break
                @case('appetizers')
                    <i data-lucide="cherry" class="w-4 h-4"></i> Appetizers
                    @break
                @case('desserts')
                    <i data-lucide="cake" class="w-4 h-4"></i> Desserts
                    @break
                @case('beverages')
                    <i data-lucide="wine" class="w-4 h-4"></i> Beverages
                    @break
                @case('sides')
                    <i data-lucide="pizza" class="w-4 h-4"></i> Sides
                    @break
            @endswitch
        </a>
        @endforeach
    </div>

    {{-- ===== Dishes Grid ===== --}}
    @if($dishes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($dishes as $dish)
            <div class="group rounded-2xl glass overflow-hidden shadow-soft hover:shadow-premium transition" 
                 data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 50 }}">
                
                {{-- Image --}}
                <div class="relative h-48 bg-gradient-to-br from-brand-300 to-brand-500 overflow-hidden">
                    @if($dish->primary_image)
                        <img src="{{ asset('storage/' . $dish->primary_image) }}" alt="{{ $dish->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="image" class="w-12 h-12 text-white/50"></i>
                        </div>
                    @endif

                    {{-- Badges --}}
                    <div class="absolute top-3 right-3 flex flex-wrap gap-2 justify-end">
                        @if($dish->is_bestseller)
                        <span class="inline-flex items-center gap-1 rounded-full bg-gold/90 text-yellow-900 px-2.5 py-1 text-xs font-bold backdrop-blur-sm">
                            ⭐ Best
                        </span>
                        @endif

                        @if($dish->is_vegetarian)
                        <span class="inline-flex items-center gap-1 rounded-full bg-green-500/90 text-white px-2 py-1 text-xs font-bold backdrop-blur-sm">
                            🌱
                        </span>
                        @endif

                        @if($dish->is_spicy)
                        <span class="inline-flex items-center gap-1 rounded-full bg-red-500/90 text-white px-2 py-1 text-xs font-bold backdrop-blur-sm">
                            🌶️
                        </span>
                        @endif
                    </div>

                    {{-- Quick Action Overlay --}}
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2 flex-col">
                        <button class="inline-flex items-center gap-2 bg-white text-brand-600 font-bold px-4 py-2 rounded-lg hover:scale-105 transition"
                                onclick="addToCart({{ $dish->id }}, 1)">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Add to Cart
                        </button>
                        <a href="{{ route('menu.show', $dish->id) }}" class="inline-flex items-center gap-2 bg-brand-600 text-white font-bold px-4 py-2 rounded-lg hover:scale-105 transition">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            View Details
                        </a>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-1">{{ $dish->name }}</h3>
                    <p class="text-sm text-ink/60 dark:text-orange-50/60 mb-3 line-clamp-2">{{ $dish->description }}</p>

                    {{-- Features Tags --}}
                    <div class="flex flex-wrap gap-1 mb-3">
                        @if($dish->is_vegan)
                            <span class="text-xs bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-300 px-2 py-1 rounded-full font-semibold">🥗 Vegan</span>
                        @endif
                        @if($dish->is_gluten_free)
                            <span class="text-xs bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-300 px-2 py-1 rounded-full font-semibold">🌾 GF</span>
                        @endif
                        @if($dish->is_dairy_free)
                            <span class="text-xs bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 px-2 py-1 rounded-full font-semibold">🥛 DF</span>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="pt-3 border-t border-black/5 dark:border-white/5 flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-extrabold text-gradient">KSh {{ number_format($dish->price, 0) }}</p>
                            @if($dish->prep_time)
                                <p class="text-xs text-ink/50 dark:text-orange-50/50 mt-1">⏱️ {{ $dish->prep_time }} mins</p>
                            @endif
                        </div>
                        <button class="grid h-9 w-9 place-items-center rounded-lg bg-brand-500/15 text-brand-600 hover:bg-brand-500/25 transition">
                            <i data-lucide="heart" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-12 rounded-2xl glass p-8 shadow-soft" data-aos="fade-up">
            <i data-lucide="inbox" class="w-16 h-16 text-black/20 dark:text-white/20 mx-auto mb-4"></i>
            <p class="text-ink/60 dark:text-orange-50/60 mb-2 font-semibold text-lg">No dishes available</p>
            <p class="text-ink/50 dark:text-orange-50/50">Check back soon for delicious options!</p>
        </div>
    @endif

</div>

@push('scripts')
<script>
    function addToCart(dishId, quantity = 1) {
        fetch(`/cart/add/${dishId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ quantity })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                updateCartCount();
            }
        })
        .catch(err => console.error(err));
    }

    function updateCartCount() {
        fetch('/cart/count')
            .then(r => r.json())
            .then(data => {
                const cartBadge = document.getElementById('cart-count');
                if (cartBadge) {
                    cartBadge.textContent = data.count;
                    cartBadge.style.display = data.count > 0 ? 'flex' : 'none';
                }
            });
    }

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg font-semibold shadow-lg z-50';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => toast.remove(), 3000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
        updateCartCount();
    });
</script>
@endpush
@endsection