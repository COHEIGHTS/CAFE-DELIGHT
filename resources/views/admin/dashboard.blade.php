@extends('layouts.admin-layout')

@section('title', 'Dashboard — Admin · Cafe Delight')

@php $activeNav = 'Dashboard'; @endphp

@section('content')
<div class="space-y-6" x-data="adminDashboard()">

    {{-- ===== Welcome banner ===== --}}
    <div data-aos="fade-up"
         class="overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 via-brand-700 to-brand-900 p-6 text-white sm:p-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-xs font-extrabold uppercase tracking-widest">
                        <i data-lucide="shield-check" class="h-3.5 w-3.5"></i> Admin Panel
                    </span>
                </div>
                <h1 class="font-display text-2xl font-extrabold sm:text-3xl">
                    Good day, {{ auth()->user()->name ?? 'Admin' }}! 👋
                </h1>
                <p class="mt-1 text-orange-50/90">Here's what's happening at Cafe Delight today.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="#" class="inline-flex items-center gap-2 rounded-xl bg-white/15 border border-white/20 px-4 py-2.5 text-sm font-bold transition hover:bg-white/25">
                    <i data-lucide="download" class="h-4 w-4"></i> Export Report
                </a>
                <a href="#" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-brand-700 transition hover:scale-105">
                    <i data-lucide="plus" class="h-4 w-4"></i> New Menu Item
                </a>
            </div>
        </div>
    </div>

    {{-- ===== Stat cards ===== --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <template x-for="(card, i) in stats" :key="card.label">
            <div data-aos="fade-up" :data-aos-delay="i * 80"
                 class="rounded-2xl glass p-5 shadow-soft">
                <div class="flex items-center justify-between">
                    <span class="grid h-11 w-11 place-items-center rounded-xl text-white"
                          :class="card.color">
                        <i :data-lucide="card.icon" class="h-6 w-6"></i>
                    </span>
                    <span class="text-xs font-bold"
                          :class="card.up ? 'text-emerald-500' : 'text-red-500'"
                          x-text="card.delta"></span>
                </div>
                <p class="mt-4 text-2xl font-extrabold" x-text="card.value"></p>
                <p class="text-sm text-ink/55 dark:text-orange-50/55" x-text="card.label"></p>
            </div>
        </template>
    </div>

    {{-- ===== Orders table + Top items ===== --}}
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Recent orders --}}
        <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft lg:col-span-2">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-bold">Recent Orders</h2>
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-amber-500/15 px-2.5 py-1 text-xs font-bold text-amber-600">12 pending</span>
                    <a href="#" class="text-sm font-bold text-brand-600 hover:underline">View all</a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-ink/45 dark:text-orange-50/45">
                            <th class="pb-3 font-bold">Order</th>
                            <th class="pb-3 font-bold">Customer</th>
                            <th class="pb-3 font-bold">Items</th>
                            <th class="pb-3 font-bold">Total</th>
                            <th class="pb-3 font-bold">Status</th>
                            <th class="pb-3 font-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="o in orders" :key="o.id">
                            <tr class="border-t border-black/5 dark:border-white/5">
                                <td class="py-3.5 font-bold" x-text="o.id"></td>
                                <td class="py-3.5">
                                    <div class="flex items-center gap-2">
                                        <span class="grid h-7 w-7 place-items-center rounded-full bg-gradient-to-br from-brand-400 to-brand-700 text-xs font-bold text-white"
                                              x-text="o.customer.charAt(0)"></span>
                                        <span class="font-semibold" x-text="o.customer"></span>
                                    </div>
                                </td>
                                <td class="py-3.5 text-ink/65 dark:text-orange-50/65" x-text="o.items"></td>
                                <td class="py-3.5 font-semibold" x-text="o.total"></td>
                                <td class="py-3.5">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold"
                                          :class="o.cls">
                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                        <span x-text="o.status"></span>
                                    </span>
                                </td>
                                <td class="py-3.5">
                                    <button class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-brand-600 transition hover:bg-brand-500/10">
                                        View
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top selling items --}}
        <div data-aos="fade-up" data-aos-delay="100" class="rounded-3xl glass p-6 shadow-soft">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-bold">Top Sellers</h2>
                <i data-lucide="trending-up" class="h-5 w-5 text-brand-600"></i>
            </div>
            <div class="space-y-3">
                <template x-for="(item, i) in topItems" :key="item.name">
                    <div class="flex items-center gap-3">
                        <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full text-xs font-extrabold"
                              :class="i === 0 ? 'bg-gold/20 text-yellow-700' : 'bg-ink/5 text-ink/50 dark:bg-white/10 dark:text-orange-50/50'"
                              x-text="i + 1"></span>
                        <span class="text-xl" x-text="item.emoji"></span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold" x-text="item.name"></p>
                            <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-ink/8 dark:bg-white/10">
                                <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-brand-600 transition-all duration-700"
                                     :style="`width: ${item.pct}%`"></div>
                            </div>
                        </div>
                        <span class="shrink-0 text-xs font-bold text-ink/55 dark:text-orange-50/55" x-text="item.orders + ' orders'"></span>
                    </div>
                </template>
            </div>

            {{-- Quick actions --}}
            <div class="mt-6 border-t border-black/5 pt-5 dark:border-white/5">
                <p class="mb-3 text-xs font-extrabold uppercase tracking-widest text-ink/40 dark:text-orange-50/40">Quick Actions</p>
                <div class="grid grid-cols-2 gap-2">
                    <button class="flex flex-col items-center gap-1.5 rounded-xl bg-brand-500/10 px-3 py-3 text-xs font-bold text-brand-600 transition hover:bg-brand-500/20">
                        <i data-lucide="plus-circle" class="h-5 w-5"></i> Add Item
                    </button>
                    <button class="flex flex-col items-center gap-1.5 rounded-xl bg-brand-500/10 px-3 py-3 text-xs font-bold text-brand-600 transition hover:bg-brand-500/20">
                        <i data-lucide="users" class="h-5 w-5"></i> Customers
                    </button>
                    <button class="flex flex-col items-center gap-1.5 rounded-xl bg-brand-500/10 px-3 py-3 text-xs font-bold text-brand-600 transition hover:bg-brand-500/20">
                        <i data-lucide="package" class="h-5 w-5"></i> Inventory
                    </button>
                    <button class="flex flex-col items-center gap-1.5 rounded-xl bg-brand-500/10 px-3 py-3 text-xs font-bold text-brand-600 transition hover:bg-brand-500/20">
                        <i data-lucide="bar-chart-2" class="h-5 w-5"></i> Analytics
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function adminDashboard() {
        return {
            stats: [
                { label: "Today's Orders",  value: '34',         icon: 'shopping-bag',  color: 'bg-gradient-to-br from-brand-500 to-brand-700',     delta: '+8%',  up: true  },
                { label: "Today's Revenue", value: 'KSh 28,450', icon: 'banknote',      color: 'bg-gradient-to-br from-emerald-500 to-emerald-700',  delta: '+14%', up: true  },
                { label: 'Total Customers', value: '1,284',      icon: 'users',         color: 'bg-gradient-to-br from-blue-500 to-blue-700',        delta: '+22',  up: true  },
                { label: 'Pending Orders',  value: '12',         icon: 'clock',         color: 'bg-gradient-to-br from-amber-500 to-amber-700',      delta: '-3',   up: false },
            ],

            orders: [
                { id:'#CD-1055', customer:'Jane Doe',    items:'Beef Shawarma, Juice',  total:'KSh 550',   status:'Pending',    cls:'bg-amber-500/15 text-amber-600'   },
                { id:'#CD-1054', customer:'Ali Hassan',  items:'Chicken Biryani x2',    total:'KSh 1,100', status:'Preparing',  cls:'bg-blue-500/15 text-blue-600'     },
                { id:'#CD-1053', customer:'Mary Wanjiku',items:'Choco Cake, Soda',      total:'KSh 550',   status:'On the way', cls:'bg-purple-500/15 text-purple-600' },
                { id:'#CD-1052', customer:'Brian Otieno',items:'Chips, Chapati x3',     total:'KSh 420',   status:'Delivered',  cls:'bg-emerald-500/15 text-emerald-600'},
                { id:'#CD-1051', customer:'Grace Mwangi',items:'Mango Juice x2',        total:'KSh 400',   status:'Cancelled',  cls:'bg-red-500/15 text-red-600'       },
            ],

            topItems: [
                { name:'Chicken Biryani',   emoji:'🍛', orders:142, pct:95 },
                { name:'Beef Shawarma',     emoji:'🌯', orders:128, pct:85 },
                { name:'Chocolate Cake',    emoji:'🍰', orders: 97, pct:65 },
                { name:'Fresh Mango Juice', emoji:'🥭', orders: 88, pct:58 },
                { name:'Chips & Chapati',   emoji:'🍟', orders: 74, pct:49 },
            ],

            init() {
                this.$nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                });
            },
        }
    }
</script>
@endpush