@extends('layouts.customer-layout')

@section('title', 'Dashboard — Cafe Delight')

@php $activeNav = 'Dashboard'; @endphp

@section('content')
<div class="space-y-6">

    {{-- ===== Welcome banner ===== --}}
    <div data-aos="fade-up"
         class="overflow-hidden rounded-3xl bg-gradient-to-br from-brand-500 via-brand-600 to-brand-800 p-6 text-white sm:p-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-extrabold sm:text-3xl">
                    Welcome back, {{ auth()->user()->name ?? 'Jane' }}! 👋
                </h1>
                <p class="mt-1 text-orange-50/90">Hungry again? Your favorites are just a click away.</p>
            </div>
            <a href="#"
               class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-brand-700 transition hover:scale-105">
                <i data-lucide="plus" class="h-4 w-4"></i> New Order
            </a>
        </div>
    </div>

    {{-- ===== Stat cards ===== --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4"
         x-data="dashboardData()">
        <template x-for="(card, i) in stats" :key="card.label">
            <div data-aos="fade-up" :data-aos-delay="i * 80"
                 class="rounded-2xl glass p-5 shadow-soft">
                <div class="flex items-center justify-between">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white">
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

    {{-- ===== Orders + Quick Reorder ===== --}}
    <div class="grid gap-6 lg:grid-cols-3"
         x-data="dashboardData()">

        {{-- Recent orders --}}
        <div data-aos="fade-up"
             class="rounded-3xl glass p-6 shadow-soft lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold">Recent Orders</h2>
                <a href="#" class="text-sm font-bold text-brand-600 hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-ink/45 dark:text-orange-50/45">
                            <th class="pb-3 font-bold">Order</th>
                            <th class="pb-3 font-bold">Items</th>
                            <th class="pb-3 font-bold">Total</th>
                            <th class="pb-3 font-bold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="o in orders" :key="o.id">
                            <tr class="border-t border-black/5 dark:border-white/5">
                                <td class="py-3.5 font-bold" x-text="o.id"></td>
                                <td class="py-3.5 text-ink/65 dark:text-orange-50/65" x-text="o.items"></td>
                                <td class="py-3.5 font-semibold" x-text="o.total"></td>
                                <td class="py-3.5">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold"
                                          :class="o.cls">
                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                        <span x-text="o.status"></span>
                                    </span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Reorder --}}
        <div data-aos="fade-up" data-aos-delay="100"
             class="rounded-3xl glass p-6 shadow-soft">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold">Quick Reorder</h2>
                <i data-lucide="repeat" class="h-5 w-5 text-brand-600"></i>
            </div>
            <div class="space-y-3">
                <template x-for="f in favorites" :key="f.name">
                    <div class="flex items-center gap-3 rounded-2xl glass p-3 transition hover:shadow-soft">
                        <span class="grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-brand-100 to-brand-200 text-2xl dark:from-brand-500/30 dark:to-brand-700/30"
                              x-text="f.emoji"></span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold" x-text="f.name"></p>
                            <p class="text-xs font-semibold text-brand-600" x-text="f.price"></p>
                        </div>
                        <button class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 text-white transition hover:scale-110">
                            <i data-lucide="plus" class="h-4 w-4"></i>
                        </button>
                    </div>
                </template>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function dashboardData() {
        return {
            stats: [
                { label:'Total Orders',   value:'47',          icon:'shopping-bag', delta:'+12%', up:true  },
                { label:'Total Spent',    value:'KSh 18,420',  icon:'wallet',       delta:'+8%',  up:true  },
                { label:'Loyalty Points', value:'1,240',       icon:'star',         delta:'+45',  up:true  },
                { label:'Saved Items',    value:'8',           icon:'heart',        delta:'-2',   up:false },
            ],

            orders: [
                { id:'#CD-1042', items:'Beef Shawarma, Fresh Juice', total:'KSh 550',   status:'Delivered', cls:'bg-emerald-500/15 text-emerald-600' },
                { id:'#CD-1041', items:'Chicken Biryani x2',         total:'KSh 1,100', status:'On the way', cls:'bg-amber-500/15 text-amber-600'   },
                { id:'#CD-1039', items:'Choco Cake, Soda',           total:'KSh 550',   status:'Preparing', cls:'bg-blue-500/15 text-blue-600'      },
                { id:'#CD-1036', items:'Chips, Chapati, Samosa',     total:'KSh 320',   status:'Delivered', cls:'bg-emerald-500/15 text-emerald-600' },
                { id:'#CD-1031', items:'Mango Juice x3',             total:'KSh 600',   status:'Cancelled', cls:'bg-red-500/15 text-red-600'         },
            ],

            favorites: [
                { name:'Beef Shawarma',     price:'KSh 350', emoji:'🌯' },
                { name:'Chicken Biryani',   price:'KSh 550', emoji:'🍛' },
                { name:'Fresh Mango Juice', price:'KSh 200', emoji:'🥭' },
                { name:'Chocolate Cake',    price:'KSh 450', emoji:'🍰' },
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