<!DOCTYPE html>
<html lang="en" x-data="registerPage()" :class="{ 'dark': darkMode }" x-cloak class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account — Cafe Delight</title>

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
                        spinSlow: { to:{transform:'rotate(360deg)'} },
                    },
                    animation: {
                        float: 'float 6s ease-in-out infinite',
                        'float-slow': 'floatSlow 9s ease-in-out infinite',
                        'spin-slow': 'spinSlow 24s linear infinite',
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
        <div class="relative hidden overflow-hidden rounded-l-[2.5rem] bg-gradient-to-br from-brand-500 via-brand-600 to-brand-800 p-12 text-white lg:flex lg:flex-col lg:justify-between lg:min-h-[640px]">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <span class="grid h-12 w-12 place-items-center rounded-xl bg-white/15 backdrop-blur"><i data-lucide="utensils-crossed" class="h-7 w-7"></i></span>
                <span class="font-display text-2xl font-extrabold">Cafe Delight</span>
            </a>

            <div class="relative">
                {{-- floating food emojis --}}
                <div class="pointer-events-none absolute -top-10 right-4 text-6xl animate-float-slow">🌯</div>
                <div class="pointer-events-none absolute top-24 -left-2 text-5xl animate-float">🍛</div>
                <div class="pointer-events-none absolute bottom-4 right-10 text-5xl animate-float-slow">🍰</div>

                <h1 class="font-display text-4xl font-extrabold leading-tight xl:text-5xl">Join the<br>flavor family.</h1>
                <p class="mt-5 max-w-md text-orange-50/90">Create your account to order shawarma, biryani, fresh juices, cakes &amp; more — track deliveries and unlock member-only deals.</p>

                <ul class="mt-8 space-y-3">
                    <template x-for="perk in perks" :key="perk">
                        <li class="flex items-center gap-3 text-orange-50/95">
                            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-white/20"><i data-lucide="check" class="h-4 w-4"></i></span>
                            <span x-text="perk" class="text-sm font-medium"></span>
                        </li>
                    </template>
                </ul>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex -space-x-3">
                    <template x-for="n in 4" :key="n">
                        <span class="grid h-9 w-9 place-items-center rounded-full border-2 border-brand-600 bg-white/20"><i data-lucide="user" class="h-4 w-4"></i></span>
                    </template>
                </div>
                <p class="text-sm text-orange-50/90"><strong>12,000+</strong> foodies already joined</p>
            </div>
        </div>

        {{-- ============ FORM PANEL ============ --}}
        <div data-aos="fade-left" class="glass-strong rounded-[2.5rem] p-8 shadow-premium sm:p-10 lg:rounded-l-none lg:rounded-r-[2.5rem] lg:min-h-[640px] lg:flex lg:flex-col lg:justify-center">

            {{-- mobile logo --}}
            <a href="{{ url('/') }}" class="mb-8 flex items-center gap-2.5 lg:hidden">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-glow"><i data-lucide="utensils-crossed" class="h-6 w-6"></i></span>
                <span class="font-display text-xl font-extrabold">Cafe <span class="text-gradient">Delight</span></span>
            </a>

            <div class="mx-auto w-full max-w-md">
                <h2 class="font-display text-3xl font-extrabold sm:text-4xl">Create account</h2>
                <p class="mt-2 text-ink/60 dark:text-orange-50/60">It only takes a minute to start ordering.</p>

                <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5" x-on:submit="submitting = true">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-bold">Name</label>
                        <div class="relative">
                            <i data-lucide="user" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink/40 dark:text-orange-50/40"></i>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                                   placeholder="Jane Doe"
                                   class="w-full rounded-xl border-0 glass py-3.5 pl-12 pr-4 text-sm font-medium placeholder:text-ink/40 focus:ring-2 focus:ring-brand-500 dark:placeholder:text-orange-50/30" />
                        </div>
                        @error('name') <p class="mt-1.5 flex items-center gap-1 text-sm font-medium text-red-500"><i data-lucide="alert-circle" class="h-4 w-4"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-bold">Email</label>
                        <div class="relative">
                            <i data-lucide="mail" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink/40 dark:text-orange-50/40"></i>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                                   placeholder="you@email.com"
                                   class="w-full rounded-xl border-0 glass py-3.5 pl-12 pr-4 text-sm font-medium placeholder:text-ink/40 focus:ring-2 focus:ring-brand-500 dark:placeholder:text-orange-50/30" />
                        </div>
                        @error('email') <p class="mt-1.5 flex items-center gap-1 text-sm font-medium text-red-500"><i data-lucide="alert-circle" class="h-4 w-4"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-bold">Password</label>
                        <div class="relative">
                            <i data-lucide="lock" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink/40 dark:text-orange-50/40"></i>
                            <input id="password" name="password" required autocomplete="new-password"
                                   x-model="password"
                                   :type="showPw ? 'text' : 'password'"
                                   placeholder="Create a strong password"
                                   class="w-full rounded-xl border-0 glass py-3.5 pl-12 pr-12 text-sm font-medium placeholder:text-ink/40 focus:ring-2 focus:ring-brand-500 dark:placeholder:text-orange-50/30" />
                            <button type="button" x-on:click="showPw = !showPw" :aria-label="showPw ? 'Hide password' : 'Show password'"
                                    class="absolute right-3 top-1/2 grid h-8 w-8 -translate-y-1/2 place-items-center rounded-lg text-ink/50 transition hover:text-brand-600 dark:text-orange-50/50">
                                <i x-show="!showPw" data-lucide="eye" class="h-5 w-5"></i>
                                <i x-show="showPw" data-lucide="eye-off" class="h-5 w-5"></i>
                            </button>
                        </div>

                        {{-- strength meter --}}
                        <div x-show="password.length > 0" x-transition class="mt-3">
                            <div class="flex gap-1.5">
                                <template x-for="i in 4" :key="i">
                                    <span class="h-1.5 flex-1 rounded-full transition-all duration-300"
                                          :class="i <= score ? strength.bar : 'bg-ink/10 dark:bg-white/10'"></span>
                                </template>
                            </div>
                            <p class="mt-1.5 text-xs font-bold" :class="strength.text" x-text="'Strength: ' + strength.label"></p>

                            {{-- rule checklist --}}
                            <ul class="mt-3 grid grid-cols-2 gap-y-1.5 text-xs">
                                <template x-for="rule in ruleList" :key="rule.key">
                                    <li class="flex items-center gap-1.5 transition"
                                        :class="checks[rule.key] ? 'text-emerald-600 dark:text-emerald-400' : 'text-ink/45 dark:text-orange-50/40'">
                                        <i :data-lucide="checks[rule.key] ? 'check-circle-2' : 'circle'" class="h-3.5 w-3.5"></i>
                                        <span x-text="rule.label"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                        @error('password') <p class="mt-1.5 flex items-center gap-1 text-sm font-medium text-red-500"><i data-lucide="alert-circle" class="h-4 w-4"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="mb-1.5 block text-sm font-bold">Confirm Password</label>
                        <div class="relative">
                            <i data-lucide="lock-keyhole" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink/40 dark:text-orange-50/40"></i>
                            <input id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                   x-model="confirm"
                                   :type="showConfirm ? 'text' : 'password'"
                                   placeholder="Re-enter your password"
                                   class="w-full rounded-xl border-0 glass py-3.5 pl-12 pr-12 text-sm font-medium placeholder:text-ink/40 focus:ring-2 focus:ring-brand-500 dark:placeholder:text-orange-50/30" />
                            <button type="button" x-on:click="showConfirm = !showConfirm" :aria-label="showConfirm ? 'Hide password' : 'Show password'"
                                    class="absolute right-3 top-1/2 grid h-8 w-8 -translate-y-1/2 place-items-center rounded-lg text-ink/50 transition hover:text-brand-600 dark:text-orange-50/50">
                                <i x-show="!showConfirm" data-lucide="eye" class="h-5 w-5"></i>
                                <i x-show="showConfirm" data-lucide="eye-off" class="h-5 w-5"></i>
                            </button>
                        </div>
                        <p x-show="confirm.length > 0" x-transition class="mt-1.5 flex items-center gap-1 text-xs font-bold"
                           :class="passwordsMatch ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500'">
                            <i :data-lucide="passwordsMatch ? 'check-circle-2' : 'x-circle'" class="h-4 w-4"></i>
                            <span x-text="passwordsMatch ? 'Passwords match' : 'Passwords do not match'"></span>
                        </p>
                        @error('password_confirmation') <p class="mt-1.5 flex items-center gap-1 text-sm font-medium text-red-500"><i data-lucide="alert-circle" class="h-4 w-4"></i> {{ $message }}</p> @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit" :disabled="submitting"
                            class="group flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-6 py-3.5 text-base font-bold text-white shadow-glow transition hover:scale-[1.02] active:scale-95 disabled:opacity-70">
                        <span x-show="!submitting" class="flex items-center gap-2">
                            Create Account <i data-lucide="arrow-right" class="h-5 w-5 transition-transform group-hover:translate-x-1"></i>
                        </span>
                        <span x-show="submitting" class="flex items-center gap-2">
                            <i data-lucide="loader-2" class="h-5 w-5 animate-spin"></i> Creating...
                        </span>
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-ink/60 dark:text-orange-50/60">
                    Already registered?
                    <a href="{{ route('login') }}" class="font-bold text-brand-600 underline-offset-4 transition hover:underline">Sign in</a>
                </p>
            </div>
        </div>
    </div>

    {{-- scripts --}}
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        function registerPage() {
            return {
                darkMode: false,
                password: '',
                confirm: '',
                showPw: false,
                showConfirm: false,
                submitting: false,

                perks: ['Faster checkout & reorders', 'Real-time delivery tracking', 'Exclusive member discounts'],
                ruleList: [
                    { key: 'length', label: '8+ characters' },
                    { key: 'lower',  label: 'Lowercase letter' },
                    { key: 'upper',  label: 'Uppercase letter' },
                    { key: 'number', label: 'Number' },
                    { key: 'symbol', label: 'Symbol' },
                ],

                get checks() {
                    const p = this.password;
                    return {
                        length: p.length >= 8,
                        lower:  /[a-z]/.test(p),
                        upper:  /[A-Z]/.test(p),
                        number: /[0-9]/.test(p),
                        symbol: /[^A-Za-z0-9]/.test(p),
                    };
                },

                // 0..4 strength score derived from rules met
                get score() {
                    const c = this.checks;
                    let met = (c.lower ? 1 : 0) + (c.upper ? 1 : 0) + (c.number ? 1 : 0) + (c.symbol ? 1 : 0);
                    // require length for anything above "weak"
                    if (!c.length) return Math.min(met, 1);
                    return Math.min(met, 4);
                },

                get strength() {
                    const map = [
                        { label: 'Too weak', bar: 'bg-red-500',     text: 'text-red-500' },
                        { label: 'Weak',     bar: 'bg-red-500',     text: 'text-red-500' },
                        { label: 'Fair',     bar: 'bg-amber-500',   text: 'text-amber-500' },
                        { label: 'Good',     bar: 'bg-lime-500',    text: 'text-lime-600' },
                        { label: 'Strong',   bar: 'bg-emerald-500', text: 'text-emerald-600' },
                    ];
                    return map[this.score];
                },

                get passwordsMatch() {
                    return this.confirm.length > 0 && this.password === this.confirm;
                },

                init() {
                    const saved = localStorage.getItem('cd-theme');
                    this.darkMode = saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;

                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                        if (window.AOS) AOS.init({ duration: 700, once: true, easing: 'ease-out-cubic' });
                    });

                    // re-render lucide icons whenever toggles/strength state changes
                    this.$watch('showPw',          () => this.$nextTick(() => lucide.createIcons()));
                    this.$watch('showConfirm',     () => this.$nextTick(() => lucide.createIcons()));
                    this.$watch('password',        () => this.$nextTick(() => lucide.createIcons()));
                    this.$watch('confirm',         () => this.$nextTick(() => lucide.createIcons()));
                    this.$watch('submitting',      () => this.$nextTick(() => lucide.createIcons()));
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
