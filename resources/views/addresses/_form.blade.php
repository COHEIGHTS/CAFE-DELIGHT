{{--
    Shared address form.
    Required variables:
        $formAction  – form action URL
        $formMethod  – 'POST' or 'PUT'
        $btnLabel    – submit button text
        $address     – model instance (or null for create)
--}}
@extends('layouts.customer-layout')

@section('title', isset($address) ? 'Edit Address' : 'Add Address')
@php $activeNav = 'Addresses'; @endphp

@section('content')
<div class="mx-auto max-w-xl" data-aos="fade-up">

    {{-- Back --}}
    <a href="{{ route('addresses.index') }}"
       class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-ink/50 transition hover:text-brand-500 dark:text-orange-50/50">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Back to Addresses
    </a>

    <div class="rounded-3xl glass p-6 sm:p-8 shadow-soft">

        <h1 class="font-display text-2xl font-extrabold">
            @isset($address)
                Edit <span class="text-gradient">Address</span>
            @else
                New <span class="text-gradient">Address</span>
            @endisset
        </h1>
        <p class="mt-1 text-sm text-ink/50 dark:text-orange-50/50">
            Fill in your delivery details below.
        </p>

        <form action="{{ $formAction }}" method="POST" class="mt-6 space-y-5">
            @csrf
            @if($formMethod === 'PUT') @method('PUT') @endif

            {{-- Validation errors --}}
            @if($errors->any())
                <div class="rounded-2xl bg-red-500/10 p-4 text-sm text-red-600 dark:text-red-400">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            {{-- Label + Recipient --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-ink/60 dark:text-orange-50/50">
                        Label <span class="text-red-500">*</span>
                    </label>
                    <select name="label"
                            class="w-full rounded-xl border-0 glass py-3 px-4 text-sm font-semibold focus:ring-2 focus:ring-brand-500 dark:bg-transparent">
                        @foreach(['Home','Office','School','Other'] as $opt)
                            <option value="{{ $opt }}"
                                {{ old('label', $address->label ?? 'Home') === $opt ? 'selected' : '' }}>
                                {{ $opt }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-ink/60 dark:text-orange-50/50">
                        Recipient Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="recipient_name"
                           value="{{ old('recipient_name', $address->recipient_name ?? '') }}"
                           placeholder="Full name"
                           class="w-full rounded-xl border-0 glass py-3 px-4 text-sm focus:ring-2 focus:ring-brand-500 dark:bg-transparent dark:placeholder:text-orange-50/30">
                    @error('recipient_name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Phone --}}
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-ink/60 dark:text-orange-50/50">
                    Phone Number <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-ink/50">+254</span>
                    <input type="tel" name="phone"
                           value="{{ old('phone', $address->phone ?? '') }}"
                           placeholder="7XX XXX XXX"
                           class="w-full rounded-xl border-0 glass py-3 pl-16 pr-4 text-sm focus:ring-2 focus:ring-brand-500 dark:bg-transparent dark:placeholder:text-orange-50/30">
                </div>
                @error('phone')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Address line 1 --}}
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-ink/60 dark:text-orange-50/50">
                    Address Line 1 <span class="text-red-500">*</span>
                </label>
                <input type="text" name="address_line1"
                       value="{{ old('address_line1', $address->address_line1 ?? '') }}"
                       placeholder="Street, building, house number"
                       class="w-full rounded-xl border-0 glass py-3 px-4 text-sm focus:ring-2 focus:ring-brand-500 dark:bg-transparent dark:placeholder:text-orange-50/30">
                @error('address_line1')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Address line 2 --}}
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-ink/60 dark:text-orange-50/50">
                    Address Line 2
                    <span class="ml-1 text-xs font-normal normal-case text-ink/35">(optional)</span>
                </label>
                <input type="text" name="address_line2"
                       value="{{ old('address_line2', $address->address_line2 ?? '') }}"
                       placeholder="Apartment, suite, landmark"
                       class="w-full rounded-xl border-0 glass py-3 px-4 text-sm focus:ring-2 focus:ring-brand-500 dark:bg-transparent dark:placeholder:text-orange-50/30">
            </div>

            {{-- City / County --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-ink/60 dark:text-orange-50/50">
                        City <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="city"
                           value="{{ old('city', $address->city ?? '') }}"
                           placeholder="e.g. Nakuru"
                           class="w-full rounded-xl border-0 glass py-3 px-4 text-sm focus:ring-2 focus:ring-brand-500 dark:bg-transparent dark:placeholder:text-orange-50/30">
                    @error('city')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-ink/60 dark:text-orange-50/50">
                        County / State
                    </label>
                    <input type="text" name="state"
                           value="{{ old('state', $address->state ?? '') }}"
                           placeholder="e.g. Nakuru County"
                           class="w-full rounded-xl border-0 glass py-3 px-4 text-sm focus:ring-2 focus:ring-brand-500 dark:bg-transparent dark:placeholder:text-orange-50/30">
                </div>
            </div>

            {{-- Postal / Country --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-ink/60 dark:text-orange-50/50">
                        Postal Code
                    </label>
                    <input type="text" name="postal_code"
                           value="{{ old('postal_code', $address->postal_code ?? '') }}"
                           placeholder="20100"
                           class="w-full rounded-xl border-0 glass py-3 px-4 text-sm focus:ring-2 focus:ring-brand-500 dark:bg-transparent dark:placeholder:text-orange-50/30">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-ink/60 dark:text-orange-50/50">
                        Country
                    </label>
                    <input type="text" name="country"
                           value="{{ old('country', $address->country ?? 'Kenya') }}"
                           class="w-full rounded-xl border-0 glass py-3 px-4 text-sm focus:ring-2 focus:ring-brand-500 dark:bg-transparent">
                </div>
            </div>

            {{-- Delivery instructions --}}
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-ink/60 dark:text-orange-50/50">
                    Delivery Instructions
                    <span class="ml-1 text-xs font-normal normal-case text-ink/35">(optional)</span>
                </label>
                <textarea name="delivery_instructions" rows="2"
                          placeholder="Gate code, which floor, nearest landmark..."
                          class="w-full rounded-xl border-0 glass py-3 px-4 text-sm focus:ring-2 focus:ring-brand-500 dark:bg-transparent dark:placeholder:text-orange-50/30 resize-none">{{ old('delivery_instructions', $address->delivery_instructions ?? '') }}</textarea>
            </div>

            {{-- Set as default --}}
            <label class="flex cursor-pointer items-center gap-3 rounded-2xl glass p-4 transition hover:bg-brand-500/5">
                <input type="checkbox" name="is_default" value="1"
                       {{ old('is_default', $address->is_default ?? false) ? 'checked' : '' }}
                       class="h-5 w-5 rounded accent-brand-500">
                <div>
                    <p class="text-sm font-bold">Set as default address</p>
                    <p class="text-xs text-ink/50 dark:text-orange-50/50">
                        This address will be pre-selected at checkout.
                    </p>
                </div>
            </label>

            {{-- Submit --}}
            <button type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 py-3.5 text-sm font-bold text-white shadow-glow transition hover:scale-[1.02] active:scale-100">
                <i data-lucide="save" class="h-4 w-4"></i>
                {{ $btnLabel }}
            </button>
        </form>
    </div>
</div>
@endsection