@extends('layouts.app')

@section('title', 'My Favourites — Cafe Delight')

@php $activeNav = 'Favourites'; @endphp

@section('content')
<div class="max-w-5xl mx-auto" data-aos="fade-up">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-display text-3xl font-bold">My Favourites</h1>
            <p class="text-ink/60 dark:text-orange-50/60 mt-1">
                {{ $favourites->count() }} {{ Str::plural('dish', $favourites->count()) }} saved
            </p>
        </div>
        <a href="{{ route('menu.index') }}"
           class="inline-flex items-center gap-2 text-brand-600 hover:text-brand-700 font-semibold transition">
            <i data-lucide="utensils" class="w-5 h-5"></i>
            Browse Menu
        </a>
    </div>

    {{-- Empty State --}}
    @if($favourites->isEmpty())
    <div class="rounded-2xl glass shadow-soft p-16 text-center" data-aos="fade-up">
        <div class="w-20 h-20 bg-brand-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
            <i data-lucide="heart" class="w-10 h-10 text-brand-400"></i>
        </div>
        <h2 class="font-display text-2xl font-bold mb-2">No favourites yet</h2>
        <p class="text-ink/60 dark:text-orange-50/60 mb-8 max-w-sm mx-auto">
            Tap the heart icon on any dish to save it here for quick ordering later.
        </p>
        <a href="{{ route('menu.index') }}"
           class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-600 to-brand-700 text-white font-bold px-8 py-3 rounded-xl hover:shadow-glow transition">
            <i data-lucide="utensils" class="w-5 h-5"></i>
            Explore the Menu
        </a>
    </div>

    {{-- Favourites Grid --}}
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($favourites as $favourite)
        @php $dish = $favourite->dish; @endphp

        @if($dish)
        <div class="rounded-2xl glass shadow-soft overflow-hidden group flex flex-col"
             data-aos="fade-up"
             id="favourite-card-{{ $dish->id }}">

            {{-- Dish Image --}}
            <a href="{{ route('menu.show', $dish->id) }}" class="block h-48 overflow-hidden bg-gradient-to-br from-brand-300 to-brand-500">
                @if($dish->primary_image)
                <img src="{{ asset('storage/' . $dish->primary_image) }}"
                     alt="{{ $dish->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <i data-lucide="image" class="w-12 h-12 text-white/50"></i>
                </div>
                @endif
            </a>

            {{-- Dish Info --}}
            <div class="p-5 flex flex-col flex-1">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <a href="{{ route('menu.show', $dish->id) }}"
                       class="font-display font-bold text-lg hover:text-brand-600 transition leading-tight">
                        {{ $dish->name }}
                    </a>

                    {{-- Remove from Favourites --}}
                    <button
                        onclick="removeFavourite({{ $dish->id }})"
                        title="Remove from favourites"
                        class="shrink-0 grid h-8 w-8 place-items-center rounded-lg text-brand-600 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500 transition"
                    >
                        <i data-lucide="heart" class="w-5 h-5 fill-brand-600"></i>
                    </button>
                </div>

                @if($dish->description)
                <p class="text-sm text-ink/60 dark:text-orange-50/60 line-clamp-2 mb-3">
                    {{ $dish->description }}
                </p>
                @endif

                {{-- Badges --}}
                <div class="flex flex-wrap gap-1 mb-4">
                    @if($dish->is_bestseller)
                        <span class="text-xs font-bold bg-gold/20 text-yellow-700 px-2 py-1 rounded-full">⭐ Bestseller</span>
                    @endif
                    @if($dish->is_vegetarian)
                        <span class="text-xs font-bold bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-300 px-2 py-1 rounded-full">🌱 Veg</span>
                    @endif
                    @if($dish->is_spicy)
                        <span class="text-xs font-bold bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-300 px-2 py-1 rounded-full">🌶️ Spicy</span>
                    @endif
                </div>

                {{-- Price & Add to Cart --}}
                <div class="mt-auto flex items-center justify-between gap-3">
                    <span class="text-2xl font-extrabold text-gradient">
                        KSh {{ number_format($dish->price, 0) }}
                    </span>
                    <button
                        onclick="addToCartFromFavourites({{ $dish->id }})"
                        data-cart-url="{{ route('cart.add', $dish->id) }}"
                        class="flex items-center gap-2 bg-gradient-to-r from-brand-600 to-brand-700 text-white text-sm font-bold px-4 py-2 rounded-xl hover:shadow-glow transition">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endif

</div>

@push('scripts')
<script>
    const CSRF_TOKEN   = document.querySelector('meta[name="csrf-token"]').content;
    const CART_COUNT_URL = "{{ route('cart.count') }}";

    // ── Remove from favourites and collapse the card ──────────────────────────
    function removeFavourite(dishId) {
        const url  = `/favourites/toggle/${dishId}`;
        const card = document.getElementById(`favourite-card-${dishId}`);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && !data.favourited) {
                // Animate out the card
                if (card) {
                    card.style.transition = 'opacity 0.3s, transform 0.3s';
                    card.style.opacity    = '0';
                    card.style.transform  = 'scale(0.95)';
                    setTimeout(() => {
                        card.remove();
                        updateFavouriteCount();
                    }, 300);
                }
                showToast(data.message, 'success');
            }
        })
        .catch(() => showToast('Could not remove favourite. Try again.', 'error'));
    }

    // ── Add to cart directly from favourites page ─────────────────────────────
    function addToCartFromFavourites(dishId) {
        const btn = document.querySelector(`#favourite-card-${dishId} [onclick="addToCartFromFavourites(${dishId})"]`);
        const url = btn ? btn.dataset.cartUrl : `/cart/add/${dishId}`;

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i>';
            if (window.lucide) lucide.createIcons();
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ quantity: 1 })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message ?? 'Added to cart!', 'success');
                updateCartCount();
            } else {
                showToast(data.message ?? 'Could not add to cart.', 'error');
            }
        })
        .catch(() => showToast('Something went wrong. Try again.', 'error'))
        .finally(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="shopping-bag" class="w-4 h-4"></i> Add to Cart';
                if (window.lucide) lucide.createIcons();
            }
        });
    }

    // ── Update page subtitle count ────────────────────────────────────────────
    function updateFavouriteCount() {
        const remaining = document.querySelectorAll('[id^="favourite-card-"]').length;
        const subtitle  = document.querySelector('p.text-ink\\/60');
        if (subtitle) {
            subtitle.textContent = `${remaining} ${remaining === 1 ? 'dish' : 'dishes'} saved`;
        }
        // Show empty state if none left
        if (remaining === 0) {
            location.reload();
        }
    }

    // ── Update navbar cart badge ──────────────────────────────────────────────
    function updateCartCount() {
        fetch(CART_COUNT_URL, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                const badge = document.getElementById('cart-count');
                if (badge) {
                    badge.textContent = data.count;
                    badge.style.display = data.count > 0 ? 'flex' : 'none';
                }
            })
            .catch(() => {});
    }

    // ── Toast ─────────────────────────────────────────────────────────────────
    function showToast(message, type = 'success') {
        const colours = { success: 'bg-green-500', error: 'bg-red-500', info: 'bg-brand-600' };
        const toast   = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 ${colours[type] ?? colours.success} text-white px-6 py-3 rounded-lg font-semibold shadow-lg z-50`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
    }

    // ── Boot ──────────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
        updateCartCount();
    });
</script>
@endpush
@endsection