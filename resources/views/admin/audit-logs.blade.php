@extends('layouts.admin-layout')

@section('title', 'Audit Logs — Admin · Cafe Delight')

@php $activeNav = 'Audit Log'; @endphp

@section('content')
<div class="space-y-6">

    {{-- ===== Header ===== --}}
    <div data-aos="fade-up" class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-extrabold">Audit Logs</h1>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Track all system activities and user actions</p>
        </div>
        <form method="GET" action="{{ route('admin.audit-logs.download-pdf') }}" target="_blank">
            @csrf
            <button type="submit" class="flex items-center gap-2 rounded-xl bg-brand-600 text-white px-4 py-2.5 text-sm font-bold transition hover:scale-105">
                <i data-lucide="download" class="h-4 w-4"></i> Download PDF
            </button>
        </form>
    </div>

    {{-- ===== Summary Cards ===== --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div data-aos="fade-up" data-aos-delay="0" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white">
                    <i data-lucide="file-text" class="h-6 w-6"></i>
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">{{ $totalLogs }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Total Logs</p>
        </div>

        <div data-aos="fade-up" data-aos-delay="80" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white">
                    <i data-lucide="calendar" class="h-6 w-6"></i>
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">{{ $todayLogs }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Today's Logs</p>
        </div>

        <div data-aos="fade-up" data-aos-delay="160" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 text-white">
                    <i data-lucide="users" class="h-6 w-6"></i>
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">{{ $uniqueUsers }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Unique Users</p>
        </div>
    </div>

    {{-- ===== Filters ===== --}}
    <div data-aos="fade-up" class="rounded-2xl glass p-4 shadow-soft">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by user or description..."
                       class="w-full rounded-xl border border-black/10 bg-white/50 px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
            </div>
            <select name="action" class="rounded-xl border border-black/10 bg-white/50 px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                <option value="">All Actions</option>
                @foreach($actions as $act)
                <option value="{{ $act }}" {{ $action === $act ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $act)) }}</option>
                @endforeach
            </select>
            <select name="sort_by" class="rounded-xl border border-black/10 bg-white/50 px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Date</option>
                <option value="action" {{ $sortBy === 'action' ? 'selected' : '' }}>Action</option>
            </select>
            <select name="sort_order" class="rounded-xl border border-black/10 bg-white/50 px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 dark:border-white/10 dark:bg-ink/50">
                <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Descending</option>
                <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Ascending</option>
            </select>
            <button type="submit" class="flex items-center gap-2 rounded-xl bg-brand-600 text-white px-4 py-2.5 text-sm font-bold transition hover:scale-105">
                <i data-lucide="filter" class="h-4 w-4"></i> Filter
            </button>
            <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-2 rounded-xl glass px-4 py-2.5 text-sm font-bold transition hover:bg-brand-500/10">
                <i data-lucide="x" class="h-4 w-4"></i> Clear
            </a>
        </form>
    </div>

    {{-- ===== Audit Logs Table ===== --}}
    <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-lg font-bold">Activity Log</h2>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">{{ $auditLogs->total() }} results</p>
        </div>

        @if($auditLogs->isEmpty())
        <div class="py-10 text-center">
            <i data-lucide="file-text" class="mx-auto h-10 w-10 text-ink/20 dark:text-orange-50/20"></i>
            <p class="mt-3 text-sm font-semibold text-ink/50 dark:text-orange-50/50">No audit logs found</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs uppercase tracking-wider text-ink/45 dark:text-orange-50/45">
                        <th class="pb-3 font-bold">Date</th>
                        <th class="pb-3 font-bold">User</th>
                        <th class="pb-3 font-bold">Action</th>
                        <th class="pb-3 font-bold">Description</th>
                        <th class="pb-3 font-bold">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($auditLogs as $log)
                    <tr class="border-t border-black/5 dark:border-white/5">
                        <td class="py-3.5 text-ink/65 dark:text-orange-50/65">
                            {{ $log->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="py-3.5">
                            @if($log->user)
                            <div class="flex items-center gap-2">
                                <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-gradient-to-br from-brand-400 to-brand-700 text-xs font-bold text-white">
                                    {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                </span>
                                <span class="font-semibold">{{ $log->user->name }}</span>
                            </div>
                            @else
                            <span class="text-ink/45 dark:text-orange-50/45">System</span>
                            @endif
                        </td>
                        <td class="py-3.5">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold bg-brand-500/15 text-brand-600">
                                {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                            </span>
                        </td>
                        <td class="py-3.5 text-ink/65 dark:text-orange-50/65">
                            {{ $log->description }}
                        </td>
                        <td class="py-3.5 text-ink/45 dark:text-orange-50/45 text-xs">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($auditLogs->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <p class="text-sm text-ink/55 dark:text-orange-50/55">
                Showing {{ $auditLogs->firstItem() }} to {{ $auditLogs->lastItem() }} of {{ $auditLogs->total() }} results
            </p>
            <div class="flex items-center gap-2">
                @if($auditLogs->onFirstPage())
                <span class="rounded-lg px-3 py-2 text-sm font-semibold text-ink/40 dark:text-orange-50/40">Previous</span>
                @else
                <a href="{{ $auditLogs->previousPageUrl() }}" class="rounded-lg glass px-3 py-2 text-sm font-semibold transition hover:bg-brand-500/10 hover:text-brand-600">
                    Previous
                </a>
                @endif

                @foreach($auditLogs->getUrlRange(1, $auditLogs->lastPage()) as $page => $url)
                @if($page == $auditLogs->currentPage())
                <span class="rounded-lg bg-brand-600 px-3 py-2 text-sm font-bold text-white">{{ $page }}</span>
                @else
                <a href="{{ $url }}" class="rounded-lg glass px-3 py-2 text-sm font-semibold transition hover:bg-brand-500/10 hover:text-brand-600">
                    {{ $page }}
                </a>
                @endif
                @endforeach

                @if($auditLogs->hasMorePages())
                <a href="{{ $auditLogs->nextPageUrl() }}" class="rounded-lg glass px-3 py-2 text-sm font-semibold transition hover:bg-brand-500/10 hover:text-brand-600">
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
