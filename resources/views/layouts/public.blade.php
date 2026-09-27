<!DOCTYPE html>
<html lang="en" x-data="{
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    mobileMenuOpen: false,
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}" :class="{ 'dark': darkMode }" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Apex Diagnostics - AI-Powered Patient, Sample & Report Management')</title>
    <meta name="description" content="@yield('meta_description', 'Clinical diagnostic laboratory platform with automated AI report interpretation, barcode sample custody, online queue tracking, and instant patient portal.')">
    <meta name="theme-color" content="#0d9488">

    <!-- Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind Play CDN for immediate modern styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6', // Medical Teal
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                            950: '#042f2e',
                        },
                        clinical: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981', // Clinical Emerald
                            600: '#059669',
                            700: '#047857',
                        },
                        darknavy: {
                            800: '#0f172a',
                            850: '#0b1329',
                            900: '#070f21',
                            950: '#030712',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    boxShadow: {
                        'glow-teal': '0 0 30px -5px rgba(20, 184, 166, 0.35)',
                        'glow-emerald': '0 0 30px -5px rgba(16, 185, 129, 0.35)',
                        'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.08)',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Initialize theme before DOM load to avoid flash
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .glass-panel {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .text-gradient {
            background: linear-gradient(135deg, #14b8a6 0%, #10b981 50%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .text-gradient-dark {
            background: linear-gradient(135deg, #2dd4bf 0%, #34d399 50%, #60a5fa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        /* Custom sleek scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.05);
        }
        .dark ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.6);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(13, 148, 136, 0.4);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(13, 148, 136, 0.7);
        }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans bg-slate-50 dark:bg-darknavy-950 text-slate-800 dark:text-slate-100 antialiased selection:bg-brand-500 selection:text-white transition-colors duration-200">

    <!-- Top Emergency & Clinical Notification Bar -->
    <div class="bg-gradient-to-r from-brand-950 via-slate-900 to-teal-950 text-white text-[11px] py-1.5 px-4 border-b border-brand-800/40">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-semibold border border-emerald-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                    Central Lab 24/7 STAT Active
                </span>
                <span class="text-slate-300 hidden md:inline">&bull; ISO 15189:2022 Certified &bull; Fast Turnaround (&lt; 4 Hours)</span>
            </div>
            <div class="flex items-center gap-4 text-slate-300">
                <a href="{{ route('public.tv-queue') }}" class="hover:text-brand-300 flex items-center gap-1 transition-colors">
                    <svg class="w-3.5 h-3.5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Live TV Lounge Queue
                </a>
                <span class="text-slate-600">|</span>
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    Emergency Phlebotomy: <strong class="text-white font-mono">+880 1700-000000</strong>
                </span>
            </div>
        </div>
    </div>

    <!-- Sticky Glassmorphic Navbar -->
    <header class="sticky top-0 z-50 bg-white/85 dark:bg-darknavy-900/85 glass-panel border-b border-slate-200/80 dark:border-slate-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
                    <div class="relative">
                        <span class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-brand-600 via-teal-500 to-emerald-400 text-white flex items-center justify-center shadow-lg shadow-brand-500/25 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </span>
                        <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-2 border-white dark:border-darknavy-900 rounded-full"></span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Apex<span class="text-teal-600 dark:text-teal-400">Diagnostics</span></span>
                            <span class="px-2 py-0.5 text-[9px] uppercase font-extrabold tracking-wider rounded-md bg-teal-50 dark:bg-teal-950 text-teal-700 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800">AI Core</span>
                        </div>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Pathology &bull; Imaging &bull; Patient Intelligence</p>
                    </div>
                </a>

                <!-- Navigation Links -->
                <nav class="hidden lg:flex items-center gap-1 text-sm font-semibold">
                    <a href="{{ route('public.tests') }}" class="px-3.5 py-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 transition-all">
                        Test Directory
                    </a>
                    <a href="{{ route('public.book-appointment') }}" class="px-3.5 py-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 transition-all flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                        Book Appointment
                    </a>
                    <a href="{{ route('public.tv-queue') }}" class="px-3.5 py-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Lounge TV
                    </a>
                    <a href="/admin" class="px-3.5 py-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 transition-all flex items-center gap-1">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Staff Panel
                    </a>
                </nav>

                <!-- Actions: Theme Toggle + Patient Portal + Book Button -->
                <div class="flex items-center gap-3">
                    <!-- Dark / Light Mode Toggle Button -->
                    <button
                        type="button"
                        @click="toggleTheme"
                        class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-darknavy-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-500 transition-colors shadow-sm"
                        title="Toggle Dark / Light Mode"
                        aria-label="Toggle theme"
                    >
                        <!-- Sun Icon (shown in dark mode) -->
                        <svg x-show="darkMode" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <!-- Moon Icon (shown in light mode) -->
                        <svg x-show="!darkMode" class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    <!-- Patient Portal Login Button -->
                    <a href="{{ route('portal.login') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-darknavy-800 text-slate-800 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all shadow-sm">
                        <svg class="w-4 h-4 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Patient Portal
                    </a>

                    <!-- Fast Book Appointment CTA -->
                    <a href="{{ route('public.book-appointment') }}" class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 text-xs font-bold rounded-xl bg-gradient-to-r from-brand-600 to-teal-600 hover:from-brand-700 hover:to-teal-700 text-white shadow-md shadow-brand-500/25 hover:shadow-glow-teal transition-all duration-300 transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>Book Test</span>
                    </a>

                    <!-- Mobile Menu Hamburger -->
                    <button
                        type="button"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800"
                        aria-label="Open menu"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div
            x-show="mobileMenuOpen"
            x-cloak
            @click.away="mobileMenuOpen = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden border-t border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-darknavy-900/95 glass-panel px-4 pt-3 pb-6 space-y-2"
        >
            <a href="{{ route('public.tests') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-teal-50 dark:hover:bg-teal-950/60">
                Test Directory & Packages
            </a>
            <a href="{{ route('public.book-appointment') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-teal-50 dark:hover:bg-teal-950/60">
                Book Lab Appointment
            </a>
            <a href="{{ route('portal.login') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-teal-50 dark:hover:bg-teal-950/60">
                Patient Portal (View &amp; Download Reports)
            </a>
            <a href="{{ route('public.tv-queue') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-teal-50 dark:hover:bg-teal-950/60">
                Waiting Lounge TV Queue
            </a>
            <a href="/admin" class="block px-3 py-2 rounded-lg text-sm font-semibold text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-950/60">
                Staff / Pathologist Admin Panel
            </a>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Ultra-Modern Footer -->
    <footer class="bg-white dark:bg-darknavy-900 border-t border-slate-200 dark:border-slate-800 py-16 mt-20 relative overflow-hidden transition-colors">
        <!-- Background Ambient Glow -->
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
                <!-- Col 1: Brand Info & Accreditation -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-teal-400 text-white flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </span>
                        <div>
                            <span class="text-lg font-black text-slate-900 dark:text-white">Apex Diagnostics</span>
                            <span class="block text-[11px] text-teal-600 dark:text-teal-400 font-semibold">Autonomous Clinical AI Diagnostics</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed max-w-sm">
                        ISO 15189:2022 and CAP benchmark accredited clinical laboratory. Zero-error phlebotomy barcode tracking, plain-language patient report summarization, and biometric technician shift management.
                    </p>
                    <div class="flex flex-wrap gap-2 pt-2">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 dark:bg-darknavy-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                            🛡️ ISO 15189:2022
                        </span>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 dark:bg-darknavy-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                            🔒 256-bit Cryptographic Reports
                        </span>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-teal-50 dark:bg-teal-950 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800">
                            ⚡ AI Hub Engine
                        </span>
                    </div>
                </div>

                <!-- Col 2: Clinical Services -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-4">Laboratory Services</h4>
                    <ul class="space-y-2.5 text-xs text-slate-600 dark:text-slate-400">
                        <li><a href="{{ route('public.tests', ['category' => 'hematology']) }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Clinical Hematology &amp; CBC</a></li>
                        <li><a href="{{ route('public.tests', ['category' => 'biochemistry']) }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Clinical Biochemistry &amp; Enzymes</a></li>
                        <li><a href="{{ route('public.tests', ['category' => 'immunology']) }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Immunology &amp; Serology</a></li>
                        <li><a href="{{ route('public.tests', ['category' => 'molecular']) }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Molecular &amp; PCR Diagnostics</a></li>
                        <li><a href="{{ route('public.tests', ['category' => 'radiology']) }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Digital X-Ray &amp; Ultrasonography</a></li>
                    </ul>
                </div>

                <!-- Col 3: Patient Quick Portal -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-4">Patient Portal</h4>
                    <ul class="space-y-2.5 text-xs text-slate-600 dark:text-slate-400">
                        <li><a href="{{ route('portal.login') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Download Official PDF Report</a></li>
                        <li><a href="{{ route('public.book-appointment') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Book Fast-Track Phlebotomy</a></li>
                        <li><a href="{{ route('public.tv-queue') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Lounge TV Display Screen</a></li>
                        <li><a href="/report/cbc-farhan-akhtar-2026" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Sample AI Report Demo</a></li>
                        <li><a href="{{ route('public.verify-report', 'demo') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Report QR Authenticity Check</a></li>
                    </ul>
                </div>

                <!-- Col 4: Central Reference Lab Contacts -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-4">Central Facilities</h4>
                    <div class="text-xs text-slate-600 dark:text-slate-400 space-y-2">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-brand-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Plot 42, Health Avenue, Medical City, Dhaka 1212</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span class="font-mono">+880 1700-000001</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>support@apexdiagnostics.test</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Row: Copyright & Stack -->
            <div class="border-t border-slate-200 dark:border-slate-800 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <div class="flex items-center gap-2">
                    <span>&copy; {{ date('Y') }} Apex Diagnostics AI Platform.</span>
                    <span class="hidden sm:inline">&bull;</span>
                    <span class="hidden sm:inline">Engineered with Laravel 11/12 &amp; Filament v3/v4</span>
                </div>
                <div class="flex items-center gap-4 font-mono text-[11px]">
                    <span class="text-teal-600 dark:text-teal-400 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        AI Engine: Multi-Provider Failover Ready
                    </span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>

