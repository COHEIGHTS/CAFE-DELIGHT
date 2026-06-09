@extends('layouts.admin-layout')

@section('title', 'Add Dish — Admin · Cafe Delight')

@php $activeNav = 'Add Dish'; @endphp

@section('content')
<div class="max-w-4xl" data-aos="fade-up">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="font-display text-3xl font-bold">Add New Dish</h1>
        <p class="mt-2 text-ink/60 dark:text-orange-50/60">Create a new menu item with photos, pricing, and features</p>
    </div>

    {{-- Success Message --}}
    @if($errors->any())
        <div class="mb-6 rounded-2xl glass p-4 border-l-4 border-red-500 shadow-soft" data-aos="fade-down">
            <div class="flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 mt-0.5 shrink-0"></i>
                <div>
                    <h3 class="font-bold text-red-600">Errors found:</h3>
                    <ul class="mt-2 space-y-1 text-sm text-red-600">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Basic Information --}}
        <div class="rounded-2xl glass p-6 shadow-soft" data-aos="fade-up" data-aos-delay="100">
            <h2 class="mb-6 text-lg font-bold flex items-center gap-2">
                <i data-lucide="info" class="w-5 h-5 text-brand-600"></i>
                Basic Information
            </h2>

            <div class="space-y-4">
                {{-- Dish Name --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">Dish Name *</label>
                    <input type="text" name="name" placeholder="e.g., Chicken Biryani" 
                           class="w-full px-4 py-3 border border-black/10 dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 dark:bg-ink dark:text-orange-50 @error('name') border-red-500 @enderror"
                           value="{{ old('name') }}" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">Description *</label>
                    <textarea name="description" rows="4" placeholder="Describe your dish in detail..."
                              class="w-full px-4 py-3 border border-black/10 dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 dark:bg-ink dark:text-orange-50 @error('description') border-red-500 @enderror"
                              required>{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Category *</label>
                        <select name="category" class="w-full px-4 py-3 border border-black/10 dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 dark:bg-ink dark:text-orange-50 @error('category') border-red-500 @enderror" required>
                            <option value="">Select Category</option>
                            <option value="appetizers" {{ old('category') === 'appetizers' ? 'selected' : '' }}>🥢 Appetizers</option>
                            <option value="mains" {{ old('category') === 'mains' ? 'selected' : '' }}>🍽️ Main Courses</option>
                            <option value="desserts" {{ old('category') === 'desserts' ? 'selected' : '' }}>🍰 Desserts</option>
                            <option value="beverages" {{ old('category') === 'beverages' ? 'selected' : '' }}>🥤 Beverages</option>
                            <option value="sides" {{ old('category') === 'sides' ? 'selected' : '' }}>🍟 Sides</option>
                        </select>
                        @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Price --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">Price (KSh) *</label>
                        <input type="number" name="price" step="0.01" placeholder="e.g., 550" 
                               class="w-full px-4 py-3 border border-black/10 dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 dark:bg-ink dark:text-orange-50 @error('price') border-red-500 @enderror"
                               value="{{ old('price') }}" required>
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Images --}}
        <div class="rounded-2xl glass p-6 shadow-soft" data-aos="fade-up" data-aos-delay="200">
            <h2 class="mb-6 text-lg font-bold flex items-center gap-2">
                <i data-lucide="image" class="w-5 h-5 text-brand-600"></i>
                Dish Images (Cool Pictures!)
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Primary Image --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">Primary Image * (Main Photo)</label>
                    <div class="relative border-2 border-dashed border-black/10 dark:border-white/10 rounded-xl p-6 text-center hover:border-brand-500 transition cursor-pointer group"
                         onclick="document.getElementById('primary_image').click()">
                        <input type="file" id="primary_image" name="primary_image" accept="image/*" 
                               class="hidden" onchange="previewImage(this, 'preview_primary')" required>
                        <i data-lucide="image-plus" class="w-8 h-8 text-black/30 dark:text-white/30 mx-auto mb-2 group-hover:text-brand-600 transition"></i>
                        <p class="text-sm font-semibold">Click to upload</p>
                        <p class="text-xs text-ink/50 dark:text-orange-50/50 mt-1">JPG, PNG (max 2MB)</p>
                    </div>
                    <img id="preview_primary" class="mt-3 rounded-lg max-h-40 hidden w-full object-cover">
                </div>

                {{-- Secondary Image --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">Secondary Image (Alternative Angle)</label>
                    <div class="relative border-2 border-dashed border-black/10 dark:border-white/10 rounded-xl p-6 text-center hover:border-brand-500 transition cursor-pointer group"
                         onclick="document.getElementById('secondary_image').click()">
                        <input type="file" id="secondary_image" name="secondary_image" accept="image/*" 
                               class="hidden" onchange="previewImage(this, 'preview_secondary')">
                        <i data-lucide="image-plus" class="w-8 h-8 text-black/30 dark:text-white/30 mx-auto mb-2 group-hover:text-brand-600 transition"></i>
                        <p class="text-sm font-semibold">Click to upload</p>
                        <p class="text-xs text-ink/50 dark:text-orange-50/50 mt-1">Optional</p>
                    </div>
                    <img id="preview_secondary" class="mt-3 rounded-lg max-h-40 hidden w-full object-cover">
                </div>

                {{-- Tertiary Image --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">Tertiary Image (Closeup/Detail)</label>
                    <div class="relative border-2 border-dashed border-black/10 dark:border-white/10 rounded-xl p-6 text-center hover:border-brand-500 transition cursor-pointer group"
                         onclick="document.getElementById('tertiary_image').click()">
                        <input type="file" id="tertiary_image" name="tertiary_image" accept="image/*" 
                               class="hidden" onchange="previewImage(this, 'preview_tertiary')">
                        <i data-lucide="image-plus" class="w-8 h-8 text-black/30 dark:text-white/30 mx-auto mb-2 group-hover:text-brand-600 transition"></i>
                        <p class="text-sm font-semibold">Click to upload</p>
                        <p class="text-xs text-ink/50 dark:text-orange-50/50 mt-1">Optional</p>
                    </div>
                    <img id="preview_tertiary" class="mt-3 rounded-lg max-h-40 hidden w-full object-cover">
                </div>
            </div>
        </div>

        {{-- Features & Attributes --}}
        <div class="rounded-2xl glass p-6 shadow-soft" data-aos="fade-up" data-aos-delay="300">
            <h2 class="mb-6 text-lg font-bold flex items-center gap-2">
                <i data-lucide="zap" class="w-5 h-5 text-brand-600"></i>
                Features & Dietary Info
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <label class="flex items-center gap-3 p-4 border border-black/10 dark:border-white/10 rounded-lg hover:bg-brand-500/5 cursor-pointer transition">
                    <input type="checkbox" name="is_vegetarian" class="w-5 h-5 text-brand-600 rounded" {{ old('is_vegetarian') ? 'checked' : '' }}>
                    <span class="font-semibold">🌱 Vegetarian</span>
                </label>
                <label class="flex items-center gap-3 p-4 border border-black/10 dark:border-white/10 rounded-lg hover:bg-brand-500/5 cursor-pointer transition">
                    <input type="checkbox" name="is_vegan" class="w-5 h-5 text-brand-600 rounded" {{ old('is_vegan') ? 'checked' : '' }}>
                    <span class="font-semibold">🥗 Vegan</span>
                </label>
                <label class="flex items-center gap-3 p-4 border border-black/10 dark:border-white/10 rounded-lg hover:bg-brand-500/5 cursor-pointer transition">
                    <input type="checkbox" name="is_spicy" class="w-5 h-5 text-brand-600 rounded" {{ old('is_spicy') ? 'checked' : '' }}>
                    <span class="font-semibold">🌶️ Spicy</span>
                </label>
                <label class="flex items-center gap-3 p-4 border border-black/10 dark:border-white/10 rounded-lg hover:bg-brand-500/5 cursor-pointer transition">
                    <input type="checkbox" name="is_gluten_free" class="w-5 h-5 text-brand-600 rounded" {{ old('is_gluten_free') ? 'checked' : '' }}>
                    <span class="font-semibold">🌾 Gluten Free</span>
                </label>
                <label class="flex items-center gap-3 p-4 border border-black/10 dark:border-white/10 rounded-lg hover:bg-brand-500/5 cursor-pointer transition">
                    <input type="checkbox" name="is_dairy_free" class="w-5 h-5 text-brand-600 rounded" {{ old('is_dairy_free') ? 'checked' : '' }}>
                    <span class="font-semibold">🥛 Dairy Free</span>
                </label>
                <label class="flex items-center gap-3 p-4 border border-black/10 dark:border-white/10 rounded-lg hover:bg-brand-500/5 cursor-pointer transition">
                    <input type="checkbox" name="is_bestseller" class="w-5 h-5 text-brand-600 rounded" {{ old('is_bestseller') ? 'checked' : '' }}>
                    <span class="font-semibold">⭐ Bestseller</span>
                </label>
            </div>
        </div>

        {{-- Ingredients & Preparation --}}
        <div class="rounded-2xl glass p-6 shadow-soft" data-aos="fade-up" data-aos-delay="400">
            <h2 class="mb-6 text-lg font-bold flex items-center gap-2">
                <i data-lucide="list" class="w-5 h-5 text-brand-600"></i>
                Ingredients & Preparation
            </h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Main Ingredients</label>
                    <textarea name="ingredients" rows="3" placeholder="e.g., Rice, Chicken, Spices, Onions, Garlic, Ginger..."
                              class="w-full px-4 py-3 border border-black/10 dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 dark:bg-ink dark:text-orange-50">{{ old('ingredients') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Preparation Time (minutes)</label>
                        <input type="number" name="prep_time" placeholder="e.g., 30" 
                               class="w-full px-4 py-3 border border-black/10 dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 dark:bg-ink dark:text-orange-50"
                               value="{{ old('prep_time') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Serving Size</label>
                        <input type="text" name="serving_size" placeholder="e.g., Serves 1-2 people" 
                               class="w-full px-4 py-3 border border-black/10 dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 dark:bg-ink dark:text-orange-50"
                               value="{{ old('serving_size') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-4" data-aos="fade-up" data-aos-delay="500">
            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-brand-600 to-brand-700 text-white font-bold py-4 rounded-xl hover:shadow-glow transition">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                Add Dish to Menu ✨
            </button>
            <a href="{{ route('admin.menu.index') }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-black/10 dark:bg-white/10 text-ink dark:text-orange-50 font-bold py-4 rounded-xl hover:scale-105 transition">
                <i data-lucide="x-circle" class="w-5 h-5"></i>
                Cancel
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const preview = document.getElementById(previewId);
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection