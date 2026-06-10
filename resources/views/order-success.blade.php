@extends('layouts.customer-layout')

@section('title', 'Order Placed — Cafe Delight')
@php $activeNav = 'My Orders'; @endphp

@section('content')
<div class="mx-auto max-w-lg" x-data>

    <div data-aos="fade-up" class="rounded-3xl glass p-8 shadow-premium text-center">

        {{-- Success animation --}}
        <div class="mx-auto mb-6 grid h-24 w-24 place-items-center rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-glow text-white"
             style="box-shadow: 0 0 40px -10px rgba(16,185,129,0.6)">
            <i data-lucide="check" class="h-12 w-12"></i>
        </div>

        <h1 class="font-display text-3xl font-extrabold">Order Placed! 🎉</h1>
        <p class="mt-3 text-ink/60 dark:text-orange-50/60 text-sm leading-relaxed">
            Your order has been received and is being confirmed by our team.<br>
            Sit tight — delicious food is on its way!
        </p>

        {{-- Estimated time --}}
        <div class="mt-6 flex items-center justify-center gap-3 rounded-2xl bg-brand-500/10 border border-brand-500/20 px-5 py-4">
            <i data-lucide="clock" class="h-6 w-6 text-brand-600 shrink-0"></i>
            <div class="text-left">
                <p class="text-xs font-semibold text-ink/55 dark:text-orange-50/55">Estimated Delivery</p>
                <p class="text-sm font-extrabold text-brand-600">~45 minutes</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-8 flex flex-col gap-3">
            <a href="{{ route('order.index') }}"
               class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-6 py-3.5 text-sm font-extrabold text-white shadow-glow transition hover:scale-105">
                <i data-lucide="shopping-bag" class="h-5 w-5"></i> Track My Order
            </a>
            <a href="{{ route('menu.index') }}"
               class="flex items-center justify-center gap-2 rounded-xl glass px-6 py-3.5 text-sm font-bold transition hover:bg-brand-500/10 hover:text-brand-600">
                <i data-lucide="utensils" class="h-5 w-5"></i> Back to Menu
            </a>
        </div>
    </div>

</div>
@endsection