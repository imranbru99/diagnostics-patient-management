@extends('layouts.public')

@section('title', 'Diagnostic Test Catalog & Health Packages | Apex Diagnostics')
@section('meta_description', 'Browse our complete catalog of pathology, biochemistry, hematology, and imaging tests with preparation guidelines and pricing.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Diagnostic Test Catalog</h1>
        <p class="text-sm text-slate-500 mt-1">Explore certified clinical investigations, specimen requirements, and turnaround times.</p>
    </div>

    <!-- Filters & Search -->
    <div class="p-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm mb-8">
        <form action="{{ route('public.tests') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-grow">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search by test name, code or specimen (e.g. CBC, Lipid, Urine)..."
                    class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm px-4 py-2.5 text-slate-900 dark:text-white focus:border-brand-500 focus:ring-brand-500"
                >
            </div>
            <div class="w-full md:w-64">
                <select name="category" onchange="this.form.submit()" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm px-4 py-2.5 text-slate-900 dark:text-white">
                    <option value="">All Departments</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors">
                Filter
            </button>
        </form>
    </div>

    <!-- Test Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($tests as $test)
            <div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:border-brand-500 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                            {{ $test->code }}
                        </span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-teal-50 dark:bg-teal-950 text-teal-700 dark:text-teal-400 font-semibold">
                            {{ $test->sample_type }}
                        </span>
                    </div>

                    <h2 class="text-base font-bold text-slate-900 dark:text-white mb-1">
                        <a href="{{ route('public.test-detail', $test->slug) }}" class="hover:text-brand-600 dark:hover:text-brand-400">
                            {{ $test->name }}
                        </a>
                    </h2>
                    <div class="text-xs text-slate-400 mb-3">{{ $test->category->name ?? 'Clinical Pathology' }}</div>

                    <div class="text-xs text-slate-600 dark:text-slate-400 space-y-1 mb-4">
                        <div class="flex items-center gap-1.5">
                            <span class="font-semibold text-slate-500">TAT:</span>
                            <span>{{ $test->tat_hours }} Hours (Routine)</span>
                        </div>
                        @if($test->collection_tube)
                            <div class="flex items-center gap-1.5">
                                <span class="font-semibold text-slate-500">Tube:</span>
                                <span>{{ $test->collection_tube }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div class="text-lg font-bold text-slate-900 dark:text-white">
                        ${{ number_format($test->price, 2) }}
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('public.test-detail', $test->slug) }}" class="text-xs text-slate-600 dark:text-slate-400 hover:underline">
                            Details
                        </a>
                        <a href="{{ route('public.book-appointment', ['test' => $test->id]) }}" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-brand-600 hover:bg-brand-700 text-white transition-colors">
                            Book
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800">
                <div class="text-slate-400 text-sm">No investigations found matching your filter criteria.</div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $tests->links() }}
    </div>
</div>
@endsection
