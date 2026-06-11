<!DOCTYPE html>
<html lang="en" x-data="appLayout()" :class="{ 'dark': darkMode }" x-cloak class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cafe Delight')</title>

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
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        brand: { 50:'#fff7ed',100:'#ffedd5',200:'#fed7aa',300:'#fdba74',400:'#fb923c',500:'#f97316',600:'#ea580c',700:'#c2410c',800:'#9a3412',900:'#7c2d12' },
                        gold: '#e6b450', ink: '#0b0a09',
                    },
                    boxShadow: {
                        glow: '0 0 40px -10px rgba(249,115,22,0.55)',
                        premium: '0 30px 60px -15px rgba(0,0,0,0.35)',
                        soft: '0 10px 40px -12px rgba(0,0,0,0.18)',
                    },
                },
            },
        }
    </script>

    <style>
        [x-cloak] { display:none !important; }
        body { -webkit-font-smoothing: antialiased; }
        .glass { background:rgba(255,255,255,0.6); backdrop-filter:blur(18px) saturate(160%); -webkit-backdrop-filter:blur(18px) saturate(160%); border:1px solid rgba(255,255,255,0.5); }
        .dark .glass { background:rgba(24,21,18,0.6); border:1px solid rgba(255,255,255,0.08); }
        .glass-strong { background:rgba(255,255,255,0.85); backdrop-filter:blur(22px) saturate(180%); -webkit-backdrop-filter:blur(22px) saturate(180%); }
        .dark .glass-strong { background:rgba(20,17,15,0.85); }
        .text-gradient { background:linear-gradient(120deg,#fb923c,#ea580c 40%,#e6b450); -webkit-background-clip:text; background-clip:text; color:transparent; }
        .mesh {
            background:
                radial-gradient(40rem 40rem at 100% 0%, rgba(249,115,22,0.12), transparent 60%),
                radial-gradient(40rem 40rem at 0% 100%, rgba(230,180,80,0.12), transparent 60%);
        }
        ::-webkit-scrollbar { width:9px; height:9px; }
        ::-webkit-scrollbar-thumb { background:linear-gradient(#f97316,#ea580c); border-radius:99px; border:2px solid transparent; background-clip:content-box; }

        /* Heart pop animation */
        @keyframes heartPop {
            0%   { transform: scale(1); }
            40%  { transform: scale(1.4); }
            70%  { transform: scale(0.9); }
            100% { transform: scale(1); }
        }
        .heart-pop { animation: heartPop 0.4s ease forwards; }
    </style>

    @stack('styles')
</head>

<body class="font-sans min-h-screen bg-[#fbf7f1] text-ink dark:bg-ink dark:text-orange-50 antialiased selection:bg-brand-500 selection:text-white">

    <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10 mesh"></div>

    <div class="flex min-h-screen">

        {{-- ============ SIDEBAR (desktop) ============ --}}
        <aside class="sticky top-0 hidden h-screen shrink-0 flex-col border-r border-black/5 glass-strong transition-all duration-300 lg:flex dark:border-white/5"
               :class="collapsed ? 'w-20' : 'w-72'">

            {{-- Logo --}}
            <div class="flex h-20 items-center gap-3 px-5" :class="collapsed && 'justify-center'">
                <a href="{{ url('/') }}" class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-glow">
                    <i data-lucide="utensils-crossed" class="h-6 w-6"></i>
                </a>
                <span x-show="!collapsed" x-transition class="font-display text-xl font-extrabold leading-none">
                    Cafe <span class="text-gradient">Delight</span>
                </span>
            </div>

            {{-- Nav links --}}
            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                <template x-for="item in nav" :key="item.label">
                    <a :href="item.href"
                       class="group relative flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition"
                       :class="active === item.label
                            ? 'bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-glow'
                            : 'text-ink/65 hover:bg-brand-500/10 hover:text-brand-600 dark:text-orange-50/65 dark:hover:text-brand-300'"
                       :title="collapsed ? item.label : ''">
                        <i :data-lucide="item.icon" class="h-5 w-5 shrink-0"></i>
                        <span x-show="!collapsed" x-transition x-text="item.label" class="truncate"></span>
                        {{-- Dynamic badge (favorites count) --}}
                        <span x-show="!collapsed && item.badgeDynamic"
                              x-transition
                              :id="item.badgeId"
                              class="ml-auto rounded-full px-2 py-0.5 text-xs font-bold hidden"
                              :class="active === item.label ? 'bg-white/25 text-white' : 'bg-brand-500/15 text-brand-600'"></span>
                        {{-- Static badge --}}
                        <span x-show="!collapsed && item.badge && !item.badgeDynamic"
                              x-transition
                              class="ml-auto rounded-full px-2 py-0.5 text-xs font-bold"
                              :class="active === item.label ? 'bg-white/25 text-white' : 'bg-brand-500/15 text-brand-600'"
                              x-text="item.badge"></span>
                    </a>
                </template>
            </nav>

            {{-- Promo card --}}
            <div x-show="!collapsed" x-transition class="m-3 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 p-4 text-white">
                <i data-lucide="gift" class="h-7 w-7"></i>
                <p class="mt-2 text-sm font-bold leading-tight">Get 20% off your next order!</p>
                <p class="mt-1 text-xs text-orange-50/80">Use code DELIGHT20 at checkout.</p>
                <a href="{{ route('menu.index') }}" class="mt-3 inline-flex w-full items-center justify-center rounded-lg bg-white px-3 py-2 text-xs font-bold text-brand-700 transition hover:scale-105">
                    Order now
                </a>
            </div>

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
                <span class="font-display text-xl font-extrabold">
                    Cafe <span class="text-gradient">Delight</span>
                </span>
                <button x-on:click="mobileOpen=false" class="grid h-9 w-9 place-items-center rounded-lg glass">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                <template x-for="item in nav" :key="'m'+item.label">
                    <a :href="item.href" @click="mobileOpen=false"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition"
                       :class="active === item.label
                            ? 'bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-glow'
                            : 'text-ink/65 hover:bg-brand-500/10 hover:text-brand-600 dark:text-orange-50/65'">
                        <i :data-lucide="item.icon" class="h-5 w-5"></i>
                        <span x-text="item.label"></span>
                        <span x-show="item.badgeDynamic"
                              :id="'m-' + item.badgeId"
                              class="ml-auto rounded-full bg-brand-500/15 px-2 py-0.5 text-xs font-bold text-brand-600 hidden"></span>
                        <span x-show="item.badge && !item.badgeDynamic"
                              class="ml-auto rounded-full bg-brand-500/15 px-2 py-0.5 text-xs font-bold text-brand-600"
                              x-text="item.badge"></span>
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

                {{-- Search --}}
                <div class="relative hidden max-w-md flex-1 sm:block">
                    <i data-lucide="search" class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-ink/40 dark:text-orange-50/40"></i>
                    <input type="text" placeholder="Search dishes, orders..."
                           class="w-full rounded-xl border-0 glass py-2.5 pl-11 pr-4 text-sm placeholder:text-ink/40 focus:ring-2 focus:ring-brand-500 dark:placeholder:text-orange-50/30">
                </div>

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
                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-brand-500 ring-2 ring-white dark:ring-ink"></span>
                    </button>

                    {{-- Favorites shortcut --}}
                    <a href="{{ route('favorites.index') }}"
                       class="relative grid h-10 w-10 place-items-center rounded-xl glass transition hover:scale-105"
                       aria-label="Favorites">
                        <i data-lucide="heart" class="h-5 w-5"></i>
                        <span id="fav-topbar-count"
                              class="absolute right-2 top-2 h-5 w-5 rounded-full bg-red-500 text-white text-xs font-bold grid place-items-center hidden">0</span>
                    </a>

                    {{-- Cart --}}
                    <a href="{{ route('cart.index') }}"
                       class="relative grid h-10 w-10 place-items-center rounded-xl glass transition hover:scale-105"
                       aria-label="Cart">
                        <i data-lucide="shopping-bag" class="h-5 w-5"></i>
                        <span id="cart-count"
                              class="absolute right-2 top-2 h-5 w-5 rounded-full bg-brand-500 text-white text-xs font-bold grid place-items-center hidden">0</span>
                    </a>

                    {{-- User menu --}}
                    <div class="relative" x-data="{ open: false }">
                        <button x-on:click="open = !open"
                                class="flex items-center gap-2 rounded-xl glass py-1.5 pl-1.5 pr-3 transition hover:scale-105">
                            <span class="grid h-8 w-8 place-items-center rounded-lg bg-gradient-to-br from-brand-400 to-brand-700 text-sm font-bold text-white">
                                {{ strtoupper(substr(auth()->user()->name ?? 'JD', 0, 2)) }}
                            </span>
                            <span class="hidden text-sm font-bold sm:block">{{ auth()->user()->name ?? 'Jane Doe' }}</span>
                            <i data-lucide="chevron-down" class="hidden h-4 w-4 sm:block"></i>
                        </button>

                        <div x-show="open" x-transition x-on:click.outside="open=false" x-cloak
                             class="absolute right-0 mt-2 w-52 overflow-hidden rounded-2xl glass-strong p-2 shadow-premium">
                            <a href="#" class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-semibold transition hover:bg-brand-500/10 hover:text-brand-600">
                                <i data-lucide="user" class="h-4 w-4"></i> My Profile
                            </a>
                            <a href="{{ route('order.index') }}" class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-semibold transition hover:bg-brand-500/10 hover:text-brand-600">
                                <i data-lucide="shopping-bag" class="h-4 w-4"></i> My Orders
                            </a>
                            <a href="{{ route('favorites.index') }}" class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-semibold transition hover:bg-brand-500/10 hover:text-brand-600">
                                <i data-lucide="heart" class="h-4 w-4"></i> Favorites
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

            {{-- ===== PAGE CONTENT ===== --}}
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
        function appLayout() {
            return {
                darkMode: false,
                collapsed: false,
                mobileOpen: false,
                active: '{{ $activeNav ?? 'Dashboard' }}',

                nav: [
                    { label:'Dashboard', icon:'layout-dashboard', href:'{{ route('dashboard') }}',        badge:'', badgeDynamic:false, badgeId:'' },
                    { label:'My Orders', icon:'shopping-bag',     href:'{{ route('order.index') }}',      badge:'', badgeDynamic:false, badgeId:'' },
                    { label:'Menu',      icon:'utensils',         href:'{{ route('menu.index') }}',        badge:'', badgeDynamic:false, badgeId:'' },
                    { label:'Favorites', icon:'heart',            href:'{{ route('favorites.index') }}',   badge:'', badgeDynamic:true,  badgeId:'fav-sidebar-count' },
                    { label:'Addresses', icon:'map-pin',          href:'#',                               badge:'', badgeDynamic:false, badgeId:'' },
                    { label:'Payments',  icon:'credit-card',      href:'#',                               badge:'', badgeDynamic:false, badgeId:'' },
                    { label:'Profile',   icon:'user',             href:'#',                               badge:'', badgeDynamic:false, badgeId:'' },
                    { label:'Settings',  icon:'settings',         href:'#',                               badge:'', badgeDynamic:false, badgeId:'' },
                ],

                init() {
                    const saved = localStorage.getItem('cd-theme');
                    this.darkMode = saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;

                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                        if (window.AOS) AOS.init({ duration: 600, once: true, easing: 'ease-out-cubic' });
                    });

                    ['darkMode','collapsed','mobileOpen','active'].forEach(prop =>
                        this.$watch(prop, () => this.$nextTick(() => lucide.createIcons()))
                    );

                    updateCartCount();
                    updateFavoriteCount();
                },

                toggleDark() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('cd-theme', this.darkMode ? 'dark' : 'light');
                },
            }
        }

        /* ── Cart badge ─────────────────────────────────── */
        function updateCartCount(count = null) {
            if (count !== null) { applyCartCount(count); return; }
            fetch('/cart/count', { headers: { 'Accept': 'application/json' } })
                .then(r => r.json()).then(d => applyCartCount(d.count)).catch(() => {});
        }
        function applyCartCount(count) {
            const badge = document.getElementById('cart-count');
            if (!badge) return;
            badge.textContent   = count;
            badge.style.display = count > 0 ? 'grid' : 'none';
        }

        /* ── Favorites badge ────────────────────────────── */
        function updateFavoriteCount(count = null) {
            if (count !== null) { applyFavoriteCount(count); return; }
            fetch('/favorites/count', { headers: { 'Accept': 'application/json' } })
                .then(r => r.json()).then(d => applyFavoriteCount(d.count)).catch(() => {});
        }
        function applyFavoriteCount(count) {
            // Topbar heart badge
            const topbar = document.getElementById('fav-topbar-count');
            if (topbar) {
                topbar.textContent   = count;
                topbar.style.display = count > 0 ? 'grid' : 'none';
            }
            // Sidebar badge (desktop)
            const sidebar = document.getElementById('fav-sidebar-count');
            if (sidebar) {
                sidebar.textContent = count;
                sidebar.style.display = count > 0 ? 'inline-flex' : 'none';
            }
            // Sidebar badge (mobile)
            const sidebarM = document.getElementById('m-fav-sidebar-count');
            if (sidebarM) {
                sidebarM.textContent = count;
                sidebarM.style.display = count > 0 ? 'inline-flex' : 'none';
            }
        }

        /* ── Toggle favorite (global, used in menu & show) ─ */
        function toggleFavorite(dishId, btnEl) {
            fetch(`/favorites/toggle/${dishId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                },
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;

                // Update all heart buttons for this dish across the page
                document.querySelectorAll(`[data-dish-fav="${dishId}"]`).forEach(icon => {
                    icon.classList.toggle('fill-red-500', data.favorited);
                    icon.classList.toggle('text-red-500',  data.favorited);
                    // Pop animation
                    icon.classList.remove('heart-pop');
                    void icon.offsetWidth; // reflow
                    icon.classList.add('heart-pop');
                });

                updateFavoriteCount(data.favoriteCount);
                showFavToast(data.message, data.favorited);
            })
            .catch(() => {});
        }

        /* ── Favorite toast ─────────────────────────────── */
        function showFavToast(message, favorited = true) {
            document.querySelectorAll('.fav-toast').forEach(t => t.remove());
            const toast = document.createElement('div');
            toast.className = [
                'fav-toast fixed bottom-6 right-6 z-50',
                'flex items-center gap-3 px-5 py-3 rounded-2xl shadow-xl',
                'text-white font-semibold text-sm',
                favorited ? 'bg-red-500' : 'bg-gray-600',
                'opacity-0 translate-y-2 transition-all duration-300',
            ].join(' ');
            toast.innerHTML = `<span>${favorited ? '❤️' : '🤍'}</span><span>${message}</span>`;
            document.body.appendChild(toast);
            requestAnimationFrame(() => requestAnimationFrame(() => {
                toast.classList.replace('opacity-0', 'opacity-100');
                toast.classList.replace('translate-y-2', 'translate-y-0');
            }));
            setTimeout(() => {
                toast.classList.replace('opacity-100', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 2500);
        }
    </script>

    @stack('scripts')
</body>
</html>s