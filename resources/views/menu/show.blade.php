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
            <div class="rounded-2xl overflow-hidden shadow-premium h-96 bg-gradient-to-br from-brand-300 to-brand-500 cursor-pointer group"
                 onclick="changeMainImage(this)">
                <img id="main-image"
                     src="{{ $dish->primary_image ? asset('storage/' . $dish->primary_image) : '' }}"
                     alt="{{ $dish->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @if(!$dish->primary_image)
                <div class="w-full h-full flex items-center justify-center">
                    <i data-lucide="image" class="w-20 h-20 text-white/50"></i>
                </div>
                @endif
            </div>

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

            {{-- Name & Favorite --}}
            <div>
                <div class="flex items-start justify-between mb-2">
                    <h1 class="font-display text-3xl font-bold">{{ $dish->name }}</h1>
                    <button onclick="toggleFavorite({{ $dish->id }}, this)"
                            class="grid h-10 w-10 place-items-center rounded-lg bg-brand-500/15 hover:bg-red-500/15 transition"
                            title="{{ $isFavorited ? 'Remove from favorites' : 'Add to favorites' }}">
                        <i data-lucide="heart"
                           data-dish-fav="{{ $dish->id }}"
                           class="w-6 h-6 transition-colors {{ $isFavorited ? 'fill-red-500 text-red-500' : 'text-brand-600' }}"></i>
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

            {{-- Badges --}}
            <div class="flex flex-wrap gap-2">
                @if($dish->is_bestseller)
                    <span class="inline-flex items-center gap-2 bg-gold/20 text-yellow-700 px-3 py-2 rounded-full text-sm font-bold">⭐ Bestseller</span>
                @endif
                @if($dish->is_vegetarian)
                    <span class="inline-flex items-center gap-2 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-300 px-3 py-2 rounded-full text-sm font-bold">🌱 Vegetarian</span>
                @endif
                @if($dish->is_vegan)
                    <span class="inline-flex items-center gap-2 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-300 px-3 py-2 rounded-full text-sm font-bold">🥗 Vegan</span>
                @endif
                @if($dish->is_spicy)
                    <span class="inline-flex items-center gap-2 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-300 px-3 py-2 rounded-full text-sm font-bold">🌶️ Spicy</span>
                @endif
                @if($dish->is_gluten_free)
                    <span class="inline-flex items-center gap-2 bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-300 px-3 py-2 rounded-full text-sm font-bold">🌾 Gluten Free</span>
                @endif
                @if($dish->is_dairy_free)
                    <span class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 px-3 py-2 rounded-full text-sm font-bold">🥛 Dairy Free</span>
                @endif
            </div>

            {{-- Prep / Serving / Ingredients --}}
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
                    <input type="number" id="quantity" value="1"
                           class="w-12 text-center font-bold bg-transparent focus:outline-none" min="1">
                    <button type="button" class="text-brand-600 hover:text-brand-700 transition" onclick="increaseQty()">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                    </button>
                </div>
                <button type="button"
                        id="add-to-cart-btn"
                        class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-brand-600 to-brand-700 text-white font-bold py-3 rounded-xl hover:shadow-glow transition disabled:opacity-60 disabled:cursor-not-allowed"
                        onclick="addToCart({{ $dish->id }}, parseInt(document.getElementById('quantity').value), this)">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    Add to Cart
                </button>
            </div>

        </div>
    </div>

</div>

{{-- ===== Cart Success Popup ===== --}}
<div id="cart-popup" class="fixed inset-0 z-[999] hidden">
    <div id="popup-backdrop"
         class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>

    <div id="popup-card"
         class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
                w-full max-w-xs sm:max-w-sm bg-white dark:bg-gray-900
                rounded-3xl shadow-2xl p-8 flex flex-col items-center gap-5
                scale-75 opacity-0 transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)]">

        <div class="relative flex items-center justify-center w-24 h-24">
            <svg id="popup-ring" class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 96 96">
                <circle cx="48" cy="48" r="44" fill="none" stroke="#e5e7eb" stroke-width="4"/>
                <circle id="popup-ring-fill" cx="48" cy="48" r="44"
                        fill="none" stroke-width="4" stroke-linecap="round"
                        stroke-dasharray="276.46" stroke-dashoffset="276.46"
                        style="transition:stroke-dashoffset 2s linear; stroke:#22c55e;"/>
            </svg>
            <div id="popup-success-icon"
                 class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-500/20 flex items-center justify-center scale-0 transition-transform duration-300 delay-200">
                <svg class="w-8 h-8 text-green-500" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path class="tick-path" d="M5 13l4 4L19 7"
                          stroke-dasharray="30" stroke-dashoffset="30"
                          style="transition:stroke-dashoffset 0.4s ease 0.4s;"/>
                </svg>
            </div>
            <div id="popup-error-icon"
                 class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center hidden scale-0 transition-transform duration-300 delay-200">
                <svg class="w-8 h-8 text-red-500" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
        </div>

        <div class="text-center space-y-1">
            <p id="popup-title"   class="text-xl font-extrabold text-gray-900 dark:text-white"></p>
            <p id="popup-message" class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed"></p>
        </div>

        <div class="w-full flex flex-col gap-2">
            <button id="popup-view-cart-btn" onclick="closePopup(true)"
                    class="w-full py-3 rounded-xl bg-gradient-to-r from-brand-600 to-brand-700 text-white font-bold text-sm hover:shadow-glow transition">
                View Cart
            </button>
            <button onclick="closePopup(false)"
                    class="w-full py-3 rounded-xl bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-gray-300 font-semibold text-sm hover:bg-gray-200 dark:hover:bg-white/20 transition">
                Continue Shopping
            </button>
        </div>
    </div>
