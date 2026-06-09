<!DOCTYPE html>
<html lang="en" x-data="cafeDelight()" :class="{ 'dark': darkMode }" x-cloak class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cafe Delight — premium shawarma, biryani, chips, chapati, fresh juices, pastries, cakes & all your favorite snacks. Fast delivery, world-class taste.">
    <title>Cafe Delight — Premium Shawarma, Biryani & Fast Foods</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;0,900;1,600&display=swap" rel="stylesheet">

    {{-- Tailwind CSS (Play CDN) --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>

    {{-- AOS scroll animations --}}
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    {{-- Swiper.js carousel --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

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
                        brand: {
                            50:  '#fff7ed', 100: '#ffedd5', 200: '#fed7aa', 300: '#fdba74',
                            400: '#fb923c', 500: '#f97316', 600: '#ea580c', 700: '#c2410c',
                            800: '#9a3412', 900: '#7c2d12',
                        },
                        gold: '#e6b450',
                        ink: '#0b0a09',
                    },
                    boxShadow: {
                        glow: '0 0 40px -10px rgba(249,115,22,0.55)',
                        premium: '0 30px 60px -15px rgba(0,0,0,0.35)',
                        soft: '0 10px 40px -12px rgba(0,0,0,0.18)',
                    },
                    keyframes: {
                        float: { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-18px)' } },
                        floatSlow: { '0%,100%': { transform: 'translateY(0) rotate(0deg)' }, '50%': { transform: 'translateY(-26px) rotate(3deg)' } },
                        shimmer: { '0%': { backgroundPosition: '-200% 0' }, '100%': { backgroundPosition: '200% 0' } },
                        spinSlow: { to: { transform: 'rotate(360deg)' } },
                    },
                    animation: {
                        float: 'float 6s ease-in-out infinite',
                        'float-slow': 'floatSlow 9s ease-in-out infinite',
                        shimmer: 'shimmer 3s linear infinite',
                        'spin-slow': 'spinSlow 22s linear infinite',
                    },
                },
            },
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
        body { -webkit-font-smoothing: antialiased; }

        ::-webkit-scrollbar { width: 11px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(#f97316,#ea580c); border-radius: 99px; border: 3px solid transparent; background-clip: content-box; }

        .glass {
            background: rgba(255,255,255,0.55);
            backdrop-filter: blur(18px) saturate(160%);
            -webkit-backdrop-filter: blur(18px) saturate(160%);
            border: 1px solid rgba(255,255,255,0.5);
        }
        .dark .glass {
            background: rgba(20,18,16,0.55);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .glass-strong {
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(22px) saturate(180%);
            -webkit-backdrop-filter: blur(22px) saturate(180%);
        }
        .dark .glass-strong { background: rgba(18,16,14,0.78); }

        .text-gradient {
            background: linear-gradient(120deg,#fb923c,#ea580c 40%,#e6b450);
            -webkit-background-clip: text; background-clip: text; color: transparent;
        }
        .mesh {
            background:
                radial-gradient(45rem 45rem at 80% -10%, rgba(249,115,22,0.18), transparent 60%),
                radial-gradient(40rem 40rem at 0% 20%, rgba(230,180,80,0.16), transparent 60%),
                radial-gradient(50rem 50rem at 50% 120%, rgba(234,88,12,0.14), transparent 60%);
        }
        .card-tilt { transition: transform .45s cubic-bezier(.22,1,.36,1), box-shadow .45s; }
        .card-tilt:hover { transform: translateY(-10px); }
        .swiper-pagination-bullet { background: #f97316 !important; }
        .swiper-button-next, .swiper-button-prev { color: #f97316 !important; }
        .underline-grow { position: relative; }
        .underline-grow::after { content:''; position:absolute; left:0; bottom:-4px; height:2px; width:0; background:linear-gradient(90deg,#fb923c,#e6b450); transition:width .35s ease; }
        .underline-grow:hover::after { width:100%; }
        .reveal-img { clip-path: inset(0 0 0 0); }
    </style>
</head>

<body class="font-sans bg-[#fbf7f1] text-ink dark:bg-ink dark:text-orange-50 antialiased selection:bg-brand-500 selection:text-white overflow-x-hidden">

    {{-- ============ DECORATIVE BACKGROUND BLOBS ============ --}}
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10 mesh"></div>
    <div aria-hidden="true" class="pointer-events-none fixed -top-24 -left-24 h-96 w-96 rounded-full bg-brand-400/30 blur-3xl -z-10 animate-float-slow"></div>
    <div aria-hidden="true" class="pointer-events-none fixed top-1/3 -right-32 h-[28rem] w-[28rem] rounded-full bg-gold/25 blur-3xl -z-10 animate-float"></div>

    {{-- ============ SCROLL PROGRESS BAR ============ --}}
    <div class="fixed top-0 left-0 z-[60] h-1 bg-gradient-to-r from-brand-400 via-brand-600 to-gold" :style="`width:${scrollProgress}%`"></div>

    {{-- ================= NAVBAR ================= --}}
    <header class="fixed inset-x-0 top-0 z-50 transition-all duration-500"
            :class="scrolled ? 'py-2' : 'py-4'">
        <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between rounded-2xl px-4 sm:px-6 transition-all duration-500"
                 :class="scrolled ? 'glass-strong shadow-soft py-2.5' : 'py-3'">
                {{-- Logo --}}
                <a href="#home" class="flex items-center gap-2.5 group">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-glow transition-transform duration-500 group-hover:rotate-12">
                        <i data-lucide="utensils-crossed" class="h-6 w-6"></i>
                    </span>
                    <span class="flex flex-col leading-none">
                        <span class="font-display text-xl font-extrabold tracking-tight">Cafe <span class="text-gradient">Delight</span></span>
                        <span class="text-[10px] font-semibold uppercase tracking-[0.25em] text-brand-600/80 dark:text-brand-300/80">Taste the joy</span>
                    </span>
                </a>

                {{-- Desktop links --}}
                <ul class="hidden items-center gap-8 lg:flex">
                    <template x-for="link in navLinks" :key="link.href">
                        <li>
                            <a :href="link.href" x-text="link.label"
                               class="underline-grow text-sm font-semibold text-ink/70 transition hover:text-brand-600 dark:text-orange-50/70 dark:hover:text-brand-300"></a>
                        </li>
                    </template>
                </ul>

                {{-- Actions --}}
                <div class="flex items-center gap-2 sm:gap-3">
                    <button x-on:click="toggleDark()" aria-label="Toggle dark mode"
                            class="grid h-10 w-10 place-items-center rounded-xl glass transition hover:scale-110 active:scale-95">
                        <i x-show="!darkMode" data-lucide="moon" class="h-5 w-5"></i>
                        <i x-show="darkMode" data-lucide="sun" class="h-5 w-5 text-gold"></i>
                    </button>

                    {{-- Laravel default auth links --}}
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 rounded-xl glass px-4 py-2.5 text-sm font-bold transition hover:scale-105 active:scale-95">
                                <i data-lucide="layout-dashboard" class="h-4 w-4"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-1.5 rounded-xl px-4 py-2.5 text-sm font-bold text-ink/70 transition hover:text-brand-600 dark:text-orange-50/70 dark:hover:text-brand-300">
                                <i data-lucide="log-in" class="h-4 w-4"></i> Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="hidden sm:inline-flex items-center gap-1.5 rounded-xl glass px-4 py-2.5 text-sm font-bold transition hover:scale-105 active:scale-95">
                                    <i data-lucide="user-plus" class="h-4 w-4"></i> Register
                                </a>
                            @endif
                        @endauth
                    @endif

                    <a href="#menu" class="hidden sm:inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-5 py-2.5 text-sm font-bold text-white shadow-glow transition hover:scale-105 active:scale-95">
                        <i data-lucide="shopping-bag" class="h-4 w-4"></i> Order Now
                    </a>
                    <button x-on:click="mobileOpen = !mobileOpen" aria-label="Menu"
                            class="grid h-10 w-10 place-items-center rounded-xl glass lg:hidden">
                        <i x-show="!mobileOpen" data-lucide="menu" class="h-5 w-5"></i>
                        <i x-show="mobileOpen" data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div x-show="mobileOpen" x-collapse x-cloak class="lg:hidden">
                <div class="mt-2 glass-strong rounded-2xl p-4 shadow-soft">
                    <template x-for="link in navLinks" :key="link.href">
                        <a :href="link.href" x-text="link.label" x-on:click="mobileOpen=false"
                           class="block rounded-xl px-4 py-3 text-sm font-semibold transition hover:bg-brand-500/10 hover:text-brand-600"></a>
                    </template>
                    <a href="#menu" x-on:click="mobileOpen=false" class="mt-2 flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-5 py-3 text-sm font-bold text-white">
                        <i data-lucide="shopping-bag" class="h-4 w-4"></i> Order Now
                    </a>

                    {{-- Laravel default auth links (mobile) --}}
                    @if (Route::has('login'))
                        <div class="mt-2 grid grid-cols-2 gap-2 border-t border-brand-500/15 pt-3">
                            @auth
                                <a href="{{ url('/dashboard') }}" x-on:click="mobileOpen=false" class="col-span-2 flex items-center justify-center gap-2 rounded-xl glass px-5 py-3 text-sm font-bold">
                                    <i data-lucide="layout-dashboard" class="h-4 w-4"></i> Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" x-on:click="mobileOpen=false" class="flex items-center justify-center gap-2 rounded-xl glass px-5 py-3 text-sm font-bold">
                                    <i data-lucide="log-in" class="h-4 w-4"></i> Login
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" x-on:click="mobileOpen=false" class="flex items-center justify-center gap-2 rounded-xl border border-brand-500/40 px-5 py-3 text-sm font-bold text-brand-600">
                                        <i data-lucide="user-plus" class="h-4 w-4"></i> Register
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>
    </header>

    {{-- ================= HERO ================= --}}
    <section id="home" class="relative min-h-screen overflow-hidden pt-28 lg:pt-36">
        {{-- Parallax layers --}}
        <div data-parallax="0.25" class="pointer-events-none absolute -top-10 right-[8%] hidden h-72 w-72 rounded-full bg-gradient-to-br from-brand-400/40 to-gold/30 blur-2xl md:block"></div>

        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
            {{-- Copy --}}
            <div class="relative z-10">
                <span data-aos="fade-up" class="inline-flex items-center gap-2 rounded-full glass px-4 py-2 text-xs font-bold uppercase tracking-widest text-brand-700 dark:text-brand-300">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-brand-500 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-brand-600"></span>
                    </span>
                    Now delivering in 30 mins
                </span>

                <h1 data-aos="fade-up" data-aos-delay="80" class="mt-6 font-display text-5xl font-extrabold leading-[1.05] tracking-tight sm:text-6xl xl:text-7xl">
                    Crave it. <br>
                    <span class="text-gradient">Taste</span> the Delight.
                </h1>

                <p data-aos="fade-up" data-aos-delay="160" class="mt-6 max-w-xl text-lg text-ink/70 dark:text-orange-50/70">
                    From sizzling <strong class="text-brand-600 dark:text-brand-300">shawarma</strong> and aromatic
                    <strong class="text-brand-600 dark:text-brand-300">biryani</strong> to crispy chips, soft chapati,
                    fresh juices, pastries, cakes &amp; every snack you love — handcrafted daily and delivered hot to your door.
                </p>

                <div data-aos="fade-up" data-aos-delay="240" class="mt-9 flex flex-wrap items-center gap-4">
                    <a href="#menu" class="group inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 px-7 py-4 text-base font-bold text-white shadow-glow transition hover:scale-105 active:scale-95">
                        Explore Menu
                        <i data-lucide="arrow-right" class="h-5 w-5 transition-transform group-hover:translate-x-1"></i>
                    </a>
                    <a href="#gallery" class="inline-flex items-center gap-2 rounded-2xl glass px-7 py-4 text-base font-bold transition hover:scale-105 active:scale-95">
                        <span class="grid h-8 w-8 place-items-center rounded-full bg-brand-500 text-white"><i data-lucide="play" class="h-4 w-4"></i></span>
                        Food Gallery
                    </a>
                </div>

                {{-- mini trust row --}}
                <div data-aos="fade-up" data-aos-delay="320" class="mt-10 flex items-center gap-6">
                    <div class="flex -space-x-3">
                        <template x-for="n in 4" :key="n">
                            <span class="grid h-10 w-10 place-items-center rounded-full border-2 border-white bg-gradient-to-br from-brand-300 to-brand-600 text-xs font-bold text-white dark:border-ink">
                                <i data-lucide="user" class="h-4 w-4"></i>
                            </span>
                        </template>
                    </div>
                    <div>
                        <div class="flex items-center gap-1 text-gold">
                            <template x-for="s in 5" :key="s"><i data-lucide="star" class="h-4 w-4 fill-current"></i></template>
                        </div>
                        <p class="text-sm font-medium text-ink/60 dark:text-orange-50/60">Loved by <strong>12,000+</strong> happy foodies</p>
                    </div>
                </div>
            </div>

            {{-- Visual: floating food cards --}}
            <div class="relative h-[480px] sm:h-[560px]">
                {{-- central plate --}}
                <div class="absolute left-1/2 top-1/2 h-72 w-72 -translate-x-1/2 -translate-y-1/2 rounded-full bg-gradient-to-br from-brand-400 via-brand-500 to-brand-700 shadow-premium animate-spin-slow sm:h-80 sm:w-80"></div>
                <div class="absolute left-1/2 top-1/2 grid h-60 w-60 -translate-x-1/2 -translate-y-1/2 place-items-center rounded-full glass-strong shadow-premium sm:h-64 sm:w-64">
                    <div class="text-center">
                        <i data-lucide="chef-hat" class="mx-auto h-14 w-14 text-brand-600"></i>
                        <p class="mt-2 font-display text-2xl font-bold">Fresh<br>Everyday</p>
                    </div>
                </div>

                {{-- floating cards --}}
                <template x-for="(card, i) in floatCards" :key="card.name">
                    <div class="float-card absolute" :style="card.pos">
                        <div class="card-tilt flex items-center gap-3 rounded-2xl glass-strong p-3 pr-5 shadow-premium">
                            <span class="grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-brand-100 to-brand-200 text-2xl dark:from-brand-500/30 dark:to-brand-700/30" x-text="card.emoji"></span>
                            <div>
                                <p class="text-sm font-bold leading-none" x-text="card.name"></p>
                                <p class="mt-1 text-xs font-semibold text-brand-600" x-text="card.price"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- marquee strip --}}
        <div class="mt-16 overflow-hidden border-y border-brand-500/15 bg-gradient-to-r from-brand-500/5 via-transparent to-brand-500/5 py-4">
            <div class="flex animate-[shimmer_1s] whitespace-nowrap">
                <div class="flex shrink-0 items-center gap-10 pr-10 text-lg font-display font-bold text-ink/40 dark:text-orange-50/40" style="animation: marquee 28s linear infinite;">
                    <template x-for="word in marquee" :key="word">
                        <span class="flex items-center gap-10"><span x-text="word"></span><i data-lucide="dot" class="h-6 w-6 text-brand-500"></i></span>
                    </template>
                </div>
                <div class="flex shrink-0 items-center gap-10 pr-10 text-lg font-display font-bold text-ink/40 dark:text-orange-50/40" style="animation: marquee 28s linear infinite;" aria-hidden="true">
                    <template x-for="word in marquee" :key="'b'+word">
                        <span class="flex items-center gap-10"><span x-text="word"></span><i data-lucide="dot" class="h-6 w-6 text-brand-500"></i></span>
                    </template>
                </div>
            </div>
        </div>
        <style>@keyframes marquee { from{transform:translateX(0)} to{transform:translateX(-100%)} }</style>
    </section>

    {{-- ================= FEATURES / WHY US ================= --}}
    <section id="about" class="relative py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p data-aos="fade-up" class="text-sm font-bold uppercase tracking-[0.3em] text-brand-600">Why Cafe Delight</p>
                <h2 data-aos="fade-up" data-aos-delay="80" class="mt-3 font-display text-4xl font-extrabold sm:text-5xl">Crafted for <span class="text-gradient">flavor lovers</span></h2>
                <p data-aos="fade-up" data-aos-delay="160" class="mt-4 text-ink/65 dark:text-orange-50/65">Premium ingredients, bold recipes, and lightning-fast delivery — every single time.</p>
            </div>

            <div class="mt-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <template x-for="(f, i) in features" :key="f.title">
                    <div data-aos="fade-up" :data-aos-delay="i*100"
                         class="card-tilt group rounded-3xl glass p-7 shadow-soft hover:shadow-premium">
                        <span class="grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-glow transition-transform duration-500 group-hover:-rotate-6 group-hover:scale-110">
                            <i :data-lucide="f.icon" class="h-7 w-7"></i>
                        </span>
                        <h3 class="mt-5 text-lg font-bold" x-text="f.title"></h3>
                        <p class="mt-2 text-sm text-ink/60 dark:text-orange-50/60" x-text="f.desc"></p>
                    </div>
                </template>
            </div>
        </div>
    </section>

    {{-- ================= MENU SHOWCASE ================= --}}
    <section id="menu" class="relative py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-6 lg:flex-row lg:items-end">
                <div class="max-w-xl text-center lg:text-left">
                    <p data-aos="fade-up" class="text-sm font-bold uppercase tracking-[0.3em] text-brand-600">Our Menu</p>
                    <h2 data-aos="fade-up" data-aos-delay="80" class="mt-3 font-display text-4xl font-extrabold sm:text-5xl">A feast for <span class="text-gradient">every craving</span></h2>
                </div>
                {{-- category filter --}}
                <div data-aos="fade-up" data-aos-delay="120" class="flex flex-wrap justify-center gap-2 rounded-2xl glass p-2">
                    <template x-for="cat in categories" :key="cat">
                        <button x-on:click="activeCat = cat"
                                class="rounded-xl px-4 py-2 text-sm font-bold transition"
                                :class="activeCat === cat ? 'bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-glow' : 'text-ink/60 hover:text-brand-600 dark:text-orange-50/60'"
                                x-text="cat"></button>
                    </template>
                </div>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <template x-for="item in filteredMenu" :key="item.name">
                    <div x-show="activeCat === 'All' || item.cat === activeCat"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 translate-y-6"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="card-tilt group overflow-hidden rounded-3xl glass shadow-soft hover:shadow-premium">
                        <div class="relative h-44 overflow-hidden bg-gradient-to-br from-brand-200 to-brand-400 dark:from-brand-700/40 dark:to-brand-900/40">
                            <span class="absolute inset-0 grid place-items-center text-7xl transition-transform duration-700 group-hover:scale-125 group-hover:rotate-6" x-text="item.emoji"></span>
                            <span class="absolute left-4 top-4 rounded-full glass-strong px-3 py-1 text-xs font-bold text-brand-700 dark:text-brand-200" x-text="item.cat"></span>
                            <span x-show="item.tag" class="absolute right-4 top-4 rounded-full bg-brand-600 px-3 py-1 text-xs font-bold text-white" x-text="item.tag"></span>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-bold" x-text="item.name"></h3>
                                <span class="shrink-0 rounded-lg bg-brand-500/10 px-2.5 py-1 text-sm font-extrabold text-brand-600" x-text="item.price"></span>
                            </div>
                            <p class="mt-2 text-sm text-ink/60 dark:text-orange-50/60" x-text="item.desc"></p>
                            <div class="mt-5 flex items-center justify-between">
                                <div class="flex items-center gap-1 text-gold">
                                    <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                                    <span class="text-sm font-bold text-ink/70 dark:text-orange-50/70" x-text="item.rating"></span>
                                </div>
                                <button class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-2 text-sm font-bold text-white transition hover:scale-105 active:scale-95">
                                    <i data-lucide="plus" class="h-4 w-4"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    {{-- ================= STATS COUNTERS ================= --}}
    <section class="relative py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 rounded-[2rem] glass-strong p-10 shadow-premium sm:grid-cols-2 lg:grid-cols-4"
                 x-intersect.once="startCounters()">
                <template x-for="stat in stats" :key="stat.label">
                    <div class="text-center">
                        <p class="font-display text-5xl font-extrabold text-gradient">
                            <span x-text="stat.current.toLocaleString()"></span><span x-text="stat.suffix"></span>
                        </p>
                        <p class="mt-2 text-sm font-semibold uppercase tracking-wider text-ink/60 dark:text-orange-50/60" x-text="stat.label"></p>
                    </div>
                </template>
            </div>
        </div>
    </section>

    {{-- ================= FOOD GALLERY (Swiper) ================= --}}
    <section id="gallery" class="relative py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p data-aos="fade-up" class="text-sm font-bold uppercase tracking-[0.3em] text-brand-600">Food Gallery</p>
                <h2 data-aos="fade-up" data-aos-delay="80" class="mt-3 font-display text-4xl font-extrabold sm:text-5xl">Eat with your <span class="text-gradient">eyes first</span></h2>
            </div>
        </div>
        <div data-aos="fade-up" class="swiper gallery-swiper mt-14 px-4 sm:px-6 lg:px-8">
            <div class="swiper-wrapper">
                <template x-for="g in gallery" :key="g.name">
                    <div class="swiper-slide !w-72">
                        <div class="card-tilt group overflow-hidden rounded-3xl shadow-premium">
                            <div class="relative grid h-80 place-items-center bg-gradient-to-br" :class="g.bg">
                                <span class="text-8xl transition-transform duration-700 group-hover:scale-110" x-text="g.emoji"></span>
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-5">
                                    <p class="font-display text-xl font-bold text-white" x-text="g.name"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="swiper-pagination !bottom-0 mt-6"></div>
        </div>
    </section>

    {{-- ================= DELIVERY HIGHLIGHT ================= --}}
    <section class="relative py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-12 overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-brand-500 via-brand-600 to-brand-800 p-10 shadow-premium lg:grid-cols-2 lg:p-16">
                <div class="text-white">
                    <p data-aos="fade-right" class="text-sm font-bold uppercase tracking-[0.3em] text-orange-100/80">Lightning Delivery</p>
                    <h2 data-aos="fade-right" data-aos-delay="80" class="mt-3 font-display text-4xl font-extrabold sm:text-5xl">Hot &amp; fresh at your door in 30 minutes</h2>
                    <p data-aos="fade-right" data-aos-delay="160" class="mt-5 max-w-lg text-orange-50/90">Real-time tracking, contactless delivery, and a piping-hot guarantee. Order from anywhere, anytime.</p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="#" class="inline-flex items-center gap-2 rounded-2xl bg-white px-6 py-3.5 text-sm font-bold text-brand-700 transition hover:scale-105 active:scale-95">
                            <i data-lucide="smartphone" class="h-5 w-5"></i> Get the App
                        </a>
                        <a href="#menu" class="inline-flex items-center gap-2 rounded-2xl border border-white/40 px-6 py-3.5 text-sm font-bold text-white transition hover:bg-white/10">
                            <i data-lucide="map-pin" class="h-5 w-5"></i> Track Order
                        </a>
                    </div>
                </div>
                <div data-aos="fade-left" class="relative">
                    <div class="mx-auto grid max-w-sm gap-4">
                        <template x-for="d in delivery" :key="d.title">
                            <div class="flex items-center gap-4 rounded-2xl glass-strong p-4 shadow-soft">
                                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white"><i :data-lucide="d.icon" class="h-6 w-6"></i></span>
                                <div>
                                    <p class="font-bold" x-text="d.title"></p>
                                    <p class="text-sm text-ink/60 dark:text-orange-50/60" x-text="d.desc"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= TESTIMONIALS (Swiper) ================= --}}
    <section id="reviews" class="relative py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p data-aos="fade-up" class="text-sm font-bold uppercase tracking-[0.3em] text-brand-600">Testimonials</p>
                <h2 data-aos="fade-up" data-aos-delay="80" class="mt-3 font-display text-4xl font-extrabold sm:text-5xl">What our <span class="text-gradient">foodies say</span></h2>
            </div>

            <div data-aos="fade-up" class="swiper review-swiper mt-14">
                <div class="swiper-wrapper pb-14">
                    <template x-for="t in testimonials" :key="t.name">
                        <div class="swiper-slide">
                            <div class="h-full rounded-3xl glass p-8 shadow-soft">
                                <i data-lucide="quote" class="h-9 w-9 text-brand-500/40"></i>
                                <p class="mt-4 text-ink/75 dark:text-orange-50/75" x-text="t.text"></p>
                                <div class="mt-6 flex items-center gap-4">
                                    <span class="grid h-12 w-12 place-items-center rounded-full bg-gradient-to-br from-brand-400 to-brand-700 text-lg font-bold text-white" x-text="t.initials"></span>
                                    <div>
                                        <p class="font-bold" x-text="t.name"></p>
                                        <div class="flex text-gold">
                                            <template x-for="s in 5" :key="s"><i data-lucide="star" class="h-4 w-4 fill-current"></i></template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    {{-- ================= CTA ================= --}}
    <section class="relative py-20">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-[2.5rem] glass-strong p-12 text-center shadow-premium sm:p-16">
                <div aria-hidden="true" class="pointer-events-none absolute -top-16 -right-16 h-56 w-56 rounded-full bg-brand-500/30 blur-3xl"></div>
                <div aria-hidden="true" class="pointer-events-none absolute -bottom-16 -left-16 h-56 w-56 rounded-full bg-gold/30 blur-3xl"></div>
                <h2 data-aos="zoom-in" class="font-display text-4xl font-extrabold sm:text-5xl">Hungry? <span class="text-gradient">Let's fix that.</span></h2>
                <p data-aos="zoom-in" data-aos-delay="80" class="mx-auto mt-4 max-w-xl text-ink/65 dark:text-orange-50/65">Order your favorites in seconds and get them delivered hot. Your next delicious meal is one click away.</p>
                <div data-aos="zoom-in" data-aos-delay="160" class="mt-9 flex flex-wrap justify-center gap-4">
                    <a href="#menu" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 px-8 py-4 text-base font-bold text-white shadow-glow transition hover:scale-105 active:scale-95">
                        <i data-lucide="shopping-bag" class="h-5 w-5"></i> Order Now
                    </a>
                    <a href="tel:+254700000000" class="inline-flex items-center gap-2 rounded-2xl glass px-8 py-4 text-base font-bold transition hover:scale-105 active:scale-95">
                        <i data-lucide="phone" class="h-5 w-5"></i> Call to Order
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= FOOTER ================= --}}
    <footer class="relative mt-10 border-t border-brand-500/15 pt-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-4">
                <div class="lg:col-span-1">
                    <a href="#home" class="flex items-center gap-2.5">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-glow"><i data-lucide="utensils-crossed" class="h-6 w-6"></i></span>
                        <span class="font-display text-xl font-extrabold">Cafe <span class="text-gradient">Delight</span></span>
                    </a>
                    <p class="mt-4 text-sm text-ink/60 dark:text-orange-50/60">Premium shawarma, biryani, snacks &amp; sweet treats — handcrafted with love and delivered hot.</p>
                    <div class="mt-5 flex gap-3">
                        <template x-for="s in socials" :key="s">
                            <a href="#" class="grid h-10 w-10 place-items-center rounded-xl glass transition hover:scale-110 hover:text-brand-600"><i :data-lucide="s" class="h-5 w-5"></i></a>
                        </template>
                    </div>
                </div>

                <template x-for="col in footerCols" :key="col.title">
                    <div>
                        <h4 class="font-bold" x-text="col.title"></h4>
                        <ul class="mt-4 space-y-3">
                            <template x-for="l in col.links" :key="l">
                                <li><a href="#" class="underline-grow text-sm text-ink/60 transition hover:text-brand-600 dark:text-orange-50/60" x-text="l"></a></li>
                            </template>
                        </ul>
                    </div>
                </template>

                <div>
                    <h4 class="font-bold">Get tasty updates</h4>
                    <p class="mt-4 text-sm text-ink/60 dark:text-orange-50/60">Subscribe for offers &amp; new menu drops.</p>
                    <form x-on:submit.prevent="subscribed=true" class="mt-4 flex gap-2">
                        <input type="email" required placeholder="you@email.com"
                               class="w-full rounded-xl border-0 glass px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500">
                        <button class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 text-white transition hover:scale-105 active:scale-95">
                            <i data-lucide="send" class="h-5 w-5"></i>
                        </button>
                    </form>
                    <p x-show="subscribed" x-transition class="mt-2 text-sm font-semibold text-brand-600">Thanks — you're on the list! 🎉</p>
                </div>
            </div>

            <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-brand-500/15 py-7 text-sm text-ink/55 dark:text-orange-50/55 sm:flex-row">
                <p>&copy; <span x-text="new Date().getFullYear()"></span> Cafe Delight. All rights reserved.</p>
                <p class="flex items-center gap-1.5">Made with <i data-lucide="heart" class="h-4 w-4 fill-brand-500 text-brand-500"></i> for food lovers</p>
            </div>
        </div>
    </footer>

    {{-- Back to top --}}
    <button x-show="scrolled" x-transition x-on:click="scrollToTop()" aria-label="Back to top"
            class="fixed bottom-6 right-6 z-50 grid h-12 w-12 place-items-center rounded-full bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-glow transition hover:scale-110 active:scale-95">
        <i data-lucide="arrow-up" class="h-5 w-5"></i>
    </button>

    {{-- ================= SCRIPTS ================= --}}
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    {{-- Alpine plugins must load before core --}}
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        function cafeDelight() {
            return {
                darkMode: false,
                mobileOpen: false,
                scrolled: false,
                scrollProgress: 0,
                activeCat: 'All',
                subscribed: false,

                navLinks: [
                    { href: '#home', label: 'Home' },
                    { href: '#about', label: 'Why Us' },
                    { href: '#menu', label: 'Menu' },
                    { href: '#gallery', label: 'Gallery' },
                    { href: '#reviews', label: 'Reviews' },
                ],

                marquee: ['Shawarma','Biryani','Chapati','Fresh Juices','Pastries','Cakes','Chips','Sodas','Snacks','Fast Foods'],

                floatCards: [
                    { name: 'Beef Shawarma', price: 'KSh 350', emoji: '🌯', pos: 'top:2%;left:-2%;'   , },
                    { name: 'Chicken Biryani', price: 'KSh 550', emoji: '🍛', pos: 'top:20%;right:-4%;' },
                    { name: 'Fresh Juice', price: 'KSh 200', emoji: '🥤', pos: 'bottom:14%;left:-4%;' },
                    { name: 'Choco Cake', price: 'KSh 450', emoji: '🍰', pos: 'bottom:0%;right:2%;' },
                ],

                features: [
                    { icon: 'leaf', title: 'Fresh Ingredients', desc: 'Sourced daily from trusted local markets for peak flavor.' },
                    { icon: 'flame', title: 'Bold Recipes', desc: 'Authentic spices and secret marinades in every bite.' },
                    { icon: 'bike', title: '30-Min Delivery', desc: 'Hot and fast, straight to your doorstep — guaranteed.' },
                    { icon: 'badge-check', title: 'Hygiene First', desc: 'Certified kitchens with the highest safety standards.' },
                ],

                categories: ['All','Shawarma','Biryani','Snacks','Drinks','Bakery'],
                menu: [
                    { name:'Beef Shawarma', cat:'Shawarma', price:'KSh 350', rating:'4.9', emoji:'🌯', tag:'Bestseller', desc:'Tender beef, garlic sauce & fresh veggies in soft pita.' },
                    { name:'Chicken Shawarma', cat:'Shawarma', price:'KSh 320', rating:'4.8', emoji:'🥙', tag:'', desc:'Juicy grilled chicken wrapped with crunchy salad.' },
                    { name:'Chicken Biryani', cat:'Biryani', price:'KSh 550', rating:'5.0', emoji:'🍛', tag:'Chef’s Pick', desc:'Fragrant basmati layered with spiced chicken.' },
                    { name:'Beef Pilau', cat:'Biryani', price:'KSh 480', rating:'4.7', emoji:'🍲', tag:'', desc:'Slow-cooked aromatic rice with tender beef.' },
                    { name:'Crispy Chips', cat:'Snacks', price:'KSh 150', rating:'4.6', emoji:'🍟', tag:'', desc:'Golden, crunchy fries with our signature dip.' },
                    { name:'Soft Chapati', cat:'Snacks', price:'KSh 50', rating:'4.8', emoji:'🫓', tag:'', desc:'Flaky, layered chapati made fresh on the griddle.' },
                    { name:'Samosa Trio', cat:'Snacks', price:'KSh 120', rating:'4.7', emoji:'🥟', tag:'', desc:'Crispy pastry packed with spiced filling.' },
                    { name:'Fresh Mango Juice', cat:'Drinks', price:'KSh 200', rating:'4.9', emoji:'🥭', tag:'Fresh', desc:'Pure blended mango — no added sugar.' },
                    { name:'Cold Soda', cat:'Drinks', price:'KSh 100', rating:'4.5', emoji:'🥤', tag:'', desc:'Ice-cold fizzy refreshment, your choice.' },
                    { name:'Chocolate Cake', cat:'Bakery', price:'KSh 450', rating:'5.0', emoji:'🍰', tag:'New', desc:'Rich, moist layers of decadent chocolate.' },
                    { name:'Butter Croissant', cat:'Bakery', price:'KSh 180', rating:'4.8', emoji:'🥐', tag:'', desc:'Flaky, buttery & baked golden every morning.' },
                    { name:'Glazed Donut', cat:'Bakery', price:'KSh 130', rating:'4.7', emoji:'🍩', tag:'', desc:'Soft pillowy donut with a sweet glaze.' },
                ],

                stats: [
                    { label:'Happy Customers', target:12000, current:0, suffix:'+' },
                    { label:'Menu Dishes', target:120, current:0, suffix:'+' },
                    { label:'Cities Served', target:15, current:0, suffix:'' },
                    { label:'Avg Delivery (min)', target:30, current:0, suffix:'' },
                ],

                gallery: [
                    { name:'Shawarma',  emoji:'🌯', bg:'from-amber-400 to-orange-600' },
                    { name:'Biryani',   emoji:'🍛', bg:'from-orange-400 to-red-600' },
                    { name:'Fresh Juice', emoji:'🧃', bg:'from-lime-400 to-emerald-600' },
                    { name:'Cakes',     emoji:'🎂', bg:'from-pink-400 to-rose-600' },
                    { name:'Burgers',   emoji:'🍔', bg:'from-yellow-400 to-orange-600' },
                    { name:'Pastries',  emoji:'🥐', bg:'from-amber-300 to-amber-600' },
                    { name:'Pizza',     emoji:'🍕', bg:'from-red-400 to-orange-600' },
                ],

                delivery: [
                    { icon:'navigation', title:'Live Tracking', desc:'Follow your order in real time.' },
                    { icon:'shield-check', title:'Hot Guarantee', desc:'Fresh & hot or it’s free.' },
                    { icon:'wallet', title:'Easy Payments', desc:'M-Pesa, card or cash on delivery.' },
                ],

                testimonials: [
                    { name:'Amina K.', initials:'AK', text:'The beef shawarma is unreal — easily the best in town. Delivery was crazy fast and still piping hot!' },
                    { name:'Brian O.', initials:'BO', text:'Their chicken biryani tastes like home. I order every weekend without fail. 10/10 service.' },
                    { name:'Cynthia M.', initials:'CM', text:'Fresh juices, gorgeous cakes, and the app makes ordering effortless. Cafe Delight is my go-to.' },
                    { name:'David W.', initials:'DW', text:'Premium quality at fair prices. The packaging is beautiful and everything arrives perfectly fresh.' },
                    { name:'Esther N.', initials:'EN', text:'From chips to chapati to pastries — every single thing is delicious. Customer for life!' },
                ],

                socials: ['instagram','facebook','twitter','youtube'],
                footerCols: [
                    { title:'Explore', links:['Home','Menu','Gallery','Reviews','About Us'] },
                    { title:'Menu', links:['Shawarma','Biryani','Snacks','Drinks','Bakery'] },
                ],

                get filteredMenu() { return this.menu; },

                init() {
                    // theme
                    const saved = localStorage.getItem('cd-theme');
                    this.darkMode = saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;

                    // scroll handling
                    const onScroll = () => {
                        this.scrolled = window.scrollY > 40;
                        const h = document.documentElement.scrollHeight - window.innerHeight;
                        this.scrollProgress = h > 0 ? (window.scrollY / h) * 100 : 0;
                    };
                    window.addEventListener('scroll', onScroll, { passive: true });
                    onScroll();

                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                        if (window.AOS) AOS.init({ duration: 800, once: true, easing: 'ease-out-cubic', offset: 80 });
                        this.initSwipers();
                        this.initGsap();
                        // re-render icons after dynamic templates settle
                        setTimeout(() => window.lucide && lucide.createIcons(), 200);
                    });
                },

                toggleDark() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('cd-theme', this.darkMode ? 'dark' : 'light');
                },

                scrollToTop() { window.scrollTo({ top: 0, behavior: 'smooth' }); },

                startCounters() {
                    this.stats.forEach((stat) => {
                        const duration = 1800, start = performance.now();
                        const step = (now) => {
                            const p = Math.min((now - start) / duration, 1);
                            const eased = 1 - Math.pow(1 - p, 3);
                            stat.current = Math.floor(eased * stat.target);
                            if (p < 1) requestAnimationFrame(step);
                            else stat.current = stat.target;
                        };
                        requestAnimationFrame(step);
                    });
                },

                initSwipers() {
                    if (!window.Swiper) return;
                    new Swiper('.gallery-swiper', {
                        slidesPerView: 'auto', spaceBetween: 20, centeredSlides: false, grabCursor: true, loop: true,
                        autoplay: { delay: 2200, disableOnInteraction: false },
                        pagination: { el: '.gallery-swiper .swiper-pagination', clickable: true },
                    });
                    new Swiper('.review-swiper', {
                        slidesPerView: 1, spaceBetween: 24, grabCursor: true, loop: true,
                        autoplay: { delay: 4000, disableOnInteraction: false },
                        pagination: { el: '.review-swiper .swiper-pagination', clickable: true },
                        breakpoints: { 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } },
                    });
                    // Swiper autoplay needs the plugin; bundle includes it
                },

                initGsap() {
                    if (!window.gsap) return;
                    gsap.registerPlugin(ScrollTrigger);

                    // floating hero cards
                    gsap.utils.toArray('.float-card').forEach((el, i) => {
                        gsap.to(el, { y: '+=18', rotation: i % 2 ? 4 : -4, duration: 3 + i * 0.4, repeat: -1, yoyo: true, ease: 'sine.inOut' });
                        gsap.from(el, { opacity: 0, scale: 0.6, duration: 0.9, delay: 0.3 + i * 0.15, ease: 'back.out(1.7)' });
                    });

                    // parallax layers
                    gsap.utils.toArray('[data-parallax]').forEach((el) => {
                        const speed = parseFloat(el.dataset.parallax) || 0.2;
                        gsap.to(el, { yPercent: speed * 100, ease: 'none',
                            scrollTrigger: { trigger: el, start: 'top bottom', end: 'bottom top', scrub: true } });
                    });
                },
            }
        }
    </script>
</body>
</html>
