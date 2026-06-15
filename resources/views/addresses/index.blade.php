@extends('layouts.customer-layout')

@section('title', 'My Addresses — Cafe Delight')
@php $activeNav = 'Addresses'; @endphp

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between" data-aos="fade-down">
        <div>
            <h1 class="font-display text-3xl font-extrabold">
                My <span class="text-gradient">Addresses</span>
            </h1>
            <p class="mt-1 text-sm text-ink/50 dark:text-orange-50/50">
                Save your delivery addresses for faster checkout.
            </p>
        </div>
        <a href="{{ route('addresses.create') }}"
           class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-2.5 text-sm font-bold text-white shadow-glow transition hover:scale-105">
            <i data-lucide="plus" class="h-4 w-4"></i>
            Add Address
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl bg-emerald-500/10 px-5 py-4 text-emerald-600 dark:text-emerald-400" data-aos="fade-in">
            <i data-lucide="check-circle" class="h-5 w-5 shrink-0"></i>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Empty state --}}
    @if($addresses->isEmpty())
        <div class="rounded-3xl glass p-14 text-center" data-aos="fade-up">
            <div class="mx-auto mb-4 grid h-16 w-16 place-items-center rounded-2xl bg-brand-500/10">
                <i data-lucide="map-pin" class="h-8 w-8 text-brand-500"></i>
            </div>
            <h3 class="font-display text-xl font-bold">No saved addresses yet</h3>
            <p class="mt-1 text-sm text-ink/50 dark:text-orange-50/50">
                Add an address to speed up your checkout.
            </p>
            <a href="{{ route('addresses.create') }}"
               class="mt-5 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-6 py-3 text-sm font-bold text-white shadow-glow transition hover:scale-105">
                <i data-lucide="plus" class="h-4 w-4"></i> Add my first address
            </a>
        </div>

    @else
        <div class="space-y-4">
            @foreach($addresses as $address)
                <div class="relative rounded-3xl glass p-5 transition hover:shadow-soft"
                     data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">

                    {{-- Default ribbon --}}
                    @if($address->is_default)
                        <span class="absolute right-5 top-5 flex items-center gap-1 rounded-full bg-brand-500/15 px-3 py-1 text-xs font-bold text-brand-600">
                            <i data-lucide="star" class="h-3 w-3 fill-brand-500 text-brand-500"></i>
                            Default
                        </span>
                    @endif

                    <div class="flex items-start gap-4">
                        {{-- Icon --}}
                        <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl
                            {{ $address->is_default ? 'bg-gradient-to-br from-brand-500 to-brand-700 shadow-glow' : 'bg-brand-500/10' }}">
                            @if($address->label === 'Home')
                                <i data-lucide="home" class="h-5 w-5 {{ $address->is_default ? 'text-white' : 'text-brand-500' }}"></i>
                            @elseif($address->label === 'Office')
                                <i data-lucide="briefcase" class="h-5 w-5 {{ $address->is_default ? 'text-white' : 'text-brand-500' }}"></i>
                            @elseif($address->label === 'School')
                                <i data-lucide="book-open" class="h-5 w-5 {{ $address->is_default ? 'text-white' : 'text-brand-500' }}"></i>
                            @else
                                <i data-lucide="map-pin" class="h-5 w-5 {{ $address->is_default ? 'text-white' : 'text-brand-500' }}"></i>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="min-w-0 flex-1 pr-20">
                            <p class="text-sm font-extrabold">{{ $address->label }}</p>
                            <p class="mt-0.5 text-sm font-semibold text-ink/80 dark:text-orange-50/80">
                                {{ $address->recipient_name }}
                                <span class="font-normal text-ink/50">&bull; {{ $address->phone }}</span>
                            </p>
                            <p class="mt-0.5 text-sm leading-relaxed text-ink/60 dark:text-orange-50/60">
                                {{ $address->address_line1 }}@if($address->address_line2), {{ $address->address_line2 }}@endif<br>
                                {{ $address->city }}@if($address->state), {{ $address->state }}@endif
                                @if($address->postal_code) &mdash; {{ $address->postal_code }}@endif
                            </p>
                            @if($address->delivery_instructions)
                                <p class="mt-1 text-xs italic text-ink/45 dark:text-orange-50/40">
                                    <i data-lucide="info" class="mr-1 inline h-3 w-3"></i>{{ $address->delivery_instructions }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-black/5 pt-4 dark:border-white/5">

                        @unless($address->is_default)
                            <form action="{{ route('addresses.default', $address) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="flex items-center gap-1.5 rounded-xl px-3 py-2 text-xs font-bold text-brand-600 transition hover:bg-brand-500/10">
                                    <i data-lucide="star" class="h-3.5 w-3.5"></i> Set as default
                                </button>
                            </form>
                        @endunless

                        <a href="{{ route('addresses.edit', $address) }}"
                           class="flex items-center gap-1.5 rounded-xl px-3 py-2 text-xs font-bold text-ink/60 transition hover:bg-black/5 dark:text-orange-50/60 dark:hover:bg-white/5">
                            <i data-lucide="pencil" class="h-3.5 w-3.5"></i> Edit
                        </a>

                        <form action="{{ route('addresses.destroy', $address) }}" method="POST"
                              class="ml-auto"
                              onsubmit="return confirm('Remove this address?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="flex items-center gap-1.5 rounded-xl px-3 py-2 text-xs font-bold text-red-500 transition hover:bg-red-500/10">
                                <i data-lucide="trash-2" class="h-3.5 w-3.5"></i> Remove
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection