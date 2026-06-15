@extends('layouts.app')

@section('title', 'Settings – Cafe Delight')
@php $activeNav = 'Settings'; @endphp

@section('content')

{{-- ── Page header ──────────────────────────────────────── --}}
<div class="mb-8 flex flex-col gap-1" data-aos="fade-up">
    <p class="text-xs font-semibold uppercase tracking-widest text-brand-500">Preferences</p>
    <h1 class="font-display text-3xl font-extrabold">Settings</h1>
    <p class="text-sm text-ink/50 dark:text-orange-50/50">Control how Cafe Delight looks and behaves for you.</p>
</div>

@if(session('success'))
    <div class="mb-6 flex items-center gap-3 rounded-2xl bg-green-50 border border-green-200 px-5 py-4 text-sm font-semibold text-green-700 dark:bg-green-900/20 dark:border-green-700/40 dark:text-green-400" data-aos="fade-up">
        <i data-lucide="check-circle-2" class="h-5 w-5 shrink-0"></i>
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('settings.update') }}">
@csrf
@method('PUT')

<div class="grid gap-6 lg:grid-cols-3">

    {{-- ── Sidebar tabs ──────────────────────────────────── --}}
    <div data-aos="fade-up" data-aos-delay="50">
        <div class="glass rounded-3xl p-3 shadow-soft" x-data="{ tab: 'appearance' }">
            @php
                $tabs = [
                    ['id' => 'appearance', 'icon' => 'palette',         'label' => 'Appearance'],
                    ['id' => 'notifications','icon'=> 'bell',           'label' => 'Notifications'],
                    ['id' => 'privacy',    'icon' => 'shield-check',    'label' => 'Privacy'],
                    ['id' => 'language',   'icon' => 'globe',           'label' => 'Language & Region'],
                    ['id' => 'connected',  'icon' => 'link-2',          'label' => 'Connected Accounts'],
                ];
            @endphp

            @foreach($tabs as $t)
            <button type="button"
                    x-on:click="tab = '{{ $t['id'] }}'"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition text-left"
                    :class="tab === '{{ $t['id'] }}'
                        ? 'bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-glow'
                        : 'text-ink/65 hover:bg-brand-500/10 hover:text-brand-600 dark:text-orange-50/65 dark:hover:text-brand-300'">
                <i data-lucide="{{ $t['icon'] }}" class="h-5 w-5 shrink-0"></i>
                {{ $t['label'] }}
            </button>
            @endforeach

            {{-- Save button --}}
            <div class="mt-3 border-t border-black/5 pt-3 dark:border-white/5">
                <button type="submit"
                        class="w-full rounded-xl bg-gradient-to-r from-brand-500 to-brand-700 py-2.5 text-sm font-bold text-white shadow-glow transition hover:scale-105 active:scale-95">
                    Save Settings
                </button>
            </div>

            {{-- ────────── PANELS ────────── --}}
            {{-- Appearance --}}
            <div x-show="tab === 'appearance'" x-cloak class="mt-5 space-y-5 border-t border-black/5 pt-5 dark:border-white/5">

                <div>
                    <p class="mb-3 text-xs font-bold uppercase tracking-widest text-ink/40 dark:text-orange-50/40">Theme</p>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach([['light','sun','Light'],['dark','moon','Dark'],['system','monitor','System']] as [$val,$icon,$lbl])
                        <label class="cursor-pointer">
                            <input type="radio" name="theme" value="{{ $val }}" class="peer sr-only"
                                   {{ (old('theme', $settings['theme'] ?? 'system') === $val) ? 'checked' : '' }}>
                            <div class="flex flex-col items-center gap-1.5 rounded-xl border border-black/10 p-3 text-xs font-bold transition
                                        peer-checked:border-brand-500 peer-checked:bg-brand-500/10 peer-checked:text-brand-600
                                        hover:border-brand-300 dark:border-white/10 dark:peer-checked:border-brand-400">
                                <i data-lucide="{{ $icon }}" class="h-5 w-5"></i>
                                {{ $lbl }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="mb-3 text-xs font-bold uppercase tracking-widest text-ink/40 dark:text-orange-50/40">Accent Color</p>
                    <div class="flex gap-2 flex-wrap">
                        @foreach([
                            ['orange','#f97316','Orange'],
                            ['rose','#f43f5e','Rose'],
                            ['violet','#8b5cf6','Violet'],
                            ['sky','#0ea5e9','Sky'],
                            ['emerald','#10b981','Emerald'],
                        ] as [$cval,$chex,$clbl])
                        <label class="cursor-pointer" title="{{ $clbl }}">
                            <input type="radio" name="accent_color" value="{{ $cval }}" class="peer sr-only"
                                   {{ (old('accent_color', $settings['accent_color'] ?? 'orange') === $cval) ? 'checked' : '' }}>
                            <span class="block h-7 w-7 rounded-full ring-2 ring-offset-2 ring-transparent peer-checked:ring-current transition"
                                  style="background:{{ $chex }}; color:{{ $chex }}"></span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Compact mode --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold">Compact mode</p>
                        <p class="text-xs text-ink/50 dark:text-orange-50/50">Reduce spacing throughout the UI</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" name="compact_mode" value="1" class="peer sr-only"
                               {{ old('compact_mode', $settings['compact_mode'] ?? false) ? 'checked' : '' }}>
                        <div class="h-6 w-11 rounded-full bg-black/10 transition peer-checked:bg-brand-500 peer-focus:ring-2 peer-focus:ring-brand-500/30 dark:bg-white/10
                                    after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition peer-checked:after:translate-x-5"></div>
                    </label>
                </div>
            </div>

            {{-- Notifications --}}
            <div x-show="tab === 'notifications'" x-cloak class="mt-5 space-y-4 border-t border-black/5 pt-5 dark:border-white/5">
                @foreach([
                    ['notify_order_updates', 'Order updates',      'Confirmations, dispatch, delivery status'],
                    ['notify_promotions',    'Promotions',         'Discounts, offers, and seasonal deals'],
                    ['notify_new_dishes',    'New dishes',         'When new items are added to the menu'],
                    ['notify_newsletter',    'Newsletter',         'Monthly digest and food stories'],
                ] as [$name,$label,$desc])
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold">{{ $label }}</p>
                        <p class="text-xs text-ink/50 dark:text-orange-50/50">{{ $desc }}</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" name="{{ $name }}" value="1" class="peer sr-only"
                               {{ old($name, $settings[$name] ?? true) ? 'checked' : '' }}>
                        <div class="h-6 w-11 rounded-full bg-black/10 transition peer-checked:bg-brand-500 dark:bg-white/10
                                    after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition peer-checked:after:translate-x-5"></div>
                    </label>
                </div>
                @endforeach

                <div class="rounded-2xl bg-brand-500/8 p-4 dark:bg-brand-500/10">
                    <p class="text-xs font-bold uppercase tracking-widest text-brand-600 dark:text-brand-400 mb-3">Notification channel</p>
                    @foreach([['email','Email'],['sms','SMS'],['push','Push']] as [$v,$l])
                    <label class="flex items-center gap-3 py-1.5 cursor-pointer">
                        <input type="checkbox" name="notify_channel[]" value="{{ $v }}"
                               class="h-4 w-4 rounded border-black/20 text-brand-500 focus:ring-brand-500"
                               {{ in_array($v, old('notify_channel', $settings['notify_channel'] ?? ['email'])) ? 'checked' : '' }}>
                        <span class="text-sm font-medium">{{ $l }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Privacy --}}
            <div x-show="tab === 'privacy'" x-cloak class="mt-5 space-y-4 border-t border-black/5 pt-5 dark:border-white/5">
                @foreach([
                    ['privacy_profile_public','Public profile',    'Let other users see your display name'],
                    ['privacy_order_history', 'Order history',     'Allow Cafe Delight to use order history for recommendations'],
                    ['privacy_analytics',     'Usage analytics',   'Share anonymised usage data to improve the app'],
                ] as [$name,$label,$desc])
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold">{{ $label }}</p>
                        <p class="text-xs text-ink/50 dark:text-orange-50/50">{{ $desc }}</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" name="{{ $name }}" value="1" class="peer sr-only"
                               {{ old($name, $settings[$name] ?? false) ? 'checked' : '' }}>
                        <div class="h-6 w-11 rounded-full bg-black/10 transition peer-checked:bg-brand-500 dark:bg-white/10
                                    after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition peer-checked:after:translate-x-5"></div>
                    </label>
                </div>
                @endforeach

                <a href="#" class="mt-2 inline-flex items-center gap-2 text-xs font-bold text-brand-600 hover:underline dark:text-brand-400">
                    <i data-lucide="download" class="h-3.5 w-3.5"></i>
                    Download my data
                </a>
            </div>

            {{-- Language & Region --}}
            <div x-show="tab === 'language'" x-cloak class="mt-5 space-y-5 border-t border-black/5 pt-5 dark:border-white/5">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-ink/40 dark:text-orange-50/40">Language</label>
                    <select name="language"
                            class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-3 text-sm font-medium backdrop-blur focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 dark:bg-white/5 dark:border-white/10 dark:text-orange-50">
                        @foreach([
                            ['en','English'],['es','Español'],['fr','Français'],['de','Deutsch'],['sw','Kiswahili']
                        ] as [$v,$l])
                        <option value="{{ $v }}" {{ old('language', $settings['language'] ?? 'en') === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-ink/40 dark:text-orange-50/40">Timezone</label>
                    <select name="timezone"
                            class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-3 text-sm font-medium backdrop-blur focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 dark:bg-white/5 dark:border-white/10 dark:text-orange-50">
                        @foreach(timezone_identifiers_list() as $tz)
                        <option value="{{ $tz }}" {{ old('timezone', $settings['timezone'] ?? config('app.timezone')) === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-ink/40 dark:text-orange-50/40">Currency</label>
                    <select name="currency"
                            class="w-full rounded-xl border border-black/10 bg-white/60 px-4 py-3 text-sm font-medium backdrop-blur focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 dark:bg-white/5 dark:border-white/10 dark:text-orange-50">
                        @foreach([['USD','USD – US Dollar'],['EUR','EUR – Euro'],['GBP','GBP – British Pound'],['KES','KES – Kenyan Shilling']] as [$v,$l])
                        <option value="{{ $v }}" {{ old('currency', $settings['currency'] ?? 'USD') === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Connected Accounts --}}
            <div x-show="tab === 'connected'" x-cloak class="mt-5 space-y-3 border-t border-black/5 pt-5 dark:border-white/5">
                @foreach([
                    ['Google',   'google',   '#ea4335', 'G'],
                    ['Facebook', 'facebook',  '#1877f2', 'f'],
                    ['Apple',    'apple',     '#000000', ''],
                ] as [$name,$key,$color,$letter])
                <div class="flex items-center justify-between rounded-2xl border border-black/8 px-4 py-3 dark:border-white/8">
                    <div class="flex items-center gap-3">
                        <span class="grid h-8 w-8 place-items-center rounded-lg text-sm font-black text-white"
                              style="background:{{ $color }}">{{ $letter ?: '🍎' }}</span>
                        <div>
                            <p class="text-sm font-semibold">{{ $name }}</p>
                            <p class="text-xs text-ink/40 dark:text-orange-50/40">
                                {{ ($settings['connected'][$key] ?? false) ? 'Connected' : 'Not connected' }}
                            </p>
                        </div>
                    </div>
                    <button type="button"
                            class="rounded-lg border border-black/10 px-3 py-1.5 text-xs font-bold transition hover:bg-brand-500 hover:text-white hover:border-brand-500 dark:border-white/10">
                        {{ ($settings['connected'][$key] ?? false) ? 'Disconnect' : 'Connect' }}
                    </button>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Right: summary / tips ─────────────────────────── --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Account summary card --}}
        <div class="glass rounded-3xl p-6 shadow-soft" data-aos="fade-up" data-aos-delay="100">
            <h3 class="mb-4 font-display text-lg font-bold flex items-center gap-2">
                <i data-lucide="shield" class="h-5 w-5 text-brand-500"></i>
                Account Security
            </h3>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="flex items-center gap-4 rounded-2xl bg-green-50 p-4 dark:bg-green-900/20">
                    <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-green-100 dark:bg-green-800/40">
                        <i data-lucide="mail-check" class="h-5 w-5 text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-green-700 dark:text-green-400">Email verified</p>
                        <p class="text-xs text-green-600/70 dark:text-green-500/70">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 rounded-2xl bg-brand-500/8 p-4 dark:bg-brand-500/10">
                    <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand-500/15">
                        <i data-lucide="key-round" class="h-5 w-5 text-brand-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold">Password</p>
                        <p class="text-xs text-ink/50 dark:text-orange-50/50">Last changed {{ auth()->user()->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between rounded-2xl border border-black/8 p-4 dark:border-white/8 sm:col-span-2">
                    <div class="flex items-center gap-4">
                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-violet-100 dark:bg-violet-900/30">
                            <i data-lucide="smartphone" class="h-5 w-5 text-violet-600 dark:text-violet-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold">Two-factor authentication</p>
                            <p class="text-xs text-ink/50 dark:text-orange-50/50">Add an extra layer of security to your account</p>
                        </div>
                    </div>
                    <span class="rounded-full bg-black/8 px-3 py-1 text-xs font-bold dark:bg-white/10">Off</span>
                </div>
            </div>
        </div>

        {{-- Active sessions --}}
        <div class="glass rounded-3xl p-6 shadow-soft" data-aos="fade-up" data-aos-delay="150">
            <h3 class="mb-4 font-display text-lg font-bold flex items-center gap-2">
                <i data-lucide="monitor-smartphone" class="h-5 w-5 text-brand-500"></i>
                Active Sessions
            </h3>
            <div class="space-y-3">
                @php
                    $sessions = [
                        ['icon'=>'monitor',  'device'=>'Chrome on Windows', 'location'=>'Current session', 'active'=>true],
                        ['icon'=>'smartphone','device'=>'Safari on iPhone', 'location'=>'Nairobi, KE · 2 hrs ago', 'active'=>false],
                    ];
                @endphp
                @foreach($sessions as $s)
                <div class="flex items-center justify-between rounded-2xl border border-black/8 px-4 py-3 dark:border-white/8">
                    <div class="flex items-center gap-3">
                        <i data-lucide="{{ $s['icon'] }}" class="h-5 w-5 text-ink/40 dark:text-orange-50/40"></i>
                        <div>
                            <p class="text-sm font-semibold">{{ $s['device'] }}</p>
                            <p class="text-xs text-ink/40 dark:text-orange-50/40">{{ $s['location'] }}</p>
                        </div>
                    </div>
                    @if($s['active'])
                        <span class="rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 px-3 py-1 text-xs font-bold">
                            Active
                        </span>
                    @else
                        <button type="button" class="text-xs font-bold text-red-500 hover:underline">Revoke</button>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Data & storage --}}
        <div class="glass rounded-3xl p-6 shadow-soft" data-aos="fade-up" data-aos-delay="200">
            <h3 class="mb-4 font-display text-lg font-bold flex items-center gap-2">
                <i data-lucide="database" class="h-5 w-5 text-brand-500"></i>
                Data & Storage
            </h3>
            <div class="grid gap-3 sm:grid-cols-3">
                @foreach([
                    ['Orders', $orderCount ?? 0,    'shopping-bag', 'brand'],
                    ['Favorites', $favoriteCount ?? 0, 'heart',       'red'],
                    ['Addresses', $addressCount ?? 0, 'map-pin',      'violet'],
                ] as [$label,$count,$icon,$clr])
                <div class="rounded-2xl bg-{{ $clr }}-500/8 p-4 dark:bg-{{ $clr }}-500/10">
                    <i data-lucide="{{ $icon }}" class="h-5 w-5 text-{{ $clr }}-500 mb-2"></i>
                    <p class="text-2xl font-extrabold">{{ $count }}</p>
                    <p class="text-xs text-ink/50 dark:text-orange-50/50">{{ $label }}</p>
                </div>
                @endforeach
            </div>
            <p class="mt-4 text-xs text-ink/40 dark:text-orange-50/30">
                You can request a full export of your data from the Privacy tab.
            </p>
        </div>

    </div>
</div>

</form>
@endsection