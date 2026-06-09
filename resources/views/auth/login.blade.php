<!DOCTYPE html>
<html lang="en" x-data="loginPage()" :class="{ 'dark': darkMode }" x-cloak class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In — Cafe Delight</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700;800;900&display=swap" rel="stylesheet">

    {{-- Tailwind CSS (Play CDN) --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    {{-- AOS scroll animations --}}
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
                    keyframes: {
                        float: { '0%,100%':{transform:'translateY(0)'}, '50%':{transform:'translateY(-18px)'} },
                        floatSlow: { '0%,100%':{transform:'translateY(0) rotate(0)'}, '50%':{transform:'translateY(-26px) rotate(4deg)'} },
                    },
                    animation: {
                        float: 'float 6s ease-in-out infinite',
                        'float-slow': 'floatSlow 9s ease-in-out infinite',
                    },
                },
            },
        }
    </script>

    <style>
        [x-cloak] { display:none !important; }
        body { -webkit-font-smoothing: antialiased; }
        .glass { background:rgba(255,255,255,0.55); backdrop-filter:blur(18px) saturate(160%); -webkit-backdrop-filter:blur(18px) saturate(160%); border:1px solid rgba(255,255,255,0.5); }
        .dark .glass { background:rgba(20,18,16,0.55); border:1px solid rgba(255,255,255,0.08); }
        .glass-strong { background:rgba(255,255,255,0.82); backdrop-filter:blur(22px) saturate(180%); -webkit-backdrop-filter:blur(22px) saturate(180%); }
        .dark .glass-strong { background:rgba(18,16,14,0.8); }
        .text-gradient { background:linear-gradient(120deg,#fb923c,#ea580c 40%,#e6b450); -webkit-background-clip:text; background-clip:text; color:transparent; }
        .mesh { background:
            radial-gradient(45rem 45rem at 85% -10%, rgba(249,115,22,0.20), transparent 60%),
            radial-gradient(40rem 40rem at 0% 30%, rgba(230,180,80,0.18), transparent 60%),
            radial-gradient(50rem 50rem at 50% 120%, rgba(234,88,12,0.16), transparent 60%); }
        ::-webkit-scrollbar { width:10px; }
        ::-webkit-scrollbar-thumb { background:linear-gradient(#f97316,#ea580c); border-radius:99px; border:3px solid transparent; background-clip:content-box; }
    </style>
</head>

<body class="font-sans min-h-screen bg-[#fbf7f1] text-ink dark:bg-ink dark:text-orange-50 antialiased selection:bg-brand-500 selection:text-white overflow-x-hidden">

    {{-- backgrounds --}}
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10 mesh"></div>
    <div aria-hidden="true" class="pointer-events-none fixed -top-24 -left-24 h-96 w-96 rounded-full bg-brand-400/30 blur-3xl -z-10 animate-float-slow"></div>
    <div aria-hidden="true" class="pointer-events-none fixed bottom-0 -right-28 h-[26rem] w-[26rem] rounded-full bg-gold/25 blur-3xl -z-10 animate-float"></div>

    {{-- dark mode toggle --}}
    <button x-on:click="toggleDark()" aria-label="Toggle dark mode"
            class="fixed right-5 top-5 z-50 grid h-11 w-11 place-items-center rounded-xl glass transition hover:scale-110 active:scale-95">
        <i x-show="!darkMode" data-lucide="moon" class="h-5 w-5"></i>
        <i x-show="darkMode" data-lucide="sun" class="h-5 w-5 text-gold"></i>
    </button>

    <div class="mx-auto grid min-h-screen max-w-7xl items-center gap-0 px-4 py-10 sm:px-6 lg:grid-cols-2 lg:px-8">

        {{-- ============ BRAND PANEL ============ --}}
        <div class="relative hidden overflow-hidden rounded-l-[2.5rem] bg-gradient-to-br from-brand-500 via-brand-600 to-brand-800 p-12 text-white lg:flex lg:flex-col lg:justify-between lg:min-h-[600px]">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <span class="grid h-12 w-12 place-items-center rounded-xl bg-white/15 backdrop-blur"><i data-lucide="utensils-crossed" class="h-7 w-7"></i></span>
                <span class="font-display text-2xl font-extrabold">Cafe Delight</span>
            </a>

            <div class="relative">
                {{-- floating food emojis --}}
                <div class="pointer-events-none absolute -top-10 right-4 text-6xl animate-float-slow">🥙</div>
                <div class="pointer-events-none absolute top-24 -left-2 text-5xl animate-float">🍔</div>
                <div class="pointer-events-none absolute bottom-2 right-12 text-5xl animate-float-slow">🥤</div>

                <h1 class="font-display text-4xl font-extrabold leading-tight xl:text-5xl">Welcome<br>back, foodie!</h1>
                <p class="mt-5 max-w-md text-orange-50/90">Sign in to reorder your favorites, track live deliveries, and grab today's hot member deals.</p>

                <div class="mt-8 flex items-center gap-1 text-gold">
                    <template x-for="s in 5" :key="s"><i data-lucide="star" class="h-5 w-5 fill-current"></i></template>
                    <span class="ml-2 text-sm font-semibold text-orange-50/90">Rated 4.9 by 12,000+ customers</span>
                </div>
            </div>

            <p class="text-sm text-orange-50/80">"Best shawarma in town — and the app makes reordering effortless." — Amina K.</p>
        </div>

        {{-- ============ FORM PANEL ============ --}}
        <div data-aos="fade-left" class="glass-strong rounded-[2.5rem] p-8 shadow-premium sm:p-10 lg:rounded-l-none lg:rounded-r-[2.5rem] lg:min-h-[600px] lg:flex lg:flex-col lg:justify-center">

            {{-- mobile logo --}}
            <a href="{{ url('/') }}" class="mb-8 flex items-center gap-2.5 lg:hidden">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-glow"><i data-lucide="utensils-crossed" class="h-6 w-6"></i></span>
                <span class="font-display text-xl font-extrabold">Cafe <span class="text-gradient">Delight</span></span>
            </a>

            <div class="mx-auto w-full max-w-md">
                <h2 class="font-display text-3xl font-extrabold sm:text-4xl">Sign in</h2>
                <p class="mt-2 text-ink/60 dark:text-orange-50/60">Good to see you again — let's get you fed.</p>

                {{-- Session Status (e.g. password reset link sent) --}}
                @if (session('status'))
                    <div class="mt-6 flex items-center gap-2 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-700 dark:text-emerald-400">
                        <i data-lucide="check-circle-2" class="h-5 w-5"></i> {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5" x-on:submit="submitting = true">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-bold">Email</label>
                        <div class="relative">
                            <i data-lucide="mail" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink/40 dark:text-orange-50/40"></i>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                   placeholder="you@email.com"
                                   class="w-full rounded-xl border-0 glass py-3.5 pl-12 pr-4 text-sm font-medium placeholder:text-ink/40 focus:ring-2 focus:ring-brand-500 dark:placeholder:text-orange-50/30" />
                        </div>
                        @error('email') <p class="mt-1.5 flex items-center gap-1 text-sm font-medium text-red-500"><i data-lucide="alert-circle" class="h-4 w-4"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="mb-1.5 flex items-center justify-between">
                            <label for="password" class="block text-sm font-bold">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-brand-600 underline-offset-4 transition hover:underline">Forgot password?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <i data-lucide="lock" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink/40 dark:text-orange-50/40"></i>
                            <input id="password" name="password" required autocomplete="current-password"
                                   :type="showPw ? 'text' : 'password'"
                                   placeholder="Enter your password"
                                   class="w-full rounded-xl border-0 glass py-3.5 pl-12 pr-12 text-sm font-medium placeholder:text-ink/40 focus:ring-2 focus:ring-brand-500 dark:placeholder:text-orange-50/30" />
                            <button type="button" x-on:click="showPw = !showPw" :aria-label="showPw ? 'Hide password' : 'Show password'"
                                    class="absolute right-3 top-1/2 grid h-8 w-8 -translate-y-1/2 place-items-center rounded-lg text-ink/50 transition hover:text-brand-600 dark:text-orange-50/50">
                                <i x-show="!showPw" data-lucide="eye" class="h-5 w-5"></i>
                                <i x-show="showPw" data-lucide="eye-off" class="h-5 w-5"></i>
                            </button>
                        </div>
                        @error('password') <p class="mt-1.5 flex items-center gap-1 text-sm font-medium text-red-500"><i data-lucide="alert-circle" class="h-4 w-4"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- Remember me --}}
                    <label for="remember_me" class="flex w-fit cursor-pointer items-center gap-2.5 select-none">
                        <input id="remember_me" name="remember" type="checkbox" x-model="remember" class="peer sr-only">
                        <span class="grid h-5 w-5 place-items-center rounded-md border-2 border-ink/25 transition peer-checked:border-brand-600 peer-checked:bg-brand-600 dark:border-orange-50/25">
                            <i data-lucide="check" class="h-3.5 w-3.5 text-white transition" :class="remember ? 'opacity-100' : 'opacity-0'"></i>
                        </span>
                        <span class="text-sm font-medium text-ink/70 dark:text-orange-50/70">Remember me</span>
                    </label>

                    {{-- Submit --}}
                    <button type="submit" :disabled="submitting"
                            class="group flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-6 py-3.5 text-base font-bold text-white shadow-glow transition hover:scale-[1.02] active:scale-95 disabled:opacity-70">
                        <span x-show="!submitting" class="flex items-center gap-2">
                            Log In <i data-lucide="arrow-right" class="h-5 w-5 transition-transform group-hover:translate-x-1"></i>
                        </span>
                        <span x-show="submitting" class="flex items-center gap-2">
                            <i data-lucide="loader-2" class="h-5 w-5 animate-spin"></i> Signing in...
                        </span>
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-ink/60 dark:text-orange-50/60">
                    New to Cafe Delight?
                    <a href="{{ route('register') }}" class="font-bold text-brand-600 underline-offset-4 transition hover:underline">Create an account</a>
                </p>
            </div>
        </div>
    </div>

    {{-- scripts --}}
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        function loginPage() {
            return {
                darkMode: false,
                showPw: false,
                remember: false,
                submitting: false,

                init() {
                    const saved = localStorage.getItem('cd-theme');
                    this.darkMode = saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;

                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                        if (window.AOS) AOS.init({ duration: 700, once: true, easing: 'ease-out-cubic' });
                    });

                    this.$watch('showPw',     () => this.$nextTick(() => lucide.createIcons()));
                    this.$watch('remember',   () => this.$nextTick(() => lucide.createIcons()));
                    this.$watch('submitting', () => this.$nextTick(() => lucide.createIcons()));
                },

                toggleDark() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('cd-theme', this.darkMode ? 'dark' : 'light');
                },
            }
        }
    </script>
</body>
</html>
