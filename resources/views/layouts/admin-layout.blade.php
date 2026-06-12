<!DOCTYPE html>
<html lang="en" x-data="adminLayout()" :class="{ 'dark': darkMode }" x-cloak class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — Cafe Delight')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans:    ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        brand: { 50:'#fff7ed',100:'#ffedd5',200:'#fed7aa',300:'#fdba74',400:'#fb923c',500:'#f97316',600:'#ea580c',700:'#c2410c',800:'#9a3412',900:'#7c2d12' },
                        gold: '#e6b450',
                        ink:  '#0b0a09',
                    },
                    boxShadow: {
                        glow:    '0 0 40px -10px rgba(249,115,22,0.55)',
                        premium: '0 30px 60px -15px rgba(0,0,0,0.35)',
                        soft:    '0 10px 40px -12px rgba(0,0,0,0.18)',
                    },
                },
            },
        }
    </script>

    <style>
        [x-cloak] { display:none !important; }
        body { -webkit-font-smoothing: antialiased; }
        .glass        { background:rgba(255,255,255,0.6);  backdrop-filter:blur(18px) saturate(160%); -webkit-backdrop-filter:blur(18px) saturate(160%); border:1px solid rgba(255,255,255,0.5); }
        .dark .glass  { background:rgba(24,21,18,0.6);     border:1px solid rgba(255,255,255,0.08); }
        .glass-strong { background:rgba(255,255,255,0.85); backdrop-filter:blur(22px) saturate(180%); -webkit-backdrop-filter:blur(22px) saturate(180%); }
        .dark .glass-strong { background:rgba(20,17,15,0.85); }
        .text-gradient { background:linear-gradient(120deg,#fb923c,#ea580c 40%,#e6b450); -webkit-background-clip:text; background-clip:text; color:transparent; }
        .mesh {
            background:
                radial-gradient(40rem 40rem at 100% 0%,   rgba(234,88,12,0.14),  transparent 60%),
                radial-gradient(40rem 40rem at 0%   100%, rgba(194,65,12,0.12),  transparent 60%);
        }
        ::-webkit-scrollbar { width:9px; height:9px; }
        ::-webkit-scrollbar-thumb { background:linear-gradient(#f97316,#ea580c); border-radius:99px; border:2px solid transparent; background-clip:content-box; }
    </style>

    @stack('styles')
</head>

<body class="font-sans min-h-screen bg-[#faf6f2] text-ink dark:bg-ink dark:text-orange-50 antialiased selection:bg-brand-500 selection:text-white">

    <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10 mesh"></div>

    <div class="flex min-h-screen">

        {{-- ============ SIDEBAR (desktop) ============ --}}
        <aside class="sticky top-0 hidden h-screen shrink-0 flex-col border-r border-black/5 glass-strong transition-all duration-300 lg:flex dark:border-white/5"
               :class="collapsed ? 'w-20' : 'w-72'">

            {{-- Logo + Admin badge --}}
            <div class="flex h-20 items-center gap-3 px-5" :class="collapsed && 'justify-center'">
                <a href="{{ url('/') }}" class="relative grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-brand-600 to-brand-900 text-white shadow-glow">
                    <i data-lucide="shield-check" class="h-6 w-6"></i>
                </a>
                <div x-show="!collapsed" x-transition class="min-w-0">
                    <p class="font-display text-xl font-extrabold leading-none">Cafe <span class="text-gradient">Delight</span></p>
                    <span class="mt-0.5 inline-flex items-center gap-1 rounded-full bg-brand-500/15 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-widest text-brand-600">
                        <i data-lucide="shield" class="h-3 w-3"></i> Admin
                    </span>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 py-4">

                <p x-show="!collapsed" class="mb-2 px-3 text-[10px] font-extrabold uppercase tracking-widest text-ink/35 dark:text-orange-50/35">Overview</p>
                <template x-for="item in nav.overview" :key="item.label">
                    <a :href="item.href"
                       class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition"
                       :class="active === item.label
                            ? 'bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-glow'
                            : 'text-ink/65 hover:bg-brand-500/10 hover:text-brand-600 dark:text-orange-50/65 dark:hover:text-brand-300'"
                       :title="collapsed ? item.label : ''">
                        <i :data-lucide="item.icon" class="h-5 w-5 shrink-0"></i>
                        <span x-show="!collapsed" x-transition x-text="item.label" class="truncate"></span>
                        <span x-show="!collapsed && item.badge" x-transition
                              class="ml-auto rounded-full px-2 py-0.5 text-xs font-bold"
                              :class="active === item.label ? 'bg-white/25 text-white' : 'bg-brand-500/15 text-brand-600'"
                              x-text="item.badge"></span>
                    </a>
                </template>

                <div x-show="!collapsed" class="my-3 h-px bg-black/5 dark:bg-white/5"></div>
                <p x-show="!collapsed" class="mb-2 px-3 text-[10px] font-extrabold uppercase tracking-widest text-ink/35 dark:text-orange-50/35">Manage</p>
                <template x-for="item in nav.manage" :key="item.label">
                    <a :href="item.href"
                       class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition"
                       :class="active === item.label
                            ? 'bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-glow'
                            : 'text-ink/65 hover:bg-brand-500/10 hover:text-brand-600 dark:text-orange-50/65 dark:hover:text-brand-300'"
                       :title="collapsed ? item.label : ''">
                        <i :data-lucide="item.icon" class="h-5 w-5 shrink-0"></i>
                        <span x-show="!collapsed" x-transition x-text="item.label" class="truncate"></span>
                        <span x-show="!collapsed && item.badge" x-transition
                              class="ml-auto rounded-full px-2 py-0.5 text-xs font-bold"
                              :class="active === item.label ? 'bg-white/25 text-white' : 'bg-amber-500/15 text-amber-600'"
                              x-text="item.badge"></span>
                    </a>
                </template>

                <div x-show="!collapsed" class="my-3 h-px bg-black/5 dark:bg-white/5"></div>
                <p x-show="!collapsed" class="mb-2 px-3 text-[10px] font-extrabold uppercase tracking-widest text-ink/35 dark:text-orange-50/35">System</p>
                <template x-for="item in nav.system" :key="item.label">
                    <a :href="item.href"
                       class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition"
                       :class="active === item.label
                            ? 'bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-glow'
                            : 'text-ink/65 hover:bg-brand-500/10 hover:text-brand-600 dark:text-orange-50/65 dark:hover:text-brand-300'"
                       :title="collapsed ? item.label : ''">
                        <i :data-lucide="item.icon" class="h-5 w-5 shrink-0"></i>
                        <span x-show="!collapsed" x-transition x-text="item.label" class="truncate"></span>
                    </a>
                </template>

            </nav>

            {{-- Logout --}}
            <div class="border-t border-black/5 p-3 dark:border-white/5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold text-ink/65 transition hover:bg-red-500/10 hover:text-red-500 dark:text-orange-50/65"
                            :class="collapsed && 'justify-center'"
                            :title="collapsed ? 'Log out' : ''">
                        <i data-lucide="log-out" class="h-5 w-5 shrink-0"></i>
                        <span x-show="!collapsed" x-transition>Log out</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ============ MOBILE SIDEBAR (drawer) ============ --}}
        <div x-show="mobileOpen" x-transition.opacity x-cloak
             class="fixed inset-0 z-40 bg-black/50 lg:hidden"
             x-on:click="mobileOpen=false"></div>

        <aside x-show="mobileOpen" x-cloak
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-200"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col glass-strong lg:hidden">

            <div class="flex h-20 items-center justify-between px-5">
                <div>
                    <p class="font-display text-xl font-extrabold">Cafe <span class="text-gradient">Delight</span></p>
                    <span class="inline-flex items-center gap-1 rounded-full bg-brand-500/15 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-widest text-brand-600">
                        <i data-lucide="shield" class="h-3 w-3"></i> Admin
                    </span>
                </div>
                <button x-on:click="mobileOpen=false" class="grid h-9 w-9 place-items-center rounded-lg glass">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 py-4">
                <template x-for="item in [...nav.overview, ...nav.manage, ...nav.system]" :key="'m'+item.label">
                    <a :href="item.href" @click="mobileOpen=false"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition"
                       :class="active === item.label
                            ? 'bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-glow'
                            : 'text-ink/65 hover:bg-brand-500/10 hover:text-brand-600 dark:text-orange-50/65'">
                        <i :data-lucide="item.icon" class="h-5 w-5"></i>
                        <span x-text="item.label"></span>
                        <span x-show="item.badge" class="ml-auto rounded-full bg-amber-500/15 px-2 py-0.5 text-xs font-bold text-amber-600" x-text="item.badge"></span>
                    </a>
                </template>
            </nav>

            <div class="border-t border-black/5 p-3 dark:border-white/5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold text-red-500 transition hover:bg-red-500/10">
                        <i data-lucide="log-out" class="h-5 w-5"></i> Log out
                    </button>
                </form>
            </div>
        </aside>

        {{-- ============ MAIN CONTENT AREA ============ --}}
        <div class="flex min-w-0 flex-1 flex-col">

            {{-- ===== TOP BAR ===== --}}
            <header class="sticky top-0 z-30 flex h-20 items-center gap-3 border-b border-black/5 glass px-4 sm:px-6 dark:border-white/5">

                <button x-on:click="collapsed = !collapsed"
                        class="hidden h-10 w-10 place-items-center rounded-xl glass lg:grid transition hover:scale-105"
                        aria-label="Collapse sidebar">
                    <i data-lucide="panel-left" class="h-5 w-5"></i>
                </button>
                <button x-on:click="mobileOpen = true"
                        class="grid h-10 w-10 place-items-center rounded-xl glass lg:hidden"
                        aria-label="Open menu">
                    <i data-lucide="menu" class="h-5 w-5"></i>
                </button>

                {{-- Breadcrumb / page title --}}
                <div class="hidden sm:block">
                    <p class="text-xs font-semibold text-ink/40 dark:text-orange-50/40">Admin Panel</p>
                    <p class="text-sm font-extrabold" x-text="active"></p>
                </div>

                {{-- ── Pending orders quick-link pill (shows when there are pending orders) ── --}}
                @php $pendingCount = \App\Models\Order::where('status','pending')->count(); @endphp
                @if($pendingCount > 0)
                <a href="{{ route('admin.order.index', ['status' => 'pending']) }}"
                   class="hidden sm:flex items-center gap-2 rounded-full bg-amber-500/15 border border-amber-500/25 px-3 py-1.5 text-xs font-extrabold text-amber-600 transition hover:bg-amber-500/25">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    {{ $pendingCount }} pending {{ Str::plural('order', $pendingCount) }}
                </a>
                @endif

                <div class="ml-auto flex items-center gap-2">

                    {{-- Dark mode --}}
                    <button x-on:click="toggleDark()"
                            class="grid h-10 w-10 place-items-center rounded-xl glass transition hover:scale-105"
                            aria-label="Toggle dark mode">
                        <i x-show="!darkMode" data-lucide="moon" class="h-5 w-5"></i>
                        <i x-show="darkMode"  data-lucide="sun"  class="h-5 w-5 text-gold"></i>
                    </button>

                    {{-- Notifications --}}
                    <button class="relative grid h-10 w-10 place-items-center rounded-xl glass transition hover:scale-105" aria-label="Notifications">
                        <i data-lucide="bell" class="h-5 w-5"></i>
                        @if($pendingCount > 0)
                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-ink"></span>
                        @endif
                    </button>

                    {{-- Admin user menu --}}
                    <div class="relative" x-data="{ open: false }">
                        <button x-on:click="open = !open"
                                class="flex items-center gap-2 rounded-xl glass py-1.5 pl-1.5 pr-3 transition hover:scale-105">
                            <span class="grid h-8 w-8 place-items-center rounded-lg bg-gradient-to-br from-brand-600 to-brand-900 text-sm font-bold text-white">
                                {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                            </span>
                            <div class="hidden sm:block text-left">
                                <p class="text-xs font-extrabold leading-none">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-[10px] font-semibold text-brand-600 leading-none mt-0.5">Administrator</p>
                            </div>
                            <i data-lucide="chevron-down" class="hidden h-4 w-4 sm:block"></i>
                        </button>

                        <div x-show="open" x-transition x-on:click.outside="open=false" x-cloak
                             class="absolute right-0 mt-2 w-52 overflow-hidden rounded-2xl glass-strong p-2 shadow-premium">
                            <a href="#" class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-semibold transition hover:bg-brand-500/10 hover:text-brand-600">
                                <i data-lucide="user" class="h-4 w-4"></i> My Profile
                            </a>
                            <a href="#" class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-semibold transition hover:bg-brand-500/10 hover:text-brand-600">
                                <i data-lucide="settings" class="h-4 w-4"></i> Settings
                            </a>
                            <div class="my-1 h-px bg-black/5 dark:bg-white/5"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-semibold text-red-500 transition hover:bg-red-500/10">
                                    <i data-lucide="log-out" class="h-4 w-4"></i> Log out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- ===== PAGE SLOT ===== --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

        </div>
    </div>

    {{-- ============ SCRIPTS ============ --}}
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        function adminLayout() {
            return {
                darkMode:   false,
                collapsed:  false,
                mobileOpen: false,
                active: '{{ $activeNav ?? 'Dashboard' }}',

                nav: {
                    overview: [
                        { label: 'Dashboard',  icon: 'layout-dashboard', href: '{{ route('admin.dashboard') }}', badge: '' },
                        { label: 'Analytics',  icon: 'bar-chart-2',      href: '{{ route('admin.analytics.index') }}', badge: '' },
                    ],
                    manage: [
                        // ── Orders: href now points to the real route; badge shows live pending count ──
                        { label: 'Orders',     icon: 'shopping-bag',     href: '{{ route('admin.order.index') }}', badge: '{{ \App\Models\Order::where('status','pending')->count() ?: '' }}' },
                        { label: 'Menu Items', icon: 'utensils',         href: '{{ route('admin.menu.index') }}',  badge: '' },
                        { label: 'Add Dish',   icon: 'plus-circle',      href: '{{ route('admin.menu.create') }}', badge: '' },
                        { label: 'Customers',  icon: 'users',            href: '{{ route('admin.customers.index') }}', badge: '' },
                        { label: 'Payments',   icon: 'credit-card',      href: '{{ route('admin.payments.index') }}', badge: '{{ \App\Models\Order::where('payment_status','awaiting_approval')->count() ?: '' }}' },
                    ],
                    system: [
                        { label: 'Settings',   icon: 'settings',         href: '{{ route('admin.settings.index') }}' },
                        { label: 'Audit Log',  icon: 'shield-check',     href: '{{ route('admin.audit-logs.index') }}' },
                    ],
                },

                init() {
                    const saved = localStorage.getItem('cd-admin-theme');
                    this.darkMode = saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;

                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                        if (window.AOS) AOS.init({ duration: 600, once: true, easing: 'ease-out-cubic' });
                    });

                    ['darkMode', 'collapsed', 'mobileOpen'].forEach(prop =>
                        this.$watch(prop, () => this.$nextTick(() => lucide.createIcons()))
                    );
                },

                toggleDark() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('cd-admin-theme', this.darkMode ? 'dark' : 'light');
                },
            }
        }
    </script>

    @stack('scripts')
</body>
</html>