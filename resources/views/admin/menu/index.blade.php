@extends('layouts.admin-layout')

@section('title', 'Menu Items — Admin · Cafe Delight')

@php $activeNav = 'Menu Items'; @endphp

@section('content')
<div data-aos="fade-up">
    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl font-bold">Menu Items</h1>
            <p class="mt-2 text-ink/60 dark:text-orange-50/60">Manage all dishes on your menu</p>
        </div>
        <a href="{{ route('admin.menu.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-600 to-brand-700 text-white font-bold px-6 py-3 rounded-xl hover:shadow-glow transition whitespace-nowrap">
            <i data-lucide="plus-circle" class="w-5 h-5"></i>
            Add New Dish
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 rounded-2xl glass p-4 border-l-4 border-green-500 shadow-soft" data-aos="fade-down">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                <p class="font-semibold text-green-600">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Menu Grid --}}
    @if($dishes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($dishes as $dish)
            <div class="rounded-2xl glass p-0 overflow-hidden shadow-soft hover:shadow-premium transition group" data-aos="fade-up" data-aos-delay="100">
                {{-- Image --}}
                <div class="relative h-48 bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center overflow-hidden">
                    @if($dish->primary_image)
                        <img src="{{ asset('storage/' . $dish->primary_image) }}" alt="{{ $dish->name }}" class="w-full h-full object-cover">
                    @else
                        <i data-lucide="image" class="w-12 h-12 text-white/50"></i>
                    @endif
                    
                    {{-- Bestseller Badge --}}
                    @if($dish->is_bestseller)
                    <div class="absolute top-3 right-3 z-10">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gold/30 text-gold px-2.5 py-1 text-xs font-bold backdrop-blur-sm">
                            ⭐ Bestseller
                        </span>
                    </div>
                    @endif

                    {{-- Edit/Delete Buttons --}}
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-3 opacity-0 group-hover:opacity-100 transition z-10">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.menu.edit', $dish->id) }}" class="flex-1 flex items-center justify-center gap-1 bg-brand-600 text-white px-3 py-2 rounded-lg text-xs font-bold hover:bg-brand-700 transition">
                                <i data-lucide="edit" class="w-4 h-4"></i> Edit
                            </a>
                            <form action="{{ route('admin.menu.destroy', $dish->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center justify-center gap-1 bg-red-600 text-white px-3 py-2 rounded-lg text-xs font-bold hover:bg-red-700 transition" onclick="return confirm('Are you sure?')">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-1">{{ $dish->name }}</h3>
                    <p class="text-sm text-ink/60 dark:text-orange-50/60 mb-3 line-clamp-2">{{ $dish->description }}</p>
                    
                    {{-- Features --}}
                    <div class="flex flex-wrap gap-1 mb-3">
                        @if($dish->is_vegetarian)
                            <span class="text-xs bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-300 px-2 py-1 rounded-full font-semibold">🌱 Vegetarian</span>
                        @endif
                        @if($dish->is_vegan)
                            <span class="text-xs bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-300 px-2 py-1 rounded-full font-semibold">🥗 Vegan</span>
                        @endif
                        @if($dish->is_spicy)
                            <span class="text-xs bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-300 px-2 py-1 rounded-full font-semibold">🌶️ Spicy</span>
                        @endif
                        @if($dish->is_gluten_free)
                            <span class="text-xs bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-300 px-2 py-1 rounded-full font-semibold">🌾 Gluten Free</span>
                        @endif
                        @if($dish->is_dairy_free)
                            <span class="text-xs bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 px-2 py-1 rounded-full font-semibold">🥛 Dairy Free</span>
                        @endif
                    </div>

                    {{-- Category & Price --}}
                    <div class="pt-3 border-t border-black/5 dark:border-white/5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-ink/50 dark:text-orange-50/50 uppercase">{{ ucfirst($dish->category) }}</span>
                            @if($dish->prep_time)
                                <div class="flex items-center gap-1 text-xs text-ink/50 dark:text-orange-50/50">
                                    <i data-lucide="clock" class="w-3 h-3"></i> {{ $dish->prep_time }} mins
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-extrabold text-gradient">KSh {{ number_format($dish->price, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-12 rounded-2xl glass p-8 shadow-soft" data-aos="fade-up">
            <i data-lucide="inbox" class="w-16 h-16 text-black/20 dark:text-white/20 mx-auto mb-4"></i>
            <p class="text-ink/60 dark:text-orange-50/60 mb-4 font-semibold text-lg">No menu items yet</p>
            <p class="text-ink/50 dark:text-orange-50/50 mb-6">Start by adding your first delicious dish!</p>
            <a href="{{ route('admin.menu.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-600 to-brand-700 text-white font-bold px-6 py-3 rounded-xl hover:shadow-glow transition">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                Add Your First Dish
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