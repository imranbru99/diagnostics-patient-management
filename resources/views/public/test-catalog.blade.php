@extends('layouts.public')

@section('title', 'Diagnostic Test Catalog & Health Packages | Apex Diagnostics')
@section('meta_description', 'Search 150+ certified clinical pathology, biochemistry, hematology, and imaging investigations with specimen requirements, tube types, and transparent pricing.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 dark:bg-teal-950 text-teal-700 dark:text-teal-300 text-xs font-bold mb-2 border border-teal-200 dark:border-teal-800">
                <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                Certified Clinical Directory &bull; ISO 15189 Accredited
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                Diagnostic Test Directory
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-2xl">
                Explore automated clinical investigations, biological reference intervals, specimen preparation guidelines, and expedited turnaround times.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('public.book-appointment') }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 hover:shadow-glow-teal transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Book Phlebotomy
            </a>
        </div>
    </div>

    <!-- Search & Department Filter Bar -->
    <div class="p-6 bg-white dark:bg-darknavy-800 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
        <form action="{{ route('public.tests') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search by test name, code, or specimen (e.g. CBC, Serum Ferritin, Lipid, Urea, Urine R/E)..."
                    class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all placeholder-slate-400"
                >
            </div>

            <div class="w-full md:w-72">
                <select
                    name="category"
                    onchange="this.form.submit()"
                    class="w-full py-3 px-4 rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 focus:border-brand-500 focus:ring-brand-500"
                >
                    <option value="">All Clinical Departments</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-brand-600 to-teal-600 hover:from-brand-700 hover:to-teal-700 text-white text-xs sm:text-sm font-bold rounded-2xl shadow-sm transition-all shrink-0">
                Filter
            </button>

            @if(request('q') || request('category'))
                <a href="{{ route('public.tests') }}" class="px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-500 hover:bg-slate-100 dark:hover:bg-darknavy-700 flex items-center justify-center transition-colors">
                    Reset
                </a>
            @endif
        </form>

        <!-- Department Quick Filter Badges -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 pt-1 text-xs">
            <span class="text-slate-400 text-[11px] font-bold shrink-0">Quick Filter:</span>
            <a href="{{ route('public.tests') }}" class="px-3 py-1 rounded-xl font-semibold shrink-0 {{ !request('category') ? 'bg-teal-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-darknavy-900 text-slate-600 dark:text-slate-300 hover:bg-teal-50' }}">
                All ({{ $tests->total() }})
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('public.tests', ['category' => $cat->slug]) }}" class="px-3 py-1 rounded-xl font-semibold shrink-0 {{ request('category') === $cat->slug ? 'bg-teal-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-darknavy-900 text-slate-600 dark:text-slate-300 hover:bg-teal-50 dark:hover:bg-teal-950/60' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Active Result Count & Meta -->
    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 px-1">
        <div>
            Showing <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $tests->firstItem() ?? 0 }}-{{ $tests->lastItem() ?? 0 }}</strong> of <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $tests->total() }}</strong> verified clinical tests
        </div>
        <div class="hidden sm:flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>All pricing includes phlebotomy barcode &amp; AI explanation</span>
        </div>
    </div>

    <!-- Test Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tests as $test)
            @php
                // Tube colors mapping for visual realism
                $tube = strtolower($test->collection_tube ?? '');
                $tubeBadge = 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300';
                $tubeColor = '#64748b';
                if (str_contains($tube, 'edta') || str_contains($tube, 'lavender') || str_contains($tube, 'purple')) {
                    $tubeBadge = 'bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 border border-purple-200 dark:border-purple-800';
                    $tubeColor = '#a855f7';
                } elseif (str_contains($tube, 'sst') || str_contains($tube, 'gel') || str_contains($tube, 'gold') || str_contains($tube, 'yellow')) {
                    $tubeBadge = 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border border-amber-200 dark:border-amber-800';
                    $tubeColor = '#f59e0b';
                } elseif (str_contains($tube, 'fluoride') || str_contains($tube, 'grey') || str_contains($tube, 'gray')) {
                    $tubeBadge = 'bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200';
                    $tubeColor = '#64748b';
                } elseif (str_contains($tube, 'citrate') || str_contains($tube, 'blue')) {
                    $tubeBadge = 'bg-sky-100 text-sky-800 dark:bg-sky-950 dark:text-sky-300 border border-sky-200 dark:border-sky-800';
                    $tubeColor = '#0ea5e9';
                } elseif (str_contains($tube, 'plain') || str_contains($tube, 'red')) {
                    $tubeBadge = 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300 border border-rose-200 dark:border-rose-800';
                    $tubeColor = '#f43f5e';
                }
            @endphp
            <div class="p-6 bg-white dark:bg-darknavy-800 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm hover:border-brand-500 hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                <div>
                    <!-- Header with Code & Department -->
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="font-mono text-xs font-bold px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-darknavy-900 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                            {{ $test->code }}
                        </span>
                        <span class="text-[11px] px-2.5 py-1 rounded-full bg-teal-50 dark:bg-teal-950 text-teal-700 dark:text-teal-400 font-bold border border-teal-200/60 dark:border-teal-800">
                            {{ $test->sample_type }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h2 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
                        <a href="{{ route('public.test-detail', $test->slug) }}">
                            {{ $test->name }}
                        </a>
                    </h2>
                    <div class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                        {{ $test->category->name ?? 'Clinical Diagnostics' }}
                    </div>

                    <!-- Key Attributes & Tube Type -->
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700/60 space-y-2 text-xs">
                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Turnaround (TAT):
                            </span>
                            <strong class="text-slate-800 dark:text-slate-200 font-semibold">&le; {{ $test->tat_hours }} Hours</strong>
                        </div>

                        @if($test->collection_tube)
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-1.5 text-slate-600 dark:text-slate-400">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                    Vacutainer:
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $tubeBadge }}">
                                    {{ $test->collection_tube }}
                                </span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                AI Report Explainer:
                            </span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-semibold text-[11px]">Included</span>
                        </div>
                    </div>
                </div>

                <!-- Footer with Price and Action -->
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Lab Fee</span>
                        <div class="text-xl font-black text-slate-900 dark:text-white font-mono">
                            ${{ number_format($test->price, 2) }}
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('public.test-detail', $test->slug) }}" class="px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-darknavy-700 transition-colors">
                            Details
                        </a>
                        <a href="{{ route('public.book-appointment', ['test' => $test->id]) }}" class="px-4 py-2 text-xs font-bold rounded-xl bg-brand-600 hover:bg-brand-700 text-white shadow-sm hover:shadow-glow-teal transition-all">
                            Book
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-20 bg-white dark:bg-darknavy-800 rounded-3xl border border-slate-200 dark:border-slate-800 p-8">
                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-darknavy-900 text-slate-400 mx-auto flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">No Investigations Found</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto">
                    We could not find any clinical tests matching your search term. Try searching for "CBC", "Lipid", "Glucose", or "Thyroid".
                </p>
                <a href="{{ route('public.tests') }}" class="inline-block mt-4 px-4 py-2 text-xs font-bold rounded-xl bg-brand-600 text-white">
                    Clear Filters
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-6">
        {{ $tests->links() }}
    </div>

</div>
@endsection
