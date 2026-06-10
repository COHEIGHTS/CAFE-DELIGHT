@extends('layouts.admin-layout')

@section('title', 'Edit Dish — Admin · Cafe Delight')

@php $activeNav = 'Menu Items'; @endphp

@section('content')
<div data-aos="fade-up">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.menu.index') }}"
               class="mb-2 inline-flex items-center gap-1.5 text-xs font-bold text-ink/45 hover:text-brand-600 dark:text-orange-50/45 transition">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Menu Items
            </a>
            <h1 class="font-display text-3xl font-bold">Edit Dish</h1>
            <p class="mt-1 text-ink/60 dark:text-orange-50/60">Update the details for <span class="font-semibold text-brand-600">{{ $dish->name }}</span></p>
        </div>

        {{-- Delete button in header --}}
        <form action="{{ route('admin.menu.destroy', $dish->id) }}" method="POST"
              onsubmit="return confirm('Permanently delete {{ addslashes($dish->name) }}? This cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl border border-red-500/30 bg-red-500/10 px-5 py-2.5 text-sm font-bold text-red-500 transition hover:bg-red-500/20">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Delete Dish
            </button>
        </form>
    </div>

    {{-- Errors --}}
    @if($errors->any())
    <div class="mb-6 rounded-2xl glass border-l-4 border-red-500 p-4 shadow-soft" data-aos="fade-down">
        <div class="flex items-start gap-3">
            <i data-lucide="alert-circle" class="mt-0.5 w-5 h-5 shrink-0 text-red-500"></i>
            <ul class="space-y-0.5 text-sm font-semibold text-red-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.menu.update', $dish->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- ── LEFT / MAIN COLUMN ─────────────────────────────── --}}
            <div class="space-y-6 lg:col-span-2">

                {{-- Basic info --}}
                <div class="rounded-2xl glass p-6 shadow-soft">
                    <p class="mb-5 text-xs font-extrabold uppercase tracking-widest text-ink/40 dark:text-orange-50/40">Basic Information</p>

                    <div class="space-y-4">
                        {{-- Name --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold">Dish Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $dish->name) }}"
                                   class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-2.5 text-sm font-semibold focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-white/10 dark:bg-white/5"
                                   placeholder="e.g. Grilled Chicken Tikka" required>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold">Description <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="3"
                                      class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-2.5 text-sm font-semibold focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-white/10 dark:bg-white/5"
                                      placeholder="A short, enticing description..." required>{{ old('description', $dish->description) }}</textarea>
                        </div>

                        {{-- Category & Price --}}
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-bold">Category <span class="text-red-500">*</span></label>
                                <select name="category"
                                        class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-2.5 text-sm font-semibold focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-white/10 dark:bg-white/5"
                                        required>
                                    @foreach(['starters','mains','sides','desserts','drinks','specials'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $dish->category) === $cat ? 'selected' : '' }}>
                                        {{ ucfirst($cat) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-bold">Price (KSh) <span class="text-red-500">*</span></label>
                                <input type="number" name="price" value="{{ old('price', $dish->price) }}"
                                       step="0.01" min="0"
                                       class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-2.5 text-sm font-semibold focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-white/10 dark:bg-white/5"
                                       placeholder="0.00" required>
                            </div>
                        </div>

                        {{-- Prep time & Serving --}}
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-bold">Prep Time (mins)</label>
                                <input type="number" name="prep_time" value="{{ old('prep_time', $dish->prep_time) }}"
                                       min="1"
                                       class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-2.5 text-sm font-semibold focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-white/10 dark:bg-white/5"
                                       placeholder="e.g. 20">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-bold">Serving Size</label>
                                <input type="text" name="serving_size" value="{{ old('serving_size', $dish->serving_size) }}"
                                       class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-2.5 text-sm font-semibold focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-white/10 dark:bg-white/5"
                                       placeholder="e.g. 1 plate / 2 persons">
                            </div>
                        </div>

                        {{-- Ingredients --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold">Ingredients</label>
                            <textarea name="ingredients" rows="2"
                                      class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-2.5 text-sm font-semibold focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-white/10 dark:bg-white/5"
                                      placeholder="Comma-separated list of ingredients...">{{ old('ingredients', $dish->ingredients) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Dietary flags --}}
                <div class="rounded-2xl glass p-6 shadow-soft">
                    <p class="mb-5 text-xs font-extrabold uppercase tracking-widest text-ink/40 dark:text-orange-50/40">Dietary & Labels</p>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach([
                            ['field' => 'is_vegetarian', 'emoji' => '🌱', 'label' => 'Vegetarian'],
                            ['field' => 'is_vegan',      'emoji' => '🥗', 'label' => 'Vegan'],
                            ['field' => 'is_spicy',      'emoji' => '🌶️', 'label' => 'Spicy'],
                            ['field' => 'is_gluten_free','emoji' => '🌾', 'label' => 'Gluten Free'],
                            ['field' => 'is_dairy_free', 'emoji' => '🥛', 'label' => 'Dairy Free'],
                            ['field' => 'is_bestseller', 'emoji' => '⭐', 'label' => 'Bestseller'],
                        ] as $flag)
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-black/8 bg-white/40 px-4 py-3 transition hover:border-brand-500/30 hover:bg-brand-500/5 dark:border-white/8 dark:bg-white/5 has-[:checked]:border-brand-500/40 has-[:checked]:bg-brand-500/10">
                            <input type="checkbox" name="{{ $flag['field'] }}" value="1"
                                   {{ old($flag['field'], $dish->{$flag['field']}) ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-black/20 text-brand-600 focus:ring-brand-500">
                            <span class="text-sm font-semibold">{{ $flag['emoji'] }} {{ $flag['label'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- ── RIGHT COLUMN — Images ────────────────────────────── --}}
            <div class="space-y-6">

                {{-- Primary image --}}
                <div class="rounded-2xl glass p-6 shadow-soft">
                    <p class="mb-4 text-xs font-extrabold uppercase tracking-widest text-ink/40 dark:text-orange-50/40">Primary Image</p>

                    {{-- Current image preview --}}
                    <div class="mb-4 overflow-hidden rounded-xl bg-gradient-to-br from-brand-100 to-brand-200 dark:from-brand-900/30 dark:to-brand-800/30"
                         style="aspect-ratio:4/3">
                        @if($dish->primary_image)
                            <img id="primary-preview"
                                 src="{{ asset('storage/' . $dish->primary_image) }}"
                                 alt="Primary image"
                                 class="h-full w-full object-cover">
                        @else
                            <div id="primary-preview" class="flex h-full w-full items-center justify-center">
                                <i data-lucide="image" class="h-10 w-10 text-brand-400"></i>
                            </div>
                        @endif
                    </div>

                    <label class="block">
                        <span class="mb-1.5 block text-sm font-bold">Replace Image</span>
                        <input type="file" name="primary_image" accept="image/*"
                               onchange="previewImage(this,'primary-preview')"
                               class="block w-full text-sm text-ink/60 file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-brand-500/10 file:px-3 file:py-2 file:text-xs file:font-bold file:text-brand-600 hover:file:bg-brand-500/20 dark:text-orange-50/60">
                        <p class="mt-1 text-xs text-ink/40 dark:text-orange-50/40">Leave blank to keep current image</p>
                    </label>
                </div>

                {{-- Secondary image --}}
                <div class="rounded-2xl glass p-6 shadow-soft">
                    <p class="mb-4 text-xs font-extrabold uppercase tracking-widest text-ink/40 dark:text-orange-50/40">Secondary Image</p>
                    <div class="mb-4 overflow-hidden rounded-xl bg-gradient-to-br from-brand-100 to-brand-200 dark:from-brand-900/30 dark:to-brand-800/30"
                         style="aspect-ratio:4/3">
                        @if($dish->secondary_image)
                            <img id="secondary-preview"
                                 src="{{ asset('storage/' . $dish->secondary_image) }}"
                                 alt="Secondary image"
                                 class="h-full w-full object-cover">
                        @else
                            <div id="secondary-preview" class="flex h-full w-full items-center justify-center">
                                <i data-lucide="image" class="h-10 w-10 text-brand-300"></i>
                            </div>
                        @endif
                    </div>
                    <input type="file" name="secondary_image" accept="image/*"
                           onchange="previewImage(this,'secondary-preview')"
                           class="block w-full text-sm text-ink/60 file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-brand-500/10 file:px-3 file:py-2 file:text-xs file:font-bold file:text-brand-600 hover:file:bg-brand-500/20 dark:text-orange-50/60">
                    <p class="mt-1 text-xs text-ink/40 dark:text-orange-50/40">Optional · leave blank to keep current</p>
                </div>

                {{-- Tertiary image --}}
                <div class="rounded-2xl glass p-6 shadow-soft">
                    <p class="mb-4 text-xs font-extrabold uppercase tracking-widest text-ink/40 dark:text-orange-50/40">Tertiary Image</p>
                    <div class="mb-4 overflow-hidden rounded-xl bg-gradient-to-br from-brand-100 to-brand-200 dark:from-brand-900/30 dark:to-brand-800/30"
                         style="aspect-ratio:4/3">
                        @if($dish->tertiary_image)
                            <img id="tertiary-preview"
                                 src="{{ asset('storage/' . $dish->tertiary_image) }}"
                                 alt="Tertiary image"
                                 class="h-full w-full object-cover">
                        @else
                            <div id="tertiary-preview" class="flex h-full w-full items-center justify-center">
                                <i data-lucide="image" class="h-10 w-10 text-brand-300"></i>
                            </div>
                        @endif
                    </div>
                    <input type="file" name="tertiary_image" accept="image/*"
                           onchange="previewImage(this,'tertiary-preview')"
                           class="block w-full text-sm text-ink/60 file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-brand-500/10 file:px-3 file:py-2 file:text-xs file:font-bold file:text-brand-600 hover:file:bg-brand-500/20 dark:text-orange-50/60">
                    <p class="mt-1 text-xs text-ink/40 dark:text-orange-50/40">Optional · leave blank to keep current</p>
                </div>

                {{-- Save button --}}
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-600 to-brand-700 px-6 py-3.5 text-sm font-extrabold text-white shadow-soft transition hover:shadow-glow hover:scale-[1.02]">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Changes
                </button>

                <a href="{{ route('admin.menu.index') }}"
                   class="block w-full rounded-xl border border-black/10 dark:border-white/10 px-6 py-3 text-center text-sm font-bold text-ink/60 transition hover:bg-black/5 dark:text-orange-50/60 dark:hover:bg-white/5">
                    Cancel
                </a>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (!input.files || !input.files[0]) return;

    const reader = new FileReader();
    reader.onload = e => {
        // Replace whatever is in the preview slot with a fresh <img>
        preview.innerHTML = '';
        preview.classList.remove('flex','items-center','justify-center');
        const img = document.createElement('img');
        img.src = e.target.result;
        img.className = 'h-full w-full object-cover';
        preview.appendChild(img);
    };
    reader.readAsDataURL(input.files[0]);
}

document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) lucide.createIcons();
});
</script>
@endpush
@endsection