@extends('layouts.admin-layout')

@section('title', 'Settings — Admin · Cafe Delight')

@php $activeNav = 'Settings'; @endphp

@section('content')
<div class="space-y-6">

    {{-- ===== Header ===== --}}
    <div data-aos="fade-up" class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-extrabold">Settings</h1>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Manage your cafe configuration and preferences</p>
        </div>
    </div>

    {{-- ===== System Overview ===== --}}
    <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
        <h2 class="mb-4 text-lg font-bold">System Overview</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl bg-brand-500/10 p-4">
                <p class="text-xs font-extrabold uppercase tracking-widest text-brand-600">Total Orders</p>
                <p class="mt-2 text-2xl font-extrabold">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="rounded-xl bg-emerald-500/10 p-4">
                <p class="text-xs font-extrabold uppercase tracking-widest text-emerald-600">Total Revenue</p>
                <p class="mt-2 text-2xl font-extrabold">KSh {{ number_format($stats['total_revenue'], 0) }}</p>
            </div>
            <div class="rounded-xl bg-blue-500/10 p-4">
                <p class="text-xs font-extrabold uppercase tracking-widest text-blue-600">Total Customers</p>
                <p class="mt-2 text-2xl font-extrabold">{{ $stats['total_customers'] }}</p>
            </div>
            <div class="rounded-xl bg-purple-500/10 p-4">
                <p class="text-xs font-extrabold uppercase tracking-widest text-purple-600">Menu Items</p>
                <p class="mt-2 text-2xl font-extrabold">{{ $stats['total_dishes'] }}</p>
            </div>
        </div>
    </div>

    {{-- ===== General Settings ===== --}}
    <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
        <h2 class="mb-4 text-lg font-bold">General Settings</h2>
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')
            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold">Site Name</label>
                    <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name) }}"
                           class="w-full rounded-xl border border-black/10 bg-white/50 px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold">Site Email</label>
                    <input type="email" name="site_email" value="{{ old('site_email', $settings->site_email) }}"
                           class="w-full rounded-xl border border-black/10 bg-white/50 px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold">Site Phone</label>
                    <input type="text" name="site_phone" value="{{ old('site_phone', $settings->site_phone) }}"
                           class="w-full rounded-xl border border-black/10 bg-white/50 px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold">Delivery Fee (KSh)</label>
                    <input type="number" name="delivery_fee" value="{{ old('delivery_fee', $settings->delivery_fee) }}" step="0.01"
                           class="w-full rounded-xl border border-black/10 bg-white/50 px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold">Tax Rate (%)</label>
                    <input type="number" name="tax_rate" value="{{ old('tax_rate', $settings->tax_rate) }}" step="0.1"
                           class="w-full rounded-xl border border-black/10 bg-white/50 px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold">Currency</label>
                    <input type="text" name="currency" value="{{ old('currency', $settings->currency) }}"
                           class="w-full rounded-xl border border-black/10 bg-white/50 px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                </div>
                <div class="lg:col-span-2">
                    <label class="mb-2 block text-sm font-semibold">Address</label>
                    <textarea name="address" rows="3" class="w-full rounded-xl border border-black/10 bg-white/50 px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">{{ old('address', $settings->address) }}</textarea>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold">Facebook URL</label>
                    <input type="url" name="social_facebook" value="{{ old('social_facebook', $settings->social_facebook) }}"
                           class="w-full rounded-xl border border-black/10 bg-white/50 px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold">Twitter URL</label>
                    <input type="url" name="social_twitter" value="{{ old('social_twitter', $settings->social_twitter) }}"
                           class="w-full rounded-xl border border-black/10 bg-white/50 px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold">Instagram URL</label>
                    <input type="url" name="social_instagram" value="{{ old('social_instagram', $settings->social_instagram) }}"
                           class="w-full rounded-xl border border-black/10 bg-white/50 px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="flex items-center gap-2 rounded-xl bg-brand-600 text-white px-6 py-3 text-sm font-bold transition hover:scale-105">
                    <i data-lucide="save" class="h-4 w-4"></i> Save Settings
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
        if (window.AOS) AOS.init({ duration: 600, once: true, easing: 'ease-out-cubic' });
    });
</script>
@endpush
