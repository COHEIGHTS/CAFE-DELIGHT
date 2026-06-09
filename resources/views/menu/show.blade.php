@extends('layouts.app')

@section('title', $dish->name . ' — Cafe Delight')

@php $activeNav = 'Menu'; @endphp

@section('content')
<div class="max-w-4xl mx-auto" data-aos="fade-up">

    {{-- Back Button --}}
    <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 text-brand-600 hover:text-brand-700 font-semibold mb-6 transition">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
        Back to Menu
    </a>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- ===== Images Section ===== --}}
        <div class="space-y-4">
            {{-- Main Image --}}
            <div class="rounded-2xl overflow-hidden shadow-premium h-96 bg-gradient-to-br from-brand-300 to-brand-500 cursor-pointer group" 
                 onclick="changeMainImage(this)">
                <img id="main-image" src="{{ $dish->primary_image ? asset('storage/' . $dish->primary_image) : '' }}" 
                     alt="{{ $dish->name }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @if(!$dish->primary_image)
                <div class="w-full h-full flex items-center justify-center">
                    <i data-lucide="image" class="w-20 h-20 text-white/50"></i>
                </div>
                @endif
            </div>

            {{-- Thumbnail Images --}}
            <div class="grid grid-cols-2 gap-4">
                @if($dish->secondary_image)
                <div class="rounded-xl overflow-hidden shadow-soft h-24 bg-gradient-to-br from-brand-300 to-brand-500 cursor-pointer hover:shadow-premium transition group"
                     onclick="changeMainImage(this)">
                    <img src="{{ asset('storage/' . $dish->secondary_image) }}" alt="Secondary" 
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                @endif

                @if($dish->tertiary_image)
                <div class="rounded-xl overflow-hidden shadow-soft h-24 bg-gradient-to-br from-brand-300 to-brand-500 cursor-pointer hover:shadow-premium transition group"
                     onclick="changeMainImage(this)">
                    <img src="{{ asset('storage/' . $dish->tertiary_image) }}" alt="Tertiary" 
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                @endif
            </div>
        </div>

        {{-- ===== Details Section ===== --}}
        <div class="space-y-6">

            {{-- Name & Rating --}}
            <div>
                <div class="flex items-start justify-between mb-2">
                    <h1 class="font-display text-3xl font-bold">{{ $dish->name }}</h1>
                    <button class="grid h-10 w-10 place-items-center rounded-lg bg-brand-500/15 text-brand-600 hover:bg-brand-500/25 transition"
                            onclick="toggleFavorite({{ $dish->id }})">
                        <i data-lucide="heart" class="w-6 h-6" id="favorite-btn"></i>
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <div class="flex gap-1">
                        @for($i = 0; $i < 5; $i++)
                            <i data-lucide="star" class="w-4 h-4 {{ $i < 4 ? 'fill-gold text-gold' : 'text-black/20 dark:text-white/20' }}"></i>
                        @endfor
                    </div>
                    <p class="text-sm text-ink/60 dark:text-orange-50/60">(128 reviews)</p>
                </div>
            </div>

            {{-- Price & Category --}}
            <div class="rounded-2xl glass p-6 shadow-soft space-y-4">
                <div class="flex items-baseline justify-between">
                    <span class="text-4xl font-extrabold text-gradient">KSh {{ number_format($dish->price, 0) }}</span>
                    <span class="text-sm font-semibold text-ink/60 dark:text-orange-50/60 uppercase">{{ ucfirst($dish->category) }}</span>
                </div>
                <p class="text-ink/70 dark:text-orange-50/70">{{ $dish->description }}</p>
            </div>

            {{-- Features & Badges --}}
            <div class="flex flex-wrap gap-2">
                @if($dish->is_bestseller)
                    <span class="inline-flex items-center gap-2 bg-gold/20 text-yellow-700 px-3 py-2 rounded-full text-sm font-bold">
                        ⭐ Bestseller
                    </span>
                @endif
                @if($dish->is_vegetarian)
                    <span class="inline-flex items-center gap-2 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-300 px-3 py-2 rounded-full text-sm font-bold">
                        🌱 Vegetarian
                    </span>
                @endif
                @if($dish->is_vegan)
                    <span class="inline-flex items-center gap-2 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-300 px-3 py-2 rounded-full text-sm font-bold">
                        🥗 Vegan
                    </span>
                @endif
                @if($dish->is_spicy)
                    <span class="inline-flex items-center gap-2 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-300 px-3 py-2 rounded-full text-sm font-bold">
                        🌶️ Spicy
                    </span>
                @endif
                @if($dish->is_gluten_free)
                    <span class="inline-flex items-center gap-2 bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-300 px-3 py-2 rounded-full text-sm font-bold">
                        🌾 Gluten Free
                    </span>
                @endif
                @if($dish->is_dairy_free)
                    <span class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 px-3 py-2 rounded-full text-sm font-bold">
                        🥛 Dairy Free
                    </span>
                @endif
            </div>

            {{-- Ingredients & Prep Time --}}
            @if($dish->ingredients || $dish->prep_time || $dish->serving_size)
            <div class="rounded-2xl glass p-6 shadow-soft space-y-4">
                @if($dish->prep_time)
                <div>
                    <p class="text-sm font-semibold text-ink/60 dark:text-orange-50/60 mb-2">⏱️ Preparation Time</p>
                    <p class="font-bold">{{ $dish->prep_time }} minutes</p>
                </div>
                @endif

                @if($dish->serving_size)
                <div>
                    <p class="text-sm font-semibold text-ink/60 dark:text-orange-50/60 mb-2">🍽️ Serving Size</p>
                    <p class="font-bold">{{ $dish->serving_size }}</p>
                </div>
                @endif

                @if($dish->ingredients)
                <div>
                    <p class="text-sm font-semibold text-ink/60 dark:text-orange-50/60 mb-2">🧂 Ingredients</p>
                    <p class="text-sm">{{ $dish->ingredients }}</p>
                </div>
                @endif
            </div>
            @endif

            {{-- Quantity & Add to Cart --}}
            <div class="flex gap-4">
                <div class="flex items-center gap-3 bg-black/5 dark:bg-white/10 rounded-xl px-4 py-3">
                    <button type="button" class="text-brand-600 hover:text-brand-700 transition" onclick="decreaseQty()">
                        <i data-lucide="minus" class="w-5 h-5"></i>
                    </button>
                    <input type="number" id="quantity" value="1" class="w-12 text-center font-bold bg-transparent focus:outline-none" min="1">
                    <button type="button" class="text-brand-600 hover:text-brand-700 transition" onclick="increaseQty()">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                    </button>
                </div>
                <button type="button" class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-brand-600 to-brand-700 text-white font-bold py-3 rounded-xl hover:shadow-glow transition"
                        onclick="addToCart({{ $dish->id }}, parseInt(document.getElementById('quantity').value))">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    Add to Cart
                </button>
            </div>

        </div>
    </div>

</div>

@push('scripts')
<script>
    function decreaseQty() {
        const qty = document.getElementById('quantity');
        if (qty.value > 1) qty.value--;
    }

    function increaseQty() {
        const qty = document.getElementById('quantity');
        qty.value++;
    }

    function changeMainImage(element) {
        const mainImage = document.getElementById('main-image');
        const src = element.querySelector('img').src;
        mainImage.src = src;
    }

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
                setTimeout(() => window.location.href = '{{ route('menu.index') }}', 1500);
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

    function toggleFavorite(dishId) {
        const btn = document.getElementById('favorite-btn');
        btn.classList.toggle('fill-brand-600');
        btn.classList.toggle('text-brand-600');
        // TODO: Implement wishlist functionality
        showToast('Added to favorites!');
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