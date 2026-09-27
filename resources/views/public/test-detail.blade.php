@extends('layouts.public')

@section('title', "{$test->name} ({$test->code}) - Diagnostic Test Details | Apex Diagnostics")
@section('meta_description', "Understand {$test->name} test preparation, specimen collection requirements, biological parameters evaluated, and turnaround time.")

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
        <a href="{{ route('home') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Home</a>
        <span>&rsaquo;</span>
        <a href="{{ route('public.tests') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Test Directory</a>
        <span>&rsaquo;</span>
        <a href="{{ route('public.tests', ['category' => $test->category->slug ?? '']) }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">{{ $test->category->name ?? 'Clinical' }}</a>
        <span>&rsaquo;</span>
        <span class="text-slate-900 dark:text-white font-bold">{{ $test->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- Main Test Dossier (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="p-8 bg-white dark:bg-darknavy-800 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-8">

                <!-- Header Info -->
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="px-3 py-1 font-mono text-xs font-bold rounded-lg bg-teal-50 dark:bg-teal-950 text-teal-700 dark:text-teal-400 border border-teal-200 dark:border-teal-800">
                            {{ $test->code }}
                        </span>
                        <span class="px-3 py-1 text-xs font-bold rounded-lg bg-slate-100 dark:bg-darknavy-900 text-slate-600 dark:text-slate-300">
                            Department of {{ $test->category->name ?? 'Clinical Pathology' }}
                        </span>
                        <span class="px-3 py-1 text-xs font-bold rounded-lg bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                            ⚡ &le; {{ $test->tat_hours }} Hours Turnaround
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ $test->name }}
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                        Diagnostic specimen evaluation utilizing automated Sysmex &amp; Roche Cobas analytical systems with cryptographic barcode chain-of-custody tracking.
                    </p>
                </div>

                <!-- Specimen & Phlebotomy Spec Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-5 rounded-2xl bg-slate-50 dark:bg-darknavy-900 border border-slate-200/80 dark:border-slate-800 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-1">Specimen Matrix:</span>
                        <strong class="text-slate-900 dark:text-white text-sm font-semibold">{{ $test->sample_type }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-1">Collection Tube:</span>
                        <strong class="text-teal-600 dark:text-teal-400 text-sm font-semibold">{{ $test->collection_tube ?? 'Standard Tube' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-1">Sample Volume:</span>
                        <strong class="text-slate-900 dark:text-white text-sm font-semibold">2.0 - 3.0 mL</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-1">Accreditation:</span>
                        <strong class="text-emerald-600 dark:text-emerald-400 text-sm font-semibold">ISO 15189:2022</strong>
                    </div>
                </div>

                <!-- Parameters Evaluated Table -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                            Biochemical &amp; Cellular Parameters Evaluated
                        </h2>
                        <span class="text-xs text-slate-400 font-mono">{{ $test->parameters->count() }} parameters mapped</span>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-700/80">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 dark:bg-darknavy-900 text-slate-500 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-700/80">
                                <tr>
                                    <th class="py-3 px-4">Parameter Name</th>
                                    <th class="py-3 px-4">Standard Unit</th>
                                    <th class="py-3 px-4">Standard Biological Reference Range</th>
                                    <th class="py-3 px-4 text-right">Methodology</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($test->parameters as $param)
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-darknavy-900/40 transition-colors">
                                        <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">
                                            {{ $param->name }}
                                        </td>
                                        <td class="py-3.5 px-4 font-mono text-slate-600 dark:text-slate-400">
                                            {{ $param->unit ?? 'Ratio / Index' }}
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-700 dark:text-slate-300">
                                            {{ $param->referenceRanges->first()->text_range ?? 'Demographic specific (configured by age & gender)' }}
                                        </td>
                                        <td class="py-3.5 px-4 text-right font-mono text-[11px] text-teal-600 dark:text-teal-400">
                                            Automated Optical
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-6 text-center text-slate-400">
                                            Parameter attributes are populated dynamically during analyzer calibration.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Patient Preparation Guidelines -->
                <div class="p-6 rounded-2xl bg-teal-50/60 dark:bg-teal-950/30 border border-teal-200 dark:border-teal-900 space-y-3">
                    <h3 class="text-sm font-bold text-teal-900 dark:text-teal-300 flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Phlebotomy &amp; Specimen Preparation Instructions
                    </h3>
                    <p class="text-xs text-teal-800 dark:text-teal-400 leading-relaxed">
                        {{ $test->specimen_instructions ?? 'No strict fasting is required unless combined with Fasting Blood Sugar (FBS) or Lipid Profile. Drink plenty of water prior to the phlebotomy draw to facilitate smooth venipuncture.' }}
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2 text-[11px] text-teal-900 dark:text-teal-300">
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                            <span>Hydrate adequately before blood draw</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                            <span>Inform phlebotomist of any anticoagulant medications</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Sticky Right-Hand Booking Card -->
        <div class="space-y-6 sticky top-28">
            <div class="p-6 bg-white dark:bg-darknavy-800 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl space-y-6">
                <div>
                    <div class="text-xs text-slate-400 font-semibold uppercase">Official Pricing</div>
                    <div class="mt-1 flex items-baseline gap-2">
                        <span class="text-4xl font-black text-slate-900 dark:text-white font-mono">${{ number_format($test->price, 2) }}</span>
                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-bold">Inclusive of VAT</span>
                    </div>
                </div>

                <div class="space-y-3 pt-3 border-t border-slate-100 dark:border-slate-700/60 text-xs text-slate-600 dark:text-slate-300">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Home Phlebotomy:
                        </span>
                        <strong class="text-emerald-600 dark:text-emerald-400">Available Daily</strong>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Turnaround Time:
                        </span>
                        <strong class="text-slate-900 dark:text-white">&le; {{ $test->tat_hours }} Hours</strong>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            AI Explanation:
                        </span>
                        <strong class="text-slate-900 dark:text-white">Included Free</strong>
                    </div>
                </div>

                <div class="pt-2 space-y-3">
                    <a href="{{ route('public.book-appointment', ['test' => $test->id]) }}" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-brand-600 to-teal-600 hover:from-brand-700 hover:to-teal-700 text-white text-xs font-bold shadow-md shadow-brand-500/25 hover:shadow-glow-teal flex items-center justify-center gap-2 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Book Appointment Now
                    </a>

                    <a href="{{ route('public.tests') }}" class="w-full py-3 rounded-2xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold hover:bg-slate-50 dark:hover:bg-darknavy-700 flex items-center justify-center transition-colors">
                        &larr; Browse Other Investigations
                    </a>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 dark:bg-darknavy-900 text-[11px] text-slate-500 dark:text-slate-400 space-y-1">
                    <div class="font-bold text-slate-700 dark:text-slate-300">Need Immediate Assistance?</div>
                    <div>Central Phlebotomy Hotline: <span class="font-mono font-bold text-teal-600">+880 1700-000000</span></div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
