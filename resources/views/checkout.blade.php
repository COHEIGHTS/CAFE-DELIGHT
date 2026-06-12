@extends('layouts.customer-layout')

@section('title', 'Checkout — Cafe Delight')
@php $activeNav = 'My Orders'; @endphp

@section('content')
<div class="mx-auto max-w-5xl"
     x-data="checkoutPage()"
     x-init="init()">

    {{-- Header --}}
    <div data-aos="fade-up" class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('cart.index') }}"
               class="grid h-9 w-9 place-items-center rounded-xl glass text-ink/60 transition hover:text-brand-600">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <h1 class="font-display text-3xl font-extrabold">Checkout</h1>
        </div>
        <p class="ml-12 text-sm text-ink/55 dark:text-orange-50/55">Almost there! Choose your delivery address.</p>
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

    {{-- Flash errors --}}
    @if($errors->any())
        <div data-aos="fade-in" class="mb-6 rounded-2xl bg-red-500/10 p-4 text-sm text-red-600 dark:text-red-400">
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}"
          x-on:submit="return handleSubmit($event)">
        @csrf

        {{-- Hidden: which mode the user chose --}}
        <input type="hidden" name="address_mode" :value="mode">

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- ═══════════════════════════════════════════════════════
                 LEFT: Delivery section
            ════════════════════════════════════════════════════════ --}}
            <div class="space-y-5 lg:col-span-2">

                {{-- ── Delivery Address card ── --}}
                <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
                    <div class="mb-5 flex items-center justify-between">
                        <h2 class="flex items-center gap-2 text-lg font-extrabold">
                            <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 text-white">
                                <i data-lucide="map-pin" class="h-5 w-5"></i>
                            </span>
                            Delivery Address
                        </h2>
                        @if($addresses->isNotEmpty())
                        <div class="flex rounded-xl glass overflow-hidden text-xs font-bold">
                            <button type="button"
                                    @click="mode = 'saved'"
                                    :class="mode === 'saved'
                                        ? 'bg-brand-500 text-white'
                                        : 'text-ink/60 dark:text-orange-50/60 hover:bg-brand-500/10'"
                                    class="px-3 py-2 transition">
                                Saved
                            </button>
                            <button type="button"
                                    @click="mode = 'manual'"
                                    :class="mode === 'manual'
                                        ? 'bg-brand-500 text-white'
                                        : 'text-ink/60 dark:text-orange-50/60 hover:bg-brand-500/10'"
                                    class="px-3 py-2 transition">
                                New / One-time
                            </button>
                        </div>
                        @endif
                    </div>

                    @if($addresses->isNotEmpty())
                    <div x-show="mode === 'saved'" x-transition>
                        <div class="space-y-3">
                            @foreach($addresses as $addr)
                            <label
                                :class="selectedId === {{ $addr->id }}
                                    ? 'ring-2 ring-brand-500 bg-brand-500/5'
                                    : 'ring-1 ring-black/5 dark:ring-white/8 hover:ring-brand-500/40'"
                                class="flex cursor-pointer items-start gap-4 rounded-2xl p-4 glass transition">

                                <input type="radio"
                                       name="address_id"
                                       value="{{ $addr->id }}"
                                       x-model.number="selectedId"
                                       class="mt-1 accent-brand-500 shrink-0">

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="grid h-6 w-6 place-items-center rounded-lg
                                            {{ $addr->is_default ? 'bg-brand-500 text-white' : 'bg-brand-500/10 text-brand-500' }}">
                                            @if($addr->label === 'Home')
                                                <i data-lucide="home" class="h-3.5 w-3.5"></i>
                                            @elseif($addr->label === 'Office')
                                                <i data-lucide="briefcase" class="h-3.5 w-3.5"></i>
                                            @elseif($addr->label === 'School')
                                                <i data-lucide="book-open" class="h-3.5 w-3.5"></i>
                                            @else
                                                <i data-lucide="map-pin" class="h-3.5 w-3.5"></i>
                                            @endif
                                        </span>
                                        <span class="text-sm font-extrabold">{{ $addr->label }}</span>
                                        @if($addr->is_default)
                                            <span class="rounded-full bg-brand-500/15 px-2 py-0.5 text-xs font-bold text-brand-600">Default</span>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-sm font-semibold text-ink/80 dark:text-orange-50/80">
                                        {{ $addr->recipient_name }}
                                        <span class="font-normal text-ink/50 dark:text-orange-50/50">&bull; {{ $addr->phone }}</span>
                                    </p>
                                    <p class="text-sm text-ink/55 dark:text-orange-50/55">
                                        {{ $addr->address_line1 }}@if($addr->address_line2), {{ $addr->address_line2 }}@endif,
                                        {{ $addr->city }}@if($addr->state), {{ $addr->state }}@endif
                                    </p>
                                    @if($addr->delivery_instructions)
                                        <p class="mt-0.5 text-xs italic text-ink/40 dark:text-orange-50/40">
                                            <i data-lucide="info" class="mr-1 inline h-3 w-3"></i>{{ $addr->delivery_instructions }}
                                        </p>
                                    @endif
                                </div>

                                <div x-show="selectedId === {{ $addr->id }}"
                                     x-transition.scale
                                     class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-brand-500 text-white">
                                    <i data-lucide="check" class="h-3.5 w-3.5"></i>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        <a href="{{ route('addresses.create') }}?redirect=checkout"
                           class="mt-4 flex items-center gap-2 rounded-2xl border-2 border-dashed border-brand-500/30 px-4 py-3.5 text-sm font-bold text-brand-500 transition hover:border-brand-500/60 hover:bg-brand-500/5">
                            <i data-lucide="plus-circle" class="h-4 w-4"></i>
                            Add a new saved address
                        </a>

                        <p x-show="showErrors && mode === 'saved' && !selectedId"
                           class="mt-2 flex items-center gap-1 text-sm font-semibold text-red-500">
                            <i data-lucide="alert-circle" class="h-4 w-4"></i>
                            Please select a delivery address.
                        </p>
                    </div>
                    @endif

                    <div x-show="mode === 'manual'" x-transition
                         @if($addresses->isEmpty()) style="display:block" @endif>

                        @if($addresses->isNotEmpty())
                        <p class="mb-4 text-xs text-ink/50 dark:text-orange-50/50">
                            This address will only be used for this order — it won't be saved to your account.
                            <a href="{{ route('addresses.create') }}?redirect=checkout"
                               class="ml-1 font-bold text-brand-500 hover:underline">Save it permanently →</a>
                        </p>
                        @endif

                        <div class="space-y-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-bold">
                                    Full Address <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i data-lucide="home" class="pointer-events-none absolute left-4 top-4 h-5 w-5 text-ink/40 dark:text-orange-50/40"></i>
                                    <textarea name="delivery_address"
                                              rows="3"
                                              :required="mode === 'manual'"
                                              placeholder="e.g. House No. 12, Nakuru Town, near KFC, Nakuru County"
                                              class="w-full rounded-xl border-0 glass py-3 pl-12 pr-4 text-sm font-medium placeholder:text-ink/40 focus:ring-2 focus:ring-brand-500 dark:placeholder:text-orange-50/30 resize-none">{{ old('delivery_address') }}</textarea>
                                </div>
                                @error('delivery_address')
                                <p class="mt-1.5 flex items-center gap-1 text-sm font-medium text-red-500">
                                    <i data-lucide="alert-circle" class="h-4 w-4"></i> {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-bold">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-ink/50 dark:text-orange-50/50">+254</span>
                                    <input type="tel"
                                           name="phone"
                                           :required="mode === 'manual'"
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
                </div>

                {{-- ── Special instructions ── --}}
                <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
                    <h2 class="mb-5 flex items-center gap-2 text-lg font-extrabold">
                        <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 text-white">
                            <i data-lucide="message-square" class="h-5 w-5"></i>
                        </span>
                        Special Instructions
                        <span class="ml-1 text-sm font-normal text-ink/40 dark:text-orange-50/40">(optional)</span>
                    </h2>
                    <textarea name="special_instructions"
                              rows="3"
                              placeholder="Any allergies, extra spice, delivery notes..."
                              class="w-full rounded-xl border-0 glass py-3 px-4 text-sm font-medium placeholder:text-ink/40 focus:ring-2 focus:ring-brand-500 dark:placeholder:text-orange-50/30 resize-none">{{ old('special_instructions') }}</textarea>
                </div>

                {{-- ── Payment Method ── --}}
                <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
                    <h2 class="mb-5 flex items-center gap-2 text-lg font-extrabold">
                        <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 text-white">
                            <i data-lucide="credit-card" class="h-5 w-5"></i>
                        </span>
                        Payment Method
                    </h2>
                    <div class="space-y-3">
                        <label class="flex cursor-pointer items-start gap-4 rounded-2xl p-4 glass transition ring-1 ring-black/5 dark:ring-white/8 hover:ring-brand-500/40">
                            <input type="radio"
                                   name="payment_method"
                                   value="mpesa"
                                   checked
                                   class="mt-1 accent-brand-500 shrink-0">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-extrabold">M-Pesa</span>
                                    <span class="rounded-full bg-brand-500/15 px-2 py-0.5 text-xs font-bold text-brand-600">Recommended</span>
                                </div>
                                <p class="mt-1 text-xs text-ink/55 dark:text-orange-50/55">
                                    Pay using the phone number from your delivery address. You'll receive an STK prompt to confirm payment.
                                </p>
                            </div>
                        </label>
                        <label class="flex cursor-pointer items-start gap-4 rounded-2xl p-4 glass transition ring-1 ring-black/5 dark:ring-white/8 hover:ring-brand-500/40">
                            <input type="radio"
                                   name="payment_method"
                                   value="cash_on_delivery"
                                   class="mt-1 accent-brand-500 shrink-0">
                            <div class="flex-1">
                                <span class="text-sm font-extrabold">Cash on Delivery</span>
                                <p class="mt-1 text-xs text-ink/55 dark:text-orange-50/55">
                                    Pay with cash when your order arrives. No additional fees.
                                </p>
                            </div>
                        </label>
                    </div>
                    @error('payment_method')
                    <p class="mt-2 flex items-center gap-1 text-sm font-medium text-red-500">
                        <i data-lucide="alert-circle" class="h-4 w-4"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- ── Estimated delivery info ── --}}
                <div data-aos="fade-up" class="flex items-center gap-4 rounded-2xl bg-brand-500/8 border border-brand-500/20 px-5 py-4">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 text-white">
                        <i data-lucide="clock" class="h-6 w-6"></i>
                    </span>
                    <div>
                        <p class="text-sm font-extrabold">Estimated Delivery Time</p>
                        <p class="text-xs text-ink/60 dark:text-orange-50/60">
                            Your order will arrive in approximately <strong>45 minutes</strong> after confirmation.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════
                 RIGHT: Order summary
            ════════════════════════════════════════════════════════ --}}
            <div class="space-y-5">
                <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft sticky top-24">
                    <h2 class="mb-5 text-lg font-extrabold">Order Summary</h2>

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
                            <span class="text-ink/60 dark:text-orange-50/60">Tax ({{ number_format($tax / $subtotal * 100, 0) }}%)</span>
                            <span class="font-semibold">KSh {{ number_format($tax, 0) }}</span>
                        </div>
                        <div class="flex justify-between border-t border-black/5 pt-3 dark:border-white/5">
                            <span class="font-extrabold">Total</span>
                            <span class="text-xl font-extrabold text-brand-600">KSh {{ number_format($total, 0) }}</span>
                        </div>
                    </div>

                    <template x-if="mode === 'saved' && selectedAddress">
                        <div class="mt-4 rounded-2xl bg-brand-500/5 border border-brand-500/20 px-4 py-3">
                            <p class="text-xs font-bold text-brand-600 mb-1">
                                <i data-lucide="map-pin" class="mr-1 inline h-3 w-3"></i>
                                Delivering to
                            </p>
                            <p class="text-xs font-semibold text-ink/80 dark:text-orange-50/80" x-text="selectedAddress.recipient_name"></p>
                            <p class="text-xs text-ink/55 dark:text-orange-50/55" x-text="selectedAddress.oneLine"></p>
                        </div>
                    </template>

                    <button type="submit"
                            :disabled="submitting"
                            class="group mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-6 py-4 text-base font-extrabold text-white shadow-glow transition hover:scale-[1.02] active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed">
                        <template x-if="!submitting">
                            <span class="flex items-center gap-2">
                                <i data-lucide="check-circle" class="h-5 w-5"></i>
                                Place Order
                            </span>
                        </template>
                        <template x-if="submitting">
                            <span class="flex items-center gap-2">
                                <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                </svg>
                                Placing order...
                            </span>
                        </template>
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

