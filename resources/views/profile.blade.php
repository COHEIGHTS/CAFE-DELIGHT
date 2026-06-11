@extends('layouts.app')

@section('title', 'My Profile – Cafe Delight')
@php $activeNav = 'Profile'; @endphp

@section('content')

{{-- ── Page header ──────────────────────────────────────── --}}
<div class="mb-8 flex flex-col gap-1" data-aos="fade-up">
    <p class="text-xs font-semibold uppercase tracking-widest text-brand-500">Account</p>
    <h1 class="font-display text-3xl font-extrabold">My Profile</h1>
    <p class="text-sm text-ink/50 dark:text-orange-50/50">Manage your personal details and public information.</p>
</div>

@if(session('success'))
    <div class="mb-6 flex items-center gap-3 rounded-2xl bg-green-50 border border-green-200 px-5 py-4 text-sm font-semibold text-green-700 dark:bg-green-900/20 dark:border-green-700/40 dark:text-green-400"
         data-aos="fade-up">
        <i data-lucide="check-circle-2" class="h-5 w-5 shrink-0"></i>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 flex items-start gap-3 rounded-2xl bg-red-50 border border-red-200 px-5 py-4 text-sm font-semibold text-red-600 dark:bg-red-900/20 dark:border-red-700/40 dark:text-red-400"
         data-aos="fade-up">
        <i data-lucide="alert-circle" class="h-5 w-5 shrink-0 mt-0.5"></i>
        <ul class="space-y-1">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid gap-6 lg:grid-cols-3">

    {{-- ── Left: Avatar card ─────────────────────────────── --}}
    <div data-aos="fade-up" data-aos-delay="50">
        <div class="glass rounded-3xl p-6 text-center shadow-soft">
            {{-- Avatar --}}
            <div class="relative mx-auto w-fit">
                <div id="avatar-preview"
                     class="mx-auto grid h-28 w-28 place-items-center rounded-full bg-gradient-to-br from-brand-400 to-brand-700 text-4xl font-extrabold text-white shadow-glow select-none">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <label for="avatar-upload"
                       class="absolute bottom-0 right-0 grid h-9 w-9 cursor-pointer place-items-center rounded-full bg-white shadow-md border border-black/10 transition hover:scale-110 dark:bg-zinc-800 dark:border-white/10"
                       title="Change photo">
                    <i data-lucide="camera" class="h-4 w-4 text-brand-600"></i>
                    <input id="avatar-upload" type="file" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                </label>
            </div>

            <h2 class="mt-4 font-display text-xl font-bold">{{ auth()->user()->name }}</h2>
            <p class="mt-0.5 text-sm text-ink/50 dark:text-orange-50/50">{{ auth()->user()->email }}</p>

            {{-- Member badge --}}
            <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-brand-500/10 px-4 py-1.5 text-xs font-bold text-brand-600 dark:text-brand-400">
                <i data-lucide="star" class="h-3.5 w-3.5"></i>
                Member since {{ auth()->user()->created_at->format('M Y') }}
            </div>

            {{-- Quick stats --}}
            <div class="mt-6 grid grid-cols-2 gap-3">
                <div class="rounded-2xl bg-brand-500/8 p-3 dark:bg-brand-500/10">
                    <p class="text-2xl font-extrabold text-brand-600">{{ $orderCount ?? 0 }}</p>
                    <p class="text-xs text-ink/50 dark:text-orange-50/50">Orders</p>
                </div>
                <div class="rounded-2xl bg-red-500/8 p-3 dark:bg-red-500/10">
                    <p class="text-2xl font-extrabold text-red-500">{{ $favoriteCount ?? 0 }}</p>
                    <p class="text-xs text-ink/50 dark:text-orange-50/50">Favorites</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Right: Edit form ──────────────────────────────── --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Personal info --}}
        <div class="glass rounded-3xl p-6 shadow-soft" data-aos="fade-up" data-aos-delay="100">
            <h3 class="mb-5 font-display text-lg font-bold flex items-center gap-2">
                <i data-lucide="user-circle-2" class="h-5 w-5 text-brand-500"></i>
                Personal Information
            </h3>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="file" name="avatar" id="avatar-hidden" class="hidden">

                <div class="grid gap-5 sm:grid-cols-2">
                    {{-- Name --}}
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-ink/50 dark:text-orange-50/40">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                               class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-3 text-sm font-medium backdrop-blur focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 dark:bg-white/5 dark:border-white/10 dark:text-orange-50 transition">
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-ink/50 dark:text-orange-50/40">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}"
                               placeholder="+1 (555) 000-0000"
                               class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-3 text-sm font-medium backdrop-blur focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 dark:bg-white/5 dark:border-white/10 dark:text-orange-50 transition">
                    </div>

                    {{-- Email --}}
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-ink/50 dark:text-orange-50/40">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                               class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-3 text-sm font-medium backdrop-blur focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 dark:bg-white/5 dark:border-white/10 dark:text-orange-50 transition">
                    </div>

                    {{-- Bio --}}
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-ink/50 dark:text-orange-50/40">Bio <span class="normal-case font-normal">(optional)</span></label>
                        <textarea name="bio" rows="3" placeholder="Tell us a little about yourself..."
                                  class="w-full resize-none rounded-xl border border-black/10 bg-white/60 px-4 py-3 text-sm font-medium backdrop-blur focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 dark:bg-white/5 dark:border-white/10 dark:text-orange-50 transition">{{ old('bio', auth()->user()->bio ?? '') }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="reset"
                            class="rounded-xl px-5 py-2.5 text-sm font-bold text-ink/50 transition hover:bg-black/5 dark:text-orange-50/50 dark:hover:bg-white/5">
                        Reset
                    </button>
                    <button type="submit"
                            class="rounded-xl bg-gradient-to-r from-brand-500 to-brand-700 px-6 py-2.5 text-sm font-bold text-white shadow-glow transition hover:scale-105 hover:shadow-lg active:scale-95">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Change password --}}
        <div class="glass rounded-3xl p-6 shadow-soft" data-aos="fade-up" data-aos-delay="150">
            <h3 class="mb-5 font-display text-lg font-bold flex items-center gap-2">
                <i data-lucide="lock-keyhole" class="h-5 w-5 text-brand-500"></i>
                Change Password
            </h3>

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-ink/50 dark:text-orange-50/40">Current Password</label>
                        <div class="relative">
                            <input type="password" name="current_password" id="pw-current"
                                   class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-3 pr-11 text-sm font-medium backdrop-blur focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 dark:bg-white/5 dark:border-white/10 dark:text-orange-50 transition">
                            <button type="button" onclick="togglePw('pw-current', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink/40 hover:text-brand-500 transition">
                                <i data-lucide="eye" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-ink/50 dark:text-orange-50/40">New Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="pw-new"
                                   class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-3 pr-11 text-sm font-medium backdrop-blur focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 dark:bg-white/5 dark:border-white/10 dark:text-orange-50 transition">
                            <button type="button" onclick="togglePw('pw-new', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink/40 hover:text-brand-500 transition">
                                <i data-lucide="eye" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-ink/50 dark:text-orange-50/40">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="pw-confirm"
                                   class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-3 pr-11 text-sm font-medium backdrop-blur focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 dark:bg-white/5 dark:border-white/10 dark:text-orange-50 transition">
                            <button type="button" onclick="togglePw('pw-confirm', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink/40 hover:text-brand-500 transition">
                                <i data-lucide="eye" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                            class="rounded-xl bg-gradient-to-r from-brand-500 to-brand-700 px-6 py-2.5 text-sm font-bold text-white shadow-glow transition hover:scale-105 active:scale-95">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        {{-- Danger zone --}}
        <div class="glass rounded-3xl border border-red-200/60 p-6 shadow-soft dark:border-red-900/40" data-aos="fade-up" data-aos-delay="200">
            <h3 class="mb-2 font-display text-lg font-bold text-red-500 flex items-center gap-2">
                <i data-lucide="triangle-alert" class="h-5 w-5"></i>
                Danger Zone
            </h3>
            <p class="mb-5 text-sm text-ink/50 dark:text-orange-50/50">Deleting your account is permanent and cannot be undone. All your orders, favorites, and data will be removed.</p>
            <button onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                    class="rounded-xl border border-red-300 px-5 py-2.5 text-sm font-bold text-red-500 transition hover:bg-red-500 hover:text-white dark:border-red-700">
                Delete My Account
            </button>
        </div>

    </div>
