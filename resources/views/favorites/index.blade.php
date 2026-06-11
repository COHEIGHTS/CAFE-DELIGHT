@extends('layouts.app')

@section('title', 'My Favorites — Cafe Delight')

@php $activeNav = 'Favorites'; @endphp

@section('content')
<div class="space-y-8" data-aos="fade-up">

    {{-- ===== Hero ===== --}}
    <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-red-400 via-red-500 to-brand-700 p-8 text-white sm:p-12 shadow-premium">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-4xl font-extrabold flex items-center gap-3">
                    ❤️ My Favorites
                </h1>
                <p class="mt-2 text-red-50/90">
                    {{ $favoriteCount }} {{ Str::plural('dish', $favoriteCount) }} saved to your favorites.
                </p>
            </div>
            <a href="{{ route('menu.index') }}"
               class="hidden sm:inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white font-bold px-5 py-3 rounded-xl transition backdrop-blur-sm">
                <i data-lucide="utensils" class="w-5 h-5"></i>
                Browse Menu
            </a>
        </div>
    </div>

    {{-- ===== Favorites Grid ===== --}}
    @if($favorites->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($favorites as $fav)
            @php $dish = $fav->dish; @endphp
            <div class="group rounded-2xl glass overflow-hidden shadow-soft hover:shadow-premium transition"
                 id="fav-card-{{ $dish->id }}"
                 data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 50 }}">

                {{-- Image --}}
                <div class="relative h-48 bg-gradient-to-br from-brand-300 to-brand-500 overflow-hidden">
                    @if($dish->primary_image)
                        <img src="{{ asset('storage/' . $dish->primary_image) }}" alt="{{ $dish->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="image" class="w-12 h-12 text-white/50"></i>
                        </div>
                    @endif

                    {{-- Badges --}}
                    <div class="absolute top-3 left-3 flex flex-wrap gap-1">
                        @if($dish->is_bestseller)
                        <span class="inline-flex items-center gap-1 rounded-full bg-gold/90 text-yellow-900 px-2.5 py-1 text-xs font-bold backdrop-blur-sm">
                            ⭐ Best
                        </span>
                        @endif
                        @if($dish->is_vegetarian)
                        <span class="inline-flex items-center gap-1 rounded-full bg-green-500/90 text-white px-2 py-1 text-xs font-bold backdrop-blur-sm">🌱</span>
                        @endif
                        @if($dish->is_spicy)
                        <span class="inline-flex items-center gap-1 rounded-full bg-red-500/90 text-white px-2 py-1 text-xs font-bold backdrop-blur-sm">🌶️</span>
                        @endif
                    </div>

                    {{-- Remove favorite button --}}
                    <button onclick="removeFavorite({{ $dish->id }}, this)"
                            class="absolute top-3 right-3 grid h-9 w-9 place-items-center rounded-full bg-white/90 hover:bg-red-50 shadow-soft transition"
                            title="Remove from favorites">
                        <i data-lucide="heart"
                           data-dish-fav="{{ $dish->id }}"
                           class="w-5 h-5 fill-red-500 text-red-500 transition-colors"></i>
                    </button>

                    {{-- Hover overlay --}}
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2 flex-col">
                        <button onclick="addToCart({{ $dish->id }}, 1)"
                                class="inline-flex items-center gap-2 bg-white text-brand-600 font-bold px-4 py-2 rounded-lg hover:scale-105 transition">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Add to Cart
                        </button>
                        <a href="{{ route('menu.show', $dish->id) }}"
                           class="inline-flex items-center gap-2 bg-brand-600 text-white font-bold px-4 py-2 rounded-lg hover:scale-105 transition">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            View Details
                        </a>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-1">{{ $dish->name }}</h3>
                    <p class="text-sm text-ink/60 dark:text-orange-50/60 mb-3 line-clamp-2">{{ $dish->description }}</p>

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

                    <div class="pt-3 border-t border-black/5 dark:border-white/5 flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-extrabold text-gradient">KSh {{ number_format($dish->price, 0) }}</p>
                            @if($dish->prep_time)
                                <p class="text-xs text-ink/50 dark:text-orange-50/50 mt-1">⏱️ {{ $dish->prep_time }} mins</p>
                            @endif
                        </div>
                        <button onclick="addToCart({{ $dish->id }}, 1)"
                                class="inline-flex items-center gap-1.5 bg-gradient-to-r from-brand-500 to-brand-600 text-white font-bold px-3 py-2 rounded-lg text-sm hover:shadow-glow transition">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                            Add
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    @else
        {{-- Empty State --}}
        <div class="text-center py-20 rounded-3xl glass shadow-soft" data-aos="fade-up">
            <div class="w-20 h-20 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center mx-auto mb-6">
                <i data-lucide="heart" class="w-10 h-10 text-red-400"></i>
            </div>
            <h2 class="text-2xl font-bold mb-2">No favorites yet</h2>
            <p class="text-ink/50 dark:text-orange-50/50 mb-6 max-w-sm mx-auto">
                Browse the menu and tap the ❤️ on any dish to save it here.
            </p>
            <a href="{{ route('menu.index') }}"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white font-bold px-6 py-3 rounded-xl hover:shadow-glow transition">
                <i data-lucide="utensils" class="w-5 h-5"></i>
                Browse Menu
            </a>
        </div>
    @endif

</div>

@push('scripts')
<script>
    /* ── Add to cart ────────────────────────────────────── */
    function addToCart(dishId, quantity = 1) {
        fetch(`/cart/add/${dishId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept':       'application/json',
            },
            body: JSON.stringify({ quantity })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                updateCartCount(data.cartCount);
                showFavToast(data.message, false);
            }
        })
        .catch(() => {});
    }

    /* ── Remove favorite (with card slide-out) ───────────── */
    function removeFavorite(dishId, btn) {
        fetch(`/favorites/toggle/${dishId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept':       'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;

            updateFavoriteCount(data.favoriteCount);
            showFavToast(data.message, false);

            // Animate card out
            const card = document.getElementById(`fav-card-${dishId}`);
            if (card) {
                card.style.transition = 'all 0.4s ease';
                card.style.opacity    = '0';
                card.style.transform  = 'scale(0.9)';
                setTimeout(() => {
                    card.remove();
                    // Show empty state if no cards left
                    if (!document.querySelector('[id^="fav-card-"]')) {
                        location.reload();
                    }
                }, 400);
            }
        })
        .catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
        updateCartCount();
        updateFavoriteCount();
    });
</script>
@endpush
@endsection