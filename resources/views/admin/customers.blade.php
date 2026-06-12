@extends('layouts.admin-layout')

@section('title', 'Customers — Admin · Cafe Delight')

@php $activeNav = 'Customers'; @endphp

@section('content')
<div class="space-y-6">

    {{-- ===== Header ===== --}}
    <div data-aos="fade-up" class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-extrabold">Customers</h1>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Manage customer accounts and view order history</p>
        </div>
    </div>

    {{-- ===== Summary Cards ===== --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div data-aos="fade-up" data-aos-delay="0" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white">
                    <i data-lucide="users" class="h-6 w-6"></i>
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">{{ $totalCustomers }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Total Customers</p>
        </div>

        <div data-aos="fade-up" data-aos-delay="80" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white">
                    <i data-lucide="user-check" class="h-6 w-6"></i>
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">{{ $activeCustomers }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Active (30 days)</p>
        </div>

        <div data-aos="fade-up" data-aos-delay="160" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 text-white">
                    <i data-lucide="banknote" class="h-6 w-6"></i>
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">KSh {{ number_format($totalRevenue, 0) }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Total Revenue</p>
        </div>
    </div>

    {{-- ===== Filters ===== --}}
    <div data-aos="fade-up" class="rounded-2xl glass p-4 shadow-soft">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or email..."
                       class="w-full rounded-xl border border-black/10 bg-white/50 px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
            </div>
            <select name="sort_by" class="rounded-xl border border-black/10 bg-white/50 px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Date Joined</option>
                <option value="name" {{ $sortBy === 'name' ? 'selected' : '' }}>Name</option>
                <option value="email" {{ $sortBy === 'email' ? 'selected' : '' }}>Email</option>
            </select>
            <select name="sort_order" class="rounded-xl border border-black/10 bg-white/50 px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Descending</option>
                <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Ascending</option>
            </select>
            <button type="submit" class="flex items-center gap-2 rounded-xl bg-brand-600 text-white px-4 py-2.5 text-sm font-bold transition hover:scale-105">
                <i data-lucide="filter" class="h-4 w-4"></i> Filter
            </button>
            <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-2 rounded-xl glass px-4 py-2.5 text-sm font-bold transition hover:bg-brand-500/10">
                <i data-lucide="x" class="h-4 w-4"></i> Clear
            </a>
        </form>
    </div>

    {{-- ===== Customers Table ===== --}}
    <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-lg font-bold">All Customers</h2>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">{{ $customers->total() }} results</p>
        </div>

        @if($customers->isEmpty())
        <div class="py-10 text-center">
            <i data-lucide="users" class="mx-auto h-10 w-10 text-ink/20 dark:text-orange-50/20"></i>
            <p class="mt-3 text-sm font-semibold text-ink/50 dark:text-orange-50/50">No customers found</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs uppercase tracking-wider text-ink/45 dark:text-orange-50/45">
                        <th class="pb-3 font-bold">Customer</th>
                        <th class="pb-3 font-bold">Email</th>
                        <th class="pb-3 font-bold">Orders</th>
                        <th class="pb-3 font-bold">Total Spent</th>
                        <th class="pb-3 font-bold">Avg Order</th>
                        <th class="pb-3 font-bold">Joined</th>
                        <th class="pb-3 font-bold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr class="border-t border-black/5 dark:border-white/5">
                        <td class="py-3.5">
                            <div class="flex items-center gap-2">
                                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-gradient-to-br from-brand-400 to-brand-700 text-sm font-bold text-white">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </span>
                                <span class="font-semibold">{{ $customer->name }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 text-ink/65 dark:text-orange-50/65">{{ $customer->email }}</td>
                        <td class="py-3.5 font-semibold">{{ $customer->total_orders }}</td>
                        <td class="py-3.5 font-bold text-brand-600">KSh {{ number_format($customer->total_spent, 0) }}</td>
                        <td class="py-3.5 text-ink/65 dark:text-orange-50/65">KSh {{ number_format($customer->avg_order_value, 0) }}</td>
                        <td class="py-3.5 text-ink/65 dark:text-orange-50/65">
                            {{ $customer->created_at->format('M d, Y') }}
                        </td>
                        <td class="py-3.5">
                            <a href="{{ route('admin.customers.show', $customer) }}"
                               class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-brand-600 transition hover:bg-brand-500/10">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($customers->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <p class="text-sm text-ink/55 dark:text-orange-50/55">
                Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} results
            </p>
            <div class="flex items-center gap-2">
                @if($customers->onFirstPage())
                <span class="rounded-lg px-3 py-2 text-sm font-semibold text-ink/40 dark:text-orange-50/40">Previous</span>
                @else
                <a href="{{ $customers->previousPageUrl() }}" class="rounded-lg glass px-3 py-2 text-sm font-semibold transition hover:bg-brand-500/10 hover:text-brand-600">
                    Previous
                </a>
                @endif

                @foreach($customers->getUrlRange(1, $customers->lastPage()) as $page => $url)
                @if($page == $customers->currentPage())
                <span class="rounded-lg bg-brand-600 px-3 py-2 text-sm font-bold text-white">{{ $page }}</span>
                @else
                <a href="{{ $url }}" class="rounded-lg glass px-3 py-2 text-sm font-semibold transition hover:bg-brand-500/10 hover:text-brand-600">
                    {{ $page }}
                </a>
                @endif
                @endforeach

                @if($customers->hasMorePages())
                <a href="{{ $customers->nextPageUrl() }}" class="rounded-lg glass px-3 py-2 text-sm font-semibold transition hover:bg-brand-500/10 hover:text-brand-600">
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
