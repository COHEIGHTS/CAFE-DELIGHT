@extends('layouts.customer-layout')

@section('title', 'Settings — Cafe Delight')

@php $activeNav = 'Settings'; @endphp

@section('content')
<div class="space-y-6">
    <div data-aos="fade-up" class="rounded-3xl glass p-8 shadow-soft">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold">Settings</h1>
            <p class="mt-2 text-base text-ink/65 dark:text-orange-50/65">Manage your account preferences and notifications</p>
        </div>

        <form method="POST" action="{{ route('settings.update') }}" class="space-y-8">
            @csrf
            @method('patch')

            {{-- Email Notifications --}}
            <div class="rounded-2xl glass-strong p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="rounded-xl bg-brand-500/10 p-2">
                        <i data-lucide="mail" class="h-5 w-5 text-brand-600"></i>
                    </div>
                    <h2 class="text-xl font-bold">Email Notifications</h2>
                </div>
                
                <div class="space-y-3">
                    <label class="group flex items-start gap-4 p-4 rounded-xl border border-transparent hover:border-brand-200 hover:bg-brand-50/50 dark:hover:border-brand-700/50 dark:hover:bg-brand-900/20 cursor-pointer transition-all">
                        <div class="relative flex-shrink-0 mt-0.5">
                            <input type="checkbox" 
                                   name="email_notifications" 
                                   value="1"
                                   {{ old('email_notifications', $user->settings['email_notifications'] ?? true) ? 'checked' : '' }}
                                   class="peer h-6 w-6 rounded-lg border-2 border-gray-300 text-brand-600 focus:ring-brand-500 focus:ring-offset-0 transition-all">
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-base group-hover:text-brand-600 transition-colors">Email Notifications</p>
                            <p class="mt-1 text-sm text-ink/60 dark:text-orange-50/60">Receive email notifications about your account</p>
                        </div>
                    </label>

                    <label class="group flex items-start gap-4 p-4 rounded-xl border border-transparent hover:border-brand-200 hover:bg-brand-50/50 dark:hover:border-brand-700/50 dark:hover:bg-brand-900/20 cursor-pointer transition-all">
                        <div class="relative flex-shrink-0 mt-0.5">
                            <input type="checkbox" 
                                   name="order_updates" 
                                   value="1"
                                   {{ old('order_updates', $user->settings['order_updates'] ?? true) ? 'checked' : '' }}
                                   class="peer h-6 w-6 rounded-lg border-2 border-gray-300 text-brand-600 focus:ring-brand-500 focus:ring-offset-0 transition-all">
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-base group-hover:text-brand-600 transition-colors">Order Updates</p>
                            <p class="mt-1 text-sm text-ink/60 dark:text-orange-50/60">Get notified when your order status changes</p>
                        </div>
                    </label>

                    <label class="group flex items-start gap-4 p-4 rounded-xl border border-transparent hover:border-brand-200 hover:bg-brand-50/50 dark:hover:border-brand-700/50 dark:hover:bg-brand-900/20 cursor-pointer transition-all">
                        <div class="relative flex-shrink-0 mt-0.5">
                            <input type="checkbox" 
                                   name="promotional_emails" 
                                   value="1"
                                   {{ old('promotional_emails', $user->settings['promotional_emails'] ?? false) ? 'checked' : '' }}
                                   class="peer h-6 w-6 rounded-lg border-2 border-gray-300 text-brand-600 focus:ring-brand-500 focus:ring-offset-0 transition-all">
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-base group-hover:text-brand-600 transition-colors">Promotional Emails</p>
                            <p class="mt-1 text-sm text-ink/60 dark:text-orange-50/60">Receive special offers and discounts</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="flex items-center gap-4 pt-2">
                <button type="submit" 
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-8 py-3.5 text-sm font-bold text-white transition-all hover:scale-105 hover:shadow-lg shadow-glow">
                    <i data-lucide="save" class="h-4 w-4"></i> Save Settings
                </button>

                @if (session('status') === 'settings-updated')
                    <div class="flex items-center gap-2 text-sm font-semibold text-emerald-600">
                        <i data-lucide="check-circle" class="h-4 w-4"></i>
                        <span>Settings saved successfully!</span>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
    });
</script>
@endpush