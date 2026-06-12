@extends('layouts.admin-layout')

@section('title', 'Payments — Admin · Cafe Delight')

@php $activeNav = 'Payments'; @endphp

@section('content')
<div class="space-y-6">

    {{-- ===== Header ===== --}}
    <div data-aos="fade-up" class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-extrabold">Payments</h1>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Track and manage all payments</p>
        </div>
    </div>

    {{-- ===== Summary Cards ===== --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div data-aos="fade-up" data-aos-delay="0" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white">
                    <i data-lucide="banknote" class="h-6 w-6"></i>
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">KSh {{ number_format($totalRevenue, 0) }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Total Revenue</p>
        </div>

        <div data-aos="fade-up" data-aos-delay="80" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-amber-500 to-amber-700 text-white">
                    <i data-lucide="clock" class="h-6 w-6"></i>
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">KSh {{ number_format($pendingRevenue, 0) }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Pending Revenue</p>
        </div>

        <div data-aos="fade-up" data-aos-delay="160" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 text-white">
                    <i data-lucide="check-circle" class="h-6 w-6"></i>
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">{{ $paidPayments }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Paid Payments</p>
        </div>

        <div data-aos="fade-up" data-aos-delay="240" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 text-white">
                    <i data-lucide="hourglass" class="h-6 w-6"></i>
                </span>
                @if($awaitingApproval > 0)
                <span class="text-xs font-bold text-amber-600">{{ $awaitingApproval }} awaiting</span>
                @endif
            </div>
            <p class="mt-4 text-2xl font-extrabold">{{ $pendingPayments }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Pending Payments</p>
        </div>
    </div>

    {{-- ===== Filters ===== --}}
    <div data-aos="fade-up" class="rounded-2xl glass p-4 shadow-soft">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by order ID or customer..."
                       class="w-full rounded-xl border border-black/10 bg-white/50 px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
            </div>
            <select name="status" class="rounded-xl border border-black/10 bg-white/50 px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="awaiting_approval" {{ $status === 'awaiting_approval' ? 'selected' : '' }}>Awaiting Approval</option>
                <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <select name="payment_method" class="rounded-xl border border-black/10 bg-white/50 px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                <option value="all" {{ $paymentMethod === 'all' ? 'selected' : '' }}>All Methods</option>
                <option value="mpesa" {{ $paymentMethod === 'mpesa' ? 'selected' : '' }}>M-Pesa</option>
                <option value="cash_on_delivery" {{ $paymentMethod === 'cash_on_delivery' ? 'selected' : '' }}>Cash on Delivery</option>
            </select>
            <button type="submit" class="flex items-center gap-2 rounded-xl bg-brand-600 text-white px-4 py-2.5 text-sm font-bold transition hover:scale-105">
                <i data-lucide="filter" class="h-4 w-4"></i> Filter
            </button>
            <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-2 rounded-xl glass px-4 py-2.5 text-sm font-bold transition hover:bg-brand-500/10">
                <i data-lucide="x" class="h-4 w-4"></i> Clear
            </a>
        </form>
    </div>

    {{-- ===== Payments Table ===== --}}
    <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-lg font-bold">All Payments</h2>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">{{ $payments->total() }} results</p>
        </div>

        @if($payments->isEmpty())
        <div class="py-10 text-center">
            <i data-lucide="credit-card" class="mx-auto h-10 w-10 text-ink/20 dark:text-orange-50/20"></i>
            <p class="mt-3 text-sm font-semibold text-ink/50 dark:text-orange-50/50">No payments found</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs uppercase tracking-wider text-ink/45 dark:text-orange-50/45">
                        <th class="pb-3 font-bold">Order ID</th>
                        <th class="pb-3 font-bold">Customer</th>
                        <th class="pb-3 font-bold">Amount</th>
                        <th class="pb-3 font-bold">Method</th>
                        <th class="pb-3 font-bold">Status</th>
                        <th class="pb-3 font-bold">Date</th>
                        <th class="pb-3 font-bold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr class="border-t border-black/5 dark:border-white/5">
                        <td class="py-3.5 font-bold">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-3.5">
                            <div class="flex items-center gap-2">
                                <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-gradient-to-br from-brand-400 to-brand-700 text-xs font-bold text-white">
                                    {{ strtoupper(substr($payment->user->name, 0, 1)) }}
                                </span>
                                <span class="font-semibold">{{ $payment->user->name }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 font-bold text-brand-600">KSh {{ number_format($payment->total, 0) }}</td>
                        <td class="py-3.5 text-ink/65 dark:text-orange-50/65">
                            {{ $payment->paymentMethodLabel() }}
                        </td>
                        <td class="py-3.5">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold {{ $payment->paymentStatusClass() }}">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                {{ $payment->paymentStatusLabel() }}
                            </span>
                        </td>
                        <td class="py-3.5 text-ink/65 dark:text-orange-50/65">
                            {{ $payment->created_at->format('M d, Y - H:i') }}
                        </td>
                        <td class="py-3.5">
                            @if($payment->payment_status === 'awaiting_approval' && $payment->payment_method === 'cash_on_delivery')
                            <form method="POST" action="{{ route('admin.order.approvePayment', $payment) }}">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('Approve payment of KSh {{ number_format($payment->total, 0) }}?')"
                                        class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-emerald-600 transition hover:bg-emerald-500/10">
                                    Approve
                                </button>
                            </form>
                            @else
                            <a href="{{ route('admin.order.index', ['search' => $payment->id]) }}"
                               class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-brand-600 transition hover:bg-brand-500/10">
                                View
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($payments->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <p class="text-sm text-ink/55 dark:text-orange-50/55">
                Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} results
            </p>
            <div class="flex items-center gap-2">
                @if($payments->onFirstPage())
                <span class="rounded-lg px-3 py-2 text-sm font-semibold text-ink/40 dark:text-orange-50/40">Previous</span>
                @else
                <a href="{{ $payments->previousPageUrl() }}" class="rounded-lg glass px-3 py-2 text-sm font-semibold transition hover:bg-brand-500/10 hover:text-brand-600">
                    Previous
                </a>
                @endif

                @foreach($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)
                @if($page == $payments->currentPage())
                <span class="rounded-lg bg-brand-600 px-3 py-2 text-sm font-bold text-white">{{ $page }}</span>
                @else
                <a href="{{ $url }}" class="rounded-lg glass px-3 py-2 text-sm font-semibold transition hover:bg-brand-500/10 hover:text-brand-600">
                    {{ $page }}
                </a>
                @endif
                @endforeach

                @if($payments->hasMorePages())
                <a href="{{ $payments->nextPageUrl() }}" class="rounded-lg glass px-3 py-2 text-sm font-semibold transition hover:bg-brand-500/10 hover:text-brand-600">
                    Next
                </a>
                @else
                <span class="rounded-lg px-3 py-2 text-sm font-semibold text-ink/40 dark:text-orange-50/40">Next</span>
                @endif
            </div>
        </div>
        @endif
        @endif
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