</div>

@push('scripts')
<style>
    #cart-popup.popup-open .tick-path { stroke-dashoffset: 0 !important; }
    #cart-popup.popup-open #popup-success-icon,
    #cart-popup.popup-open #popup-error-icon:not(.hidden) { transform: scale(1) !important; }
</style>
<script>
    function decreaseQty() {
        const q = document.getElementById('quantity');
        if (parseInt(q.value) > 1) q.value--;
    }
    function increaseQty() {
        document.getElementById('quantity').value++;
    }
    function changeMainImage(el) {
        const img = el.querySelector('img');
        if (img) document.getElementById('main-image').src = img.src;
    }

    /* ── Cart popup ─────────────────────────────────────── */
    let _redirectOnClose = false;
    let _autoTimer = null;

    function showPopup({ title, message, type = 'success', autoRedirect = false }) {
        clearTimeout(_autoTimer);
        _redirectOnClose = false;

        const popup    = document.getElementById('cart-popup');
        const backdrop = document.getElementById('popup-backdrop');
        const card     = document.getElementById('popup-card');
        const ring     = document.getElementById('popup-ring-fill');
        const succIcon = document.getElementById('popup-success-icon');
        const errIcon  = document.getElementById('popup-error-icon');
        const viewBtn  = document.getElementById('popup-view-cart-btn');

        document.getElementById('popup-title').textContent   = title;
        document.getElementById('popup-message').textContent = message;

        const isSuccess = type === 'success';
        ring.style.stroke = isSuccess ? '#22c55e' : '#ef4444';
        succIcon.classList.toggle('hidden', !isSuccess);
        errIcon.classList.toggle('hidden', isSuccess);
        viewBtn.style.display = isSuccess ? '' : 'none';

        ring.style.transition       = 'none';
        ring.style.strokeDashoffset = '276.46';
        succIcon.style.transform    = 'scale(0)';
        errIcon.style.transform     = 'scale(0)';
        card.classList.remove('scale-100','opacity-100');
        card.classList.add('scale-75','opacity-0');
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        popup.classList.remove('popup-open');
        popup.classList.remove('hidden');

        requestAnimationFrame(() => requestAnimationFrame(() => {
            backdrop.classList.replace('opacity-0','opacity-100');
            card.classList.replace('scale-75','scale-100');
            card.classList.replace('opacity-0','opacity-100');
            setTimeout(() => {
                ring.style.transition       = 'stroke-dashoffset 2.2s linear';
                ring.style.strokeDashoffset = '0';
            }, 300);
            popup.classList.add('popup-open');
        }));

        if (autoRedirect && isSuccess) {
            _redirectOnClose = true;
            _autoTimer = setTimeout(() => closePopup(true), 2500);
        }
    }

    function closePopup(goToCart = false) {
        clearTimeout(_autoTimer);
        const popup    = document.getElementById('cart-popup');
        const backdrop = document.getElementById('popup-backdrop');
        const card     = document.getElementById('popup-card');

        backdrop.classList.replace('opacity-100','opacity-0');
        card.classList.replace('scale-100','scale-75');
        card.classList.replace('opacity-100','opacity-0');
        popup.classList.remove('popup-open');

        setTimeout(() => {
            popup.classList.add('hidden');
            if (goToCart || _redirectOnClose) window.location.href = '{{ route('menu.index') }}';
        }, 300);
    }

    document.getElementById('popup-backdrop').addEventListener('click', () => closePopup(false));

    /* ── Add to cart ────────────────────────────────────── */
    function addToCart(dishId, quantity, btn) {
        if (!btn) btn = document.getElementById('add-to-cart-btn');
        btn.disabled  = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i> Adding…';
        if (window.lucide) lucide.createIcons();

        fetch(`/cart/add/${dishId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept':       'application/json',
            },
            body: JSON.stringify({ quantity })
        })
        .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
        .then(data => {
            if (data.success) {
                updateCartCount(data.cartCount);
                btn.innerHTML = '<i data-lucide="check" class="w-5 h-5"></i> Added!';
                if (window.lucide) lucide.createIcons();
                showPopup({ title:'🎉 Added to Cart!', message: data.message, type:'success', autoRedirect:true });
            } else {
                showPopup({ title:'Could not add item', message: data.message || 'Please try again.', type:'error' });
                resetBtn(btn);
            }
        })
        .catch(() => {
            showPopup({ title:'Request Failed', message:'Something went wrong. Please try again.', type:'error' });
            resetBtn(btn);
        });
    }

    function resetBtn(btn) {
        btn.disabled  = false;
        btn.innerHTML = '<i data-lucide="shopping-bag" class="w-5 h-5"></i> Add to Cart';
        if (window.lucide) lucide.createIcons();
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
        updateCartCount();
        updateFavoriteCount();
    });
</script>
@endpush
@endsection