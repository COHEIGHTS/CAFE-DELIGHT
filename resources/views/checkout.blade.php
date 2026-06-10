@extends('layouts.customer-layout')

@section('title', 'Checkout — Cafe Delight')
@php $activeNav = 'My Orders'; @endphp

@section('content')
<div class="mx-auto max-w-5xl" x-data="{ submitting: false }">

    {{-- Header --}}
    <div data-aos="fade-up" class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('cart.index') }}"
               class="grid h-9 w-9 place-items-center rounded-xl glass text-ink/60 transition hover:text-brand-600">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <h1 class="font-display text-3xl font-extrabold">Checkout</h1>
        </div>
        <p class="ml-12 text-sm text-ink/55 dark:text-orange-50/55">Almost there! Fill in your delivery details.</p>
    </div>

    {{-- Progress steps --}}
    <div data-aos="fade-up" class="mb-8 flex items-center gap-2">
        <div class="flex items-center gap-2">
            <span class="grid h-8 w-8 place-items-center rounded-full bg-emerald-500 text-xs font-extrabold text-white">
                <i data-lucide="check" class="h-4 w-4"></i>
            </span>
            <span class="text-sm font-bold text-emerald-600">Cart</span>
        </div>
        <div class="flex-1 h-px bg-brand-500/30"></div>
        <div class="flex items-center gap-2">
            <span class="grid h-8 w-8 place-items-center rounded-full bg-gradient-to-br from-brand-500 to-brand-600 text-xs font-extrabold text-white">2</span>
            <span class="text-sm font-bold text-brand-600">Delivery</span>
        </div>
        <div class="flex-1 h-px bg-black/10 dark:bg-white/10"></div>
        <div class="flex items-center gap-2">
            <span class="grid h-8 w-8 place-items-center rounded-full bg-black/10 dark:bg-white/10 text-xs font-extrabold text-ink/40 dark:text-orange-50/40">3</span>
            <span class="text-sm font-semibold text-ink/40 dark:text-orange-50/40">Confirmation</span>
        </div>
    </div>

    <form method="POST" action="{{ route('checkout.store') }}" x-on:submit="submitting = true">
        @csrf

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- ===== LEFT: Delivery form ===== --}}
            <div class="space-y-5 lg:col-span-2">

                {{-- Delivery address --}}
                <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
                    <h2 class="mb-5 flex items-center gap-2 text-lg font-extrabold">
                        <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 text-white">
                            <i data-lucide="map-pin" class="h-5 w-5"></i>
                        </span>
                        Delivery Address
                    </h2>

                    <div class="space-y-4">
                        {{-- Full address --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold">Full Address <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <i data-lucide="home" class="pointer-events-none absolute left-4 top-4 h-5 w-5 text-ink/40 dark:text-orange-50/40"></i>
                                <textarea name="delivery_address" rows="3" required
                                          placeholder="e.g. House No. 12, Nakuru Town, near KFC, Nakuru County"
                                          class="w-full rounded-xl border-0 glass py-3 pl-12 pr-4 text-sm font-medium placeholder:text-ink/40 focus:ring-2 focus:ring-brand-500 dark:placeholder:text-orange-50/30 resize-none">{{ old('delivery_address') }}</textarea>
                            </div>
                            @error('delivery_address')
                            <p class="mt-1.5 flex items-center gap-1 text-sm font-medium text-red-500">
                                <i data-lucide="alert-circle" class="h-4 w-4"></i> {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold">Phone Number <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-ink/50 dark:text-orange-50/50">+254</span>
                                <input type="tel" name="phone" required
                                       value="{{ old('phone') }}"
                                       placeholder="7XX XXX XXX"
                                       class="w-full rounded-xl border-0 glass py-3.5 pl-16 pr-4 text-sm font-medium placeholder:text-ink/40 focus:ring-2 focus:ring-brand-500 dark:placeholder:text-orange-50/30">
                            </div>
                            @error('phone')
                            <p class="mt-1.5 flex items-center gap-1 text-sm font-medium text-red-500">
                                <i data-lucide="alert-circle" class="h-4 w-4"></i> {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Special instructions --}}
                <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
                    <h2 class="mb-5 flex items-center gap-2 text-lg font-extrabold">
                        <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 text-white">
                            <i data-lucide="message-square" class="h-5 w-5"></i>
                        </span>
                        Special Instructions
                        <span class="ml-1 text-sm font-normal text-ink/40 dark:text-orange-50/40">(optional)</span>
                    </h2>
                    <textarea name="special_instructions" rows="3"
                              placeholder="Any allergies, extra spice, delivery notes..."
                              class="w-full rounded-xl border-0 glass py-3 px-4 text-sm font-medium placeholder:text-ink/40 focus:ring-2 focus:ring-brand-500 dark:placeholder:text-orange-50/30 resize-none">{{ old('special_instructions') }}</textarea>
                </div>

                {{-- Estimated delivery --}}
                <div data-aos="fade-up" class="flex items-center gap-4 rounded-2xl bg-brand-500/8 border border-brand-500/20 px-5 py-4">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 text-white">
                        <i data-lucide="clock" class="h-6 w-6"></i>
                    </span>
                    <div>
                        <p class="text-sm font-extrabold">Estimated Delivery Time</p>
                        <p class="text-xs text-ink/60 dark:text-orange-50/60">Your order will arrive in approximately <strong>45 minutes</strong> after confirmation.</p>
                    </div>
                </div>
            </div>

            {{-- ===== RIGHT: Order summary ===== --}}
            <div class="space-y-5">

                <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft sticky top-24">
                    <h2 class="mb-5 text-lg font-extrabold">Order Summary</h2>

                    {{-- Items --}}
                    <div class="space-y-3 mb-5">
                        @foreach($cartItems as $item)
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 shrink-0 overflow-hidden rounded-xl bg-gradient-to-br from-brand-100 to-brand-200 dark:from-brand-900/30 dark:to-brand-800/30">
                                @if($item->dish->primary_image)
                                    <img src="{{ asset('storage/' . $item->dish->primary_image) }}"
                                         alt="{{ $item->dish->name }}"
                                         class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-xl">🍽️</div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-bold">{{ $item->dish->name }}</p>
                                <p class="text-xs text-ink/55 dark:text-orange-50/55">x{{ $item->quantity }}</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">
                                KSh {{ number_format($item->dish->price * $item->quantity, 0) }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Totals --}}
                    <div class="space-y-2.5 border-t border-black/5 pt-4 dark:border-white/5">
                        <div class="flex justify-between text-sm">
                            <span class="text-ink/60 dark:text-orange-50/60">Subtotal</span>
                            <span class="font-semibold">KSh {{ number_format($subtotal, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-ink/60 dark:text-orange-50/60">Delivery Fee</span>
                            <span class="font-semibold">KSh {{ number_format($deliveryFee, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-ink/60 dark:text-orange-50/60">Tax (16%)</span>
                            <span class="font-semibold">KSh {{ number_format($tax, 0) }}</span>
                        </div>
                        <div class="flex justify-between border-t border-black/5 pt-3 dark:border-white/5">
                            <span class="font-extrabold">Total</span>
                            <span class="text-xl font-extrabold text-brand-600">KSh {{ number_format($total, 0) }}</span>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" :disabled="submitting"
                            class="group mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-6 py-4 text-base font-extrabold text-white shadow-glow transition hover:scale-[1.02] active:scale-95 disabled:opacity-70">
                        <span x-show="!submitting" class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="h-5 w-5"></i>
                            Place Order
                        </span>
                        <span x-show="submitting" class="flex items-center gap-2">
                            <i data-lucide="loader-2" class="h-5 w-5 animate-spin"></i>
                            Placing order...
                        </span>
                    </button>

                    <p class="mt-3 text-center text-xs text-ink/45 dark:text-orange-50/45">
                        By placing this order you agree to our terms of service.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection