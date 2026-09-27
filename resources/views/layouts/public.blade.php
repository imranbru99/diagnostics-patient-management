<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Apex Diagnostics - AI-Powered Patient, Sample & Report Management')</title>
    <meta name="description" content="@yield('meta_description', 'Modern diagnostic laboratory with automated AI report summarization, real-time sample barcode tracking, and online patient portal.')">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Tailwind Play CDN for immediate flawless standalone styling -->
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
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                            950: '#042f2e',
                        },
                        emerald: {
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans text-slate-800 dark:text-slate-200 antialiased selection:bg-brand-500 selection:text-white">

    <!-- Navbar -->
    <header class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="p-2 rounded-xl bg-gradient-to-tr from-brand-600 to-teal-400 text-white shadow-md shadow-brand-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </span>
                    <div>
                        <span class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">Apex<span class="text-brand-600 dark:text-brand-400">Diagnostics</span></span>
                        <span class="hidden sm:inline-block ml-2 px-2 py-0.5 text-[10px] uppercase font-bold tracking-wider rounded-md bg-teal-50 dark:bg-teal-950/80 text-teal-700 dark:text-teal-300 border border-teal-200/60 dark:border-teal-800">AI Powered</span>
                    </div>
                </a>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                    <a href="{{ route('public.tests') }}" class="text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Test Catalog</a>
                    <a href="{{ route('public.book-appointment') }}" class="text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Book Online</a>
                    <a href="{{ route('public.tv-queue') }}" class="text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Lounge TV Queue</a>
                    <a href="/admin" class="text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Lab Staff Panel</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('portal.login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Patient Portal
                    </a>
                    <a href="{{ route('public.book-appointment') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-lg bg-brand-600 hover:bg-brand-700 text-white shadow-sm shadow-brand-500/20 transition-colors">
                        Book Test
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-10 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="p-1.5 rounded-lg bg-brand-600 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </span>
                        <span class="text-base font-bold text-slate-900 dark:text-white">Apex Diagnostics</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        ISO 15189:2022 certified diagnostic center & pathology laboratory. Next-generation patient report intelligence powered by <code>laravel-ai-hub</code>.
                    </p>
                </div>

                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-3">Quick Navigation</h4>
                    <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-400">
                        <li><a href="{{ route('public.tests') }}" class="hover:text-brand-600">Complete Test Catalog</a></li>
                        <li><a href="{{ route('public.book-appointment') }}" class="hover:text-brand-600">Online Appointment</a></li>
                        <li><a href="{{ route('portal.login') }}" class="hover:text-brand-600">Download Reports (OTP / MRN)</a></li>
                        <li><a href="{{ route('public.tv-queue') }}" class="hover:text-brand-600">Lounge TV Display Screen</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-3">Ecosystem & Packages</h4>
                    <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-400 font-mono">
                        <li><a href="https://github.com/imranbru99/laravel-ai-hub" target="_blank" class="hover:text-brand-600">imrandevbd/laravel-ai-hub</a></li>
                        <li><a href="https://github.com/imranbru99/laravel-unicode-pdf" target="_blank" class="hover:text-brand-600">imrandevbd/laravel-unicode-pdf</a></li>
                        <li><a href="https://github.com/imranbru99/laravel-attendance-hub" target="_blank" class="hover:text-brand-600">imrandevbd/laravel-attendance-hub</a></li>
                        <li><a href="https://github.com/imranbru99/laravel-universal-slug" target="_blank" class="hover:text-brand-600">imrandevbd/laravel-universal-slug</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-3">Central Laboratory</h4>
                    <div class="text-xs text-slate-500 space-y-1">
                        <div>Plot 42, Health Avenue, Medical City</div>
                        <div>Phone: +880 1700-000000 / 02-9876543</div>
                        <div>Emergency Sample Pickup: 24/7 STAT Available</div>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-800 mt-8 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500">
                <div>&copy; {{ date('Y') }} Apex Diagnostics. Open-Source Core under MIT License.</div>
                <div class="mt-2 sm:mt-0 font-medium">Built with Laravel & Filament v3/v4</div>
            </div>
        </div>
    </footer>

</body>
</html>