@php
    $addressesJson = $addresses->map(function ($a) {
        return [
            'id'             => $a->id,
            'label'          => $a->label,
            'recipient_name' => $a->recipient_name,
            'phone'          => $a->phone,
            'oneLine'        => $a->toOneLine(),
            'is_default'     => (bool) $a->is_default,
        ];
    })->values()->toJson();
@endphp

@push('scripts')
<script>
function checkoutPage() {
    const savedAddresses = {!! $addressesJson !!};
    const hasSaved       = savedAddresses.length > 0;
    const preselectedId  = {{ $selectedAddressId ?? 'null' }};

    return {
        mode:        hasSaved ? 'saved' : 'manual',
        selectedId:  preselectedId,
        submitting:  false,
        showErrors:  false,

        get selectedAddress() {
            if (!this.selectedId) return null;
            return savedAddresses.find(a => a.id === this.selectedId) ?? null;
        },

        init() {
            this.$watch('mode', () => {
                this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
            });
            this.$watch('selectedId', () => {
                this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
            });
        },

        handleSubmit(event) {
            this.showErrors = true;
            if (this.mode === 'saved' && !this.selectedId) {
                event.preventDefault();
                document.querySelector('[name="address_id"]')
                    ?.closest('.rounded-3xl')
                    ?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
            this.submitting = true;
            return true;
        },
    };
}
</script>
@endpush