</div>

{{-- ── Delete confirmation modal ───────────────────────── --}}
<div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="glass-strong mx-4 w-full max-w-sm rounded-3xl p-8 shadow-premium" data-aos="zoom-in">
        <div class="mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-red-100 dark:bg-red-900/30">
            <i data-lucide="trash-2" class="h-7 w-7 text-red-500"></i>
        </div>
        <h3 class="font-display text-xl font-bold">Delete account?</h3>
        <p class="mt-2 text-sm text-ink/60 dark:text-orange-50/60">This will permanently erase all your data. Type your password to confirm.</p>
        <form method="POST" action="{{ route('profile.destroy') }}" class="mt-5 space-y-4">
            @csrf
            @method('DELETE')
            <input type="password" name="password" placeholder="Your password" required
                   class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-3 text-sm backdrop-blur focus:border-red-500 focus:ring-2 focus:ring-red-500/30 dark:bg-white/5 dark:border-white/10 dark:text-orange-50">
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                        class="flex-1 rounded-xl border border-black/10 py-2.5 text-sm font-bold transition hover:bg-black/5 dark:border-white/10 dark:hover:bg-white/5">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 rounded-xl bg-red-500 py-2.5 text-sm font-bold text-white transition hover:bg-red-600">
                    Yes, Delete
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (!file) return;
        // Transfer to the hidden form input
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('avatar-hidden').files = dt.files;
        // Show image preview
        const reader = new FileReader();
        reader.onload = e => {
            const el = document.getElementById('avatar-preview');
            el.innerHTML = `<img src="${e.target.result}" class="h-28 w-28 rounded-full object-cover">`;
        };
        reader.readAsDataURL(file);
    }

    function togglePw(id, btn) {
        const input = document.getElementById(id);
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        btn.querySelector('[data-lucide]').setAttribute('data-lucide', isText ? 'eye' : 'eye-off');
        lucide.createIcons();
    }
</script>
@endpush