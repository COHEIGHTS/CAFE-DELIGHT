@extends('layouts.admin-layout')

@section('title', 'Analytics — Admin · Cafe Delight')

@php $activeNav = 'Analytics'; @endphp

@section('content')
<div class="space-y-6" x-data="analyticsPage()">

    {{-- ===== Header with Date Filter ===== --}}
    <div data-aos="fade-up" class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-extrabold">Analytics Dashboard</h1>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Track sales, customers, and business performance</p>
        </div>
        
        {{-- Date Filter Form --}}
        <form method="GET" action="{{ route('admin.analytics.index') }}" class="flex items-center gap-2">
            <div class="flex items-center gap-2 rounded-xl glass px-3 py-2">
                <i data-lucide="calendar" class="h-4 w-4 text-ink/50 dark:text-orange-50/50"></i>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                       class="bg-transparent text-sm font-semibold focus:outline-none">
                <span class="text-ink/40">to</span>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                       class="bg-transparent text-sm font-semibold focus:outline-none">
            </div>
            <button type="submit" class="flex items-center gap-2 rounded-xl bg-brand-600 text-white px-4 py-2 text-sm font-bold transition hover:scale-105">
                <i data-lucide="filter" class="h-4 w-4"></i> Filter
            </button>
            <a href="{{ route('admin.analytics.downloadPDF', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="flex items-center gap-2 rounded-xl glass px-4 py-2 text-sm font-bold transition hover:bg-brand-500/10 hover:text-brand-600">
                <i data-lucide="download" class="h-4 w-4"></i> Download PDF
            </a>
        </form>
    </div>

    {{-- ===== Summary Cards ===== --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div data-aos="fade-up" data-aos-delay="0" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white">
                    <i data-lucide="banknote" class="h-6 w-6"></i>
                </span>
                <span class="text-xs font-bold {{ $revenueGrowth >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                    {{ $revenueGrowth >= 0 ? '+' : '' }}{{ $revenueGrowth }}% vs prev period
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">KSh {{ number_format($totalRevenue, 0) }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Total Revenue</p>
        </div>

        <div data-aos="fade-up" data-aos-delay="80" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white">
                    <i data-lucide="shopping-bag" class="h-6 w-6"></i>
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">{{ $totalOrders }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Total Orders</p>
        </div>

        <div data-aos="fade-up" data-aos-delay="160" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 text-white">
                    <i data-lucide="users" class="h-6 w-6"></i>
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">{{ $totalCustomers }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Unique Customers</p>
        </div>

        <div data-aos="fade-up" data-aos-delay="240" class="rounded-2xl glass p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 text-white">
                    <i data-lucide="trending-up" class="h-6 w-6"></i>
                </span>
            </div>
            <p class="mt-4 text-2xl font-extrabold">KSh {{ number_format($avgOrderValue, 0) }}</p>
            <p class="text-sm text-ink/55 dark:text-orange-50/55">Avg Order Value</p>
        </div>
    </div>

    {{-- ===== Charts Section ===== --}}
    <div class="grid gap-6 lg:grid-cols-2">
        
        {{-- Sales Over Time Chart --}}
        <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
            <h2 class="mb-4 text-lg font-bold">Sales Over Time</h2>
            <div class="h-80">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        {{-- Payment Methods Chart --}}
        <div data-aos="fade-up" data-aos-delay="100" class="rounded-3xl glass p-6 shadow-soft">
            <h2 class="mb-4 text-lg font-bold">Payment Methods</h2>
            <div class="h-80">
                <canvas id="paymentChart"></canvas>
            </div>
        </div>

    </div>

    {{-- ===== Top Dishes & Order Status ===== --}}
    <div class="grid gap-6 lg:grid-cols-2">
        
        {{-- Top Selling Dishes --}}
        <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
            <h2 class="mb-4 text-lg font-bold">Top Selling Dishes</h2>
            @if($topDishes->isEmpty())
            <div class="py-10 text-center">
                <i data-lucide="utensils" class="mx-auto h-10 w-10 text-ink/20 dark:text-orange-50/20"></i>
                <p class="mt-3 text-sm font-semibold text-ink/50 dark:text-orange-50/50">No sales data yet</p>
            </div>
            @else
            <div class="space-y-3">
                @foreach($topDishes as $i => $dish)
                <div class="flex items-center gap-3">
                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full text-xs font-extrabold
                                 {{ $i === 0 ? 'bg-gold/20 text-yellow-700' : 'bg-ink/5 text-ink/50 dark:bg-white/10 dark:text-orange-50/50' }}">
                        {{ $i + 1 }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold">{{ $dish['name'] }}</p>
                        <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-ink/8 dark:bg-white/10">
                            <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-brand-600 transition-all duration-700"
                                 style="width: {{ $topDishes->first()['quantity'] > 0 ? ($dish['quantity'] / $topDishes->first()['quantity']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="shrink-0 text-right">
                        <span class="block text-xs font-bold text-ink/55 dark:text-orange-50/55">{{ $dish['quantity'] }} sold</span>
                        <span class="block text-xs font-semibold text-brand-600">KSh {{ number_format($dish['revenue'], 0) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Order Status Breakdown --}}
        <div data-aos="fade-up" data-aos-delay="100" class="rounded-3xl glass p-6 shadow-soft">
            <h2 class="mb-4 text-lg font-bold">Order Status Breakdown</h2>
            <div class="h-80">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

    </div>

    {{-- ===== Customer Analytics ===== --}}
    <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
        <h2 class="mb-4 text-lg font-bold">Top Customers by Revenue</h2>
        @if($customerOrders->isEmpty())
        <div class="py-10 text-center">
            <i data-lucide="users" class="mx-auto h-10 w-10 text-ink/20 dark:text-orange-50/20"></i>
            <p class="mt-3 text-sm font-semibold text-ink/50 dark:text-orange-50/50">No customer data yet</p>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($customerOrders as $customer)
                    <tr class="border-t border-black/5 dark:border-white/5">
                        <td class="py-3.5 font-bold">{{ $customer['name'] }}</td>
                        <td class="py-3.5 text-ink/65 dark:text-orange-50/65">{{ $customer['email'] }}</td>
                        <td class="py-3.5 font-semibold">{{ $customer['orders'] }}</td>
                        <td class="py-3.5 font-bold text-brand-600">KSh {{ number_format($customer['total_spent'], 0) }}</td>
                        <td class="py-3.5 font-semibold">KSh {{ number_format($customer['avg_order_value'], 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
        if (window.AOS) AOS.init({ duration: 600, once: true, easing: 'ease-out-cubic' });

        // Sales Over Time Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesByDay->pluck('date')) !!},
                datasets: [{
                    label: 'Revenue (KSh)',
                    data: {!! json_encode($salesByDay->pluck('revenue')) !!},
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    fill: true,
                    tension: 0.4,
                }, {
                    label: 'Orders',
                    data: {!! json_encode($salesByDay->pluck('orders')) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });

        // Payment Methods Chart
        const paymentCtx = document.getElementById('paymentChart').getContext('2d');
        new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($salesByPayment->pluck('method')) !!},
                datasets: [{
                    data: {!! json_encode($salesByPayment->pluck('revenue')) !!},
                    backgroundColor: [
                        '#f97316',
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#8b5cf6',
                    ],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });

        // Order Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($orderStatusBreakdown->pluck('status')) !!},
                datasets: [{
                    label: 'Orders',
                    data: {!! json_encode($orderStatusBreakdown->pluck('count')) !!},
                    backgroundColor: [
                        '#f59e0b',
                        '#3b82f6',
                        '#8b5cf6',
                        '#f97316',
                        '#10b981',
                        '#ef4444',
                    ],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });
    });
</script>
@endpush
