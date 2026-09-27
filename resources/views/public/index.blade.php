@extends('layouts.public')

@section('title', 'Apex Diagnostics - AI-Powered Patient, Sample & Report Management')

@section('content')
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-b from-brand-50/60 via-slate-50 to-white dark:from-slate-900 dark:via-slate-950 dark:to-slate-950 py-16 lg:py-24 border-b border-slate-200/60 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-100 dark:bg-brand-950 text-brand-700 dark:text-brand-300 text-xs font-semibold mb-6 border border-brand-200 dark:border-brand-800">
                    <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                    Next-Gen AI Diagnostic Center Platform
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    AI-Powered Patient, Sample &amp; Report Management
                </h1>
                <p class="mt-5 text-lg text-slate-600 dark:text-slate-300 leading-relaxed">
                    Zero error barcode chain-of-custody, plain-language patient report summarization, automated critical value alerts, and instant online report access.
                </p>

                <!-- Search Bar -->
                <form action="{{ route('public.tests') }}" method="GET" class="mt-8 max-w-xl mx-auto">
                    <div class="relative flex items-center shadow-lg rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-1.5 focus-within:ring-2 focus-within:ring-brand-500">
                        <svg class="w-5 h-5 text-slate-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" name="q" placeholder="Search tests (e.g. CBC, Lipid Profile, Thyroid, X-Ray)..." class="w-full px-4 py-2.5 bg-transparent border-0 text-sm focus:ring-0 focus:outline-none text-slate-900 dark:text-white placeholder-slate-400">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors">
                            Find Test
                        </button>
                    </div>
                </form>

                <!-- Stats Counters -->
                <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto text-center">
                    <div class="p-4 rounded-xl bg-white/70 dark:bg-slate-900/60 backdrop-blur border border-slate-200/60 dark:border-slate-800">
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">150+</div>
                        <div class="text-xs text-slate-500 mt-1">Diagnostic Tests</div>
                    </div>
                    <div class="p-4 rounded-xl bg-white/70 dark:bg-slate-900/60 backdrop-blur border border-slate-200/60 dark:border-slate-800">
                        <div class="text-2xl font-bold text-brand-600 dark:text-brand-400">&lt; 4 Hours</div>
                        <div class="text-xs text-slate-500 mt-1">Fast Turnaround</div>
                    </div>
                    <div class="p-4 rounded-xl bg-white/70 dark:bg-slate-900/60 backdrop-blur border border-slate-200/60 dark:border-slate-800">
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">100%</div>
                        <div class="text-xs text-slate-500 mt-1">Barcode Tracked</div>
                    </div>
                    <div class="p-4 rounded-xl bg-white/70 dark:bg-slate-900/60 backdrop-blur border border-slate-200/60 dark:border-slate-800">
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">AI Hub</div>
                        <div class="text-xs text-slate-500 mt-1">Plain Language Reports</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Workflow Steps -->
    <div class="py-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Seamless Diagnostic Lifecycle</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">From phlebotomy draw to AI explanation in 4 transparent stages.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/60 relative">
                    <div class="w-10 h-10 rounded-xl bg-brand-100 dark:bg-brand-950 text-brand-600 dark:text-brand-400 font-bold flex items-center justify-center mb-4">1</div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Sample Barcoding</h3>
                    <p class="mt-2 text-xs text-slate-600 dark:text-slate-400">Sample tube is tagged with a unique cryptographic barcode at phlebotomy counter.</p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/60 relative">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-600 dark:text-purple-400 font-bold flex items-center justify-center mb-4">2</div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Automated Testing</h3>
                    <p class="mt-2 text-xs text-slate-600 dark:text-slate-400">Run through advanced Sysmex &amp; Roche analyzers with automated reference range checks.</p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/60 relative">
                    <div class="w-10 h-10 rounded-xl bg-teal-100 dark:bg-teal-950 text-teal-600 dark:text-teal-400 font-bold flex items-center justify-center mb-4">3</div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">AI Plain Explanation</h3>
                    <p class="mt-2 text-xs text-slate-600 dark:text-slate-400">AI Hub transforms clinical parameters into easy-to-understand summaries with abnormal alerts.</p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/60 relative">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center mb-4">4</div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Pathologist Verification</h3>
                    <p class="mt-2 text-xs text-slate-600 dark:text-slate-400">Consultant signs digitally. Report available on portal with QR authenticity guarantee.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Health Packages -->
    <div class="py-16 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Comprehensive Health Checkup Bundles</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Tailored multi-test packages offering up to 25% savings.</p>
                </div>
                <a href="{{ route('public.tests') }}" class="text-sm font-semibold text-brand-600 dark:text-brand-400 hover:underline">
                    View All Tests &amp; Packages &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($popularPackages as $pkg)
                    <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between hover:border-brand-500 transition-all">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400">
                                    {{ $pkg->discount_percentage }}% DISCOUNT
                                </span>
                                <span class="text-xs font-mono text-slate-400">{{ $pkg->code }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $pkg->name }}</h3>
                            <p class="mt-2 text-xs text-slate-500 line-clamp-2">{{ $pkg->description }}</p>

                            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 space-y-1.5 text-xs text-slate-600 dark:text-slate-400">
                                @foreach($pkg->tests->take(4) as $t)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="truncate">{{ $t->name }}</span>
                                    </div>
                                @endforeach
                                @if($pkg->tests->count() > 4)
                                    <div class="text-[11px] text-brand-600 font-medium">+ {{ $pkg->tests->count() - 4 }} more investigations included</div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-400 line-through">${{ number_format($pkg->price * 1.25, 2) }}</span>
                                <div class="text-2xl font-bold text-slate-900 dark:text-white">${{ number_format($pkg->price, 2) }}</div>
                            </div>
                            <a href="{{ route('public.book-appointment', ['package' => $pkg->id]) }}" class="px-4 py-2 text-xs font-semibold rounded-lg bg-brand-600 hover:bg-brand-700 text-white transition-colors">
                                Book Package
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
