@extends('layouts.public')

@section('title', 'Apex Diagnostics - AI-Powered Patient, Sample & Report Management')

@section('content')
<div class="space-y-16 lg:space-y-24">

    <!-- Hero Section with Ambient Glows & Interactive Command Center -->
    <div class="relative overflow-hidden pt-10 pb-20 lg:pt-16 lg:pb-28 border-b border-slate-200/80 dark:border-slate-800">
        <!-- Background Radial Ambient Glows -->
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[700px] h-[500px] bg-gradient-to-tr from-brand-500/15 via-teal-400/10 to-emerald-400/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
        <div class="absolute top-1/3 -left-32 w-80 h-80 bg-brand-600/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
        <div class="absolute bottom-10 -right-32 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <!-- Pill Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/80 dark:bg-darknavy-800/80 backdrop-blur-md text-brand-700 dark:text-brand-300 text-xs font-bold mb-6 border border-brand-200/80 dark:border-brand-800/80 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Next-Gen Clinical AI Diagnostic System
                    <span class="text-slate-300 dark:text-slate-600">&bull;</span>
                    <span class="font-mono text-[10px] text-teal-600 dark:text-teal-400">v4.0 Enterprise</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white tracking-tight leading-[1.12]">
                    Precision Diagnostics.<br>
                    <span class="text-gradient dark:text-gradient-dark">AI-Explained Reports.</span>
                </h1>

                <!-- Subtitle -->
                <p class="mt-6 text-base sm:text-lg text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                    Experience zero-error barcode specimen custody, instant plain-language patient report summarization, automated critical interval alerts, and live digital queue tracking.
                </p>

                <!-- Interactive Search Box -->
                <form action="{{ route('public.tests') }}" method="GET" class="mt-8 max-w-2xl mx-auto">
                    <div class="relative flex items-center p-2 rounded-2xl bg-white dark:bg-darknavy-800 shadow-xl shadow-slate-200/50 dark:shadow-black/40 border border-slate-200 dark:border-slate-700 focus-within:border-brand-500 focus-within:ring-4 focus-within:ring-brand-500/15 transition-all">
                        <div class="pl-3 text-slate-400">
                            <svg class="w-5 h-5 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input
                            type="text"
                            name="q"
                            placeholder="Search 150+ investigations (e.g. CBC, HbA1c, Thyroid TSH, Lipid Profile, Ultrasound)..."
                            class="w-full px-3.5 py-2.5 bg-transparent border-0 text-xs sm:text-sm font-medium focus:ring-0 focus:outline-none text-slate-900 dark:text-white placeholder-slate-400"
                        >
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-brand-600 to-teal-600 hover:from-brand-700 hover:to-teal-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-brand-500/20 hover:shadow-glow-teal transition-all shrink-0">
                            Search Tests
                        </button>
                    </div>

                    <!-- Quick Search Popular Tags -->
                    <div class="mt-3 flex items-center justify-center flex-wrap gap-1.5 text-xs">
                        <span class="text-slate-400 text-[11px] font-semibold">Popular:</span>
                        <a href="{{ route('public.tests', ['q' => 'CBC']) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-darknavy-800 hover:bg-teal-50 dark:hover:bg-teal-950 text-slate-600 dark:text-slate-300 hover:text-teal-600 text-[11px] transition-colors">CBC</a>
                        <a href="{{ route('public.tests', ['q' => 'Lipid']) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-darknavy-800 hover:bg-teal-50 dark:hover:bg-teal-950 text-slate-600 dark:text-slate-300 hover:text-teal-600 text-[11px] transition-colors">Lipid Profile</a>
                        <a href="{{ route('public.tests', ['q' => 'Glucose']) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-darknavy-800 hover:bg-teal-50 dark:hover:bg-teal-950 text-slate-600 dark:text-slate-300 hover:text-teal-600 text-[11px] transition-colors">Fasting Glucose</a>
                        <a href="{{ route('public.tests', ['q' => 'Thyroid']) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-darknavy-800 hover:bg-teal-50 dark:hover:bg-teal-950 text-slate-600 dark:text-slate-300 hover:text-teal-600 text-[11px] transition-colors">Thyroid TSH</a>
                        <a href="{{ route('public.tests', ['q' => 'Creatinine']) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-darknavy-800 hover:bg-teal-50 dark:hover:bg-teal-950 text-slate-600 dark:text-slate-300 hover:text-teal-600 text-[11px] transition-colors">Serum Creatinine</a>
                    </div>
                </form>

                <!-- Quick Action Command Bar (5 Tiles) -->
                <div class="mt-12 grid grid-cols-2 sm:grid-cols-4 gap-3 max-w-4xl mx-auto">
                    <!-- Action 1: Book Appointment -->
                    <a href="{{ route('public.book-appointment') }}" class="p-4 rounded-2xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm hover:border-brand-500 hover:shadow-glow-teal transition-all text-left group">
                        <div class="w-9 h-9 rounded-xl bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400">Book Phlebotomy</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Walk-in or Home Sample</div>
                    </a>

                    <!-- Action 2: Download Report -->
                    <a href="{{ route('portal.login') }}" class="p-4 rounded-2xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm hover:border-brand-500 hover:shadow-glow-teal transition-all text-left group">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400">Download Report</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Instant OTP or MRN Access</div>
                    </a>

                    <!-- Action 3: Live Lounge TV -->
                    <a href="{{ route('public.tv-queue') }}" class="p-4 rounded-2xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm hover:border-brand-500 hover:shadow-glow-teal transition-all text-left group">
                        <div class="w-9 h-9 rounded-xl bg-sky-50 dark:bg-sky-950 text-sky-600 dark:text-sky-400 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400">Lounge TV Queue</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Real-time Token Display</div>
                    </a>

                    <!-- Action 4: Verify QR Seal -->
                    <a href="{{ route('public.verify-report', 'demo') }}" class="p-4 rounded-2xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm hover:border-brand-500 hover:shadow-glow-teal transition-all text-left group">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400">Verify QR Seal</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Cryptographic Tamper Check</div>
                    </a>
                </div>

                <!-- Stats Counters -->
                <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto text-center">
                    <div class="p-5 rounded-2xl bg-white/80 dark:bg-darknavy-800/80 backdrop-blur border border-slate-200/80 dark:border-slate-800 shadow-sm">
                        <div class="text-3xl font-black text-slate-900 dark:text-white font-mono">150+</div>
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">Clinical Tests &amp; Profiles</div>
                    </div>
                    <div class="p-5 rounded-2xl bg-white/80 dark:bg-darknavy-800/80 backdrop-blur border border-slate-200/80 dark:border-slate-800 shadow-sm">
                        <div class="text-3xl font-black text-brand-600 dark:text-brand-400 font-mono">&lt; 4h</div>
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">STAT Routine Turnaround</div>
                    </div>
                    <div class="p-5 rounded-2xl bg-white/80 dark:bg-darknavy-800/80 backdrop-blur border border-slate-200/80 dark:border-slate-800 shadow-sm">
                        <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400 font-mono">100%</div>
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">Barcode Tube Custody</div>
                    </div>
                    <div class="p-5 rounded-2xl bg-white/80 dark:bg-darknavy-800/80 backdrop-blur border border-slate-200/80 dark:border-slate-800 shadow-sm">
                        <div class="text-3xl font-black text-teal-600 dark:text-teal-400 font-mono">AI Hub</div>
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">Plain Language Explanation</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4-Stage Chain of Custody & AI Workflow Showcase -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-wider text-teal-600 dark:text-teal-400">Total Clinical Transparency</span>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">The 4-Stage Chain of Custody Pipeline</h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Every tube barcode is digitally tracked through analyzers, AI verification, and pathologist sign-off.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
            <!-- Stage 1 -->
            <div class="p-6 rounded-3xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm relative group hover:border-brand-500 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400 font-black text-base flex items-center justify-center mb-4 border border-teal-200 dark:border-teal-800">
                    01
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Barcode Tube Ingestion</h3>
                <p class="mt-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                    Vacutainer blood/urine sample is tagged with a unique cryptographic barcode (`SMP-2026-XXXXX`) right at the phlebotomy draw chair.
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center gap-1.5 text-[11px] font-mono text-teal-600 dark:text-teal-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                    <span>Zero Mislabeling Guarantee</span>
                </div>
            </div>

            <!-- Stage 2 -->
            <div class="p-6 rounded-3xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm relative group hover:border-brand-500 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-sky-950 text-sky-600 dark:text-sky-400 font-black text-base flex items-center justify-center mb-4 border border-sky-200 dark:border-sky-800">
                    02
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Robotic Analyzer Run</h3>
                <p class="mt-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                    Loaded into high-throughput automated analyzers (Sysmex, Roche Cobas) with automated reference interval verification against patient age &amp; gender.
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center gap-1.5 text-[11px] font-mono text-sky-600 dark:text-sky-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                    <span>Direct ASTM/HL7 Interfacing</span>
                </div>
            </div>

            <!-- Stage 3 -->
            <div class="p-6 rounded-3xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm relative group hover:border-brand-500 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 font-black text-base flex items-center justify-center mb-4 border border-purple-200 dark:border-purple-800">
                    03
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">AI Plain Interpretation</h3>
                <p class="mt-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                    Powered by `laravel-ai-hub`, clinical values are translated into reassuring, easy-to-understand explanations with critical out-of-range flag warnings.
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center gap-1.5 text-[11px] font-mono text-purple-600 dark:text-purple-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                    <span>Multi-Provider AI Routing</span>
                </div>
            </div>

            <!-- Stage 4 -->
            <div class="p-6 rounded-3xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm relative group hover:border-brand-500 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 font-black text-base flex items-center justify-center mb-4 border border-emerald-200 dark:border-emerald-800">
                    04
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Pathologist Verification</h3>
                <p class="mt-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                    Consultant Pathologist reviews with AI Differential Co-Pilot, applies digital signature, and publishes multilingual PDF report with anti-tamper QR code.
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center gap-1.5 text-[11px] font-mono text-emerald-600 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>SHA-256 Tamper Sealed</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Health Packages Showcase -->
    <div class="py-16 bg-slate-100/70 dark:bg-darknavy-900/60 border-y border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400">Preventive Healthcare</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">Featured Preventive Health Checkups</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Comprehensive screening packages tailored for diabetes, cardiac health, executive wellness, and senior citizens.</p>
                </div>
                <a href="{{ route('public.tests') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-600 dark:text-brand-400 hover:text-brand-700 dark:hover:text-brand-300">
                    View Complete Catalog &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($popularPackages as $pkg)
                    <div class="p-6 rounded-3xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between hover:border-brand-500 hover:shadow-lg transition-all duration-300 group">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 text-[11px] font-extrabold rounded-lg bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                    {{ $pkg->discount_percentage }}% SAVINGS
                                </span>
                                <span class="font-mono text-xs font-bold text-slate-400">{{ $pkg->code }}</span>
                            </div>

                            <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
                                {{ $pkg->name }}
                            </h3>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                {{ $pkg->description }}
                            </p>

                            <!-- Included Tests Checklist -->
                            <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-700/60 space-y-2 text-xs text-slate-600 dark:text-slate-300">
                                @foreach($pkg->tests->take(4) as $t)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="truncate font-medium">{{ $t->name }}</span>
                                    </div>
                                @endforeach
                                @if($pkg->tests->count() > 4)
                                    <div class="text-[11px] font-semibold text-teal-600 dark:text-teal-400 pt-1">
                                        + {{ $pkg->tests->count() - 4 }} more diagnostic parameters included
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Pricing and Action -->
                        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-400 line-through">${{ number_format($pkg->price * 1.35, 2) }}</span>
                                <div class="text-2xl font-black text-slate-900 dark:text-white font-mono">${{ number_format($pkg->price, 2) }}</div>
                            </div>
                            <a href="{{ route('public.book-appointment', ['package' => $pkg->id]) }}" class="px-4 py-2.5 text-xs font-bold rounded-xl bg-brand-600 hover:bg-brand-700 text-white shadow-sm hover:shadow-glow-teal transition-all">
                                Book Package
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- AI Innovation Showcase Card -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="p-8 lg:p-12 rounded-3xl bg-gradient-to-br from-brand-950 via-slate-900 to-teal-950 text-white border border-brand-800/50 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center relative z-10">
                <div class="space-y-4">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/20 text-teal-300 text-xs font-bold border border-teal-500/30">
                        ⚡ Built with imrandevbd/laravel-ai-hub
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">
                        Transforming Medical Jargon into Plain English
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Laboratory reports filled with confusing numbers like "Platelet: 95 x10^3/µL" cause needless anxiety. Our embedded AI automatically translates observed values into easy-to-read explanations, flags out-of-range indicators, and provides actionable lifestyle tips before your physician consult.
                    </p>
                    <div class="grid grid-cols-2 gap-3 pt-2 text-xs">
                        <div class="p-3 rounded-xl bg-white/10 backdrop-blur border border-white/10">
                            <div class="font-bold text-white">Critical Alerts</div>
                            <div class="text-[11px] text-slate-300 mt-0.5">Automated SMS &amp; email warnings if urgent review is needed.</div>
                        </div>
                        <div class="p-3 rounded-xl bg-white/10 backdrop-blur border border-white/10">
                            <div class="font-bold text-white">Ask About Your Report</div>
                            <div class="text-[11px] text-slate-300 mt-0.5">Interactive RAG chat strictly scoped to the active patient's report.</div>
                        </div>
                    </div>
                </div>

                <!-- Interactive UI Mockup: Sample AI Card -->
                <div class="p-6 rounded-2xl bg-slate-950/80 backdrop-blur border border-slate-800 text-xs space-y-4 shadow-xl">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="font-bold text-white text-xs">AI Patient Summary Preview</span>
                        </div>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-teal-900/60 text-teal-300 border border-teal-700/50">Gemini 1.5 Flash</span>
                    </div>

                    <div class="space-y-2 text-slate-300 leading-relaxed text-[11px]">
                        <p>
                            <strong class="text-white">Executive Summary:</strong> Your Complete Blood Count (CBC) is overall reassuring with normal White Blood Cells (6.5 x10^3/µL) and Hemoglobin (13.8 g/dL).
                        </p>
                        <div class="p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-200">
                            <strong class="text-amber-300 block mb-0.5">⚠️ Mild Observation:</strong>
                            Platelet count is slightly below routine baseline (142,000 /µL vs ref: 150,000-450,000). This is classified as mild thrombocytopenia. Usually benign; recommend hydration and follow-up in 2 weeks.
                        </div>
                    </div>

                    <div class="pt-2 flex items-center justify-between text-[10px] text-slate-400">
                        <span>Pathologist Reviewed &bull; Dr. Sarah Jenkins</span>
                        <a href="/report/cbc-farhan-akhtar-2026" class="text-teal-400 hover:underline font-semibold">View Live Interactive Report &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Laboratory Network / Branches Locator -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-bold uppercase tracking-wider text-teal-600 dark:text-teal-400">Our Facilities</span>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">Multi-Branch Collection &amp; STAT Reference Labs</h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Walk in at any satellite kiosk or central reference laboratory for immediate testing.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 rounded-3xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm hover:border-brand-500 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-2.5 py-0.5 text-[10px] font-extrabold rounded-full bg-teal-50 dark:bg-teal-950 text-teal-700 dark:text-teal-400 border border-teal-200 dark:border-teal-800">
                        CENTRAL REFERENCE LAB
                    </span>
                    <span class="text-xs font-mono text-emerald-500 font-bold">● 24/7 OPEN</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Central Molecular Reference Lab</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Plot 42, Health Avenue, Medical City, Dhaka</p>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Queue Wait: <strong class="text-slate-800 dark:text-slate-200">~8 Mins</strong></span>
                    <a href="{{ route('public.book-appointment') }}" class="font-bold text-brand-600 dark:text-brand-400 hover:underline">Book Slot &rarr;</a>
                </div>
            </div>

            <div class="p-6 rounded-3xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm hover:border-brand-500 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-2.5 py-0.5 text-[10px] font-extrabold rounded-full bg-slate-100 dark:bg-darknavy-700 text-slate-700 dark:text-slate-300">
                        IMAGING &amp; SATELLITE
                    </span>
                    <span class="text-xs font-mono text-emerald-500 font-bold">● 07:00 - 23:00</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Downtown Collection Hub</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Tower 8, Commercial Road, Motijheel, Dhaka</p>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Queue Wait: <strong class="text-slate-800 dark:text-slate-200">~12 Mins</strong></span>
                    <a href="{{ route('public.book-appointment') }}" class="font-bold text-brand-600 dark:text-brand-400 hover:underline">Book Slot &rarr;</a>
                </div>
            </div>

            <div class="p-6 rounded-3xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm hover:border-brand-500 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-2.5 py-0.5 text-[10px] font-extrabold rounded-full bg-slate-100 dark:bg-darknavy-700 text-slate-700 dark:text-slate-300">
                        SATELLITE DROP POINT
                    </span>
                    <span class="text-xs font-mono text-emerald-500 font-bold">● 08:00 - 20:00</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Northside Diagnostic Kiosk</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Sector 11, Uttara Model Town, Dhaka</p>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Queue Wait: <strong class="text-slate-800 dark:text-slate-200">~5 Mins</strong></span>
                    <a href="{{ route('public.book-appointment') }}" class="font-bold text-brand-600 dark:text-brand-400 hover:underline">Book Slot &rarr;</a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

