@extends('layouts.public')

@section('title', "{$test->name} ({$test->code}) - Diagnostic Test Details | Apex Diagnostics")
@section('meta_description', "Understand {$test->name} test preparation, specimen collection requirements, biological parameters evaluated, and turnaround time.")

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:underline">Home</a>
        <span>&rsaquo;</span>
        <a href="{{ route('public.tests') }}" class="hover:underline">Test Catalog</a>
        <span>&rsaquo;</span>
        <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $test->name }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Test Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-3 py-1 font-mono text-xs font-bold rounded-lg bg-teal-50 dark:bg-teal-950 text-teal-700 dark:text-teal-400">
                        {{ $test->code }}
                    </span>
                    <span class="text-xs text-slate-400 font-medium">Department of {{ $test->category->name }}</span>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $test->name }}</h1>

                <!-- Key Parameters Table -->
                <div class="mt-8">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-3">Biochemical &amp; Cellular Parameters Evaluated</h3>
                    <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300">
                                <tr>
                                    <th class="p-3">Parameter Name</th>
                                    <th class="p-3">Standard Unit</th>
                                    <th class="p-3">Default Reference Interval</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($test->parameters as $param)
                                    <tr>
                                        <td class="p-3 font-semibold text-slate-800 dark:text-slate-200">{{ $param->name }}</td>
                                        <td class="p-3 text-slate-500 font-mono">{{ $param->unit ?? 'Ratio / Index' }}</td>
                                        <td class="p-3 text-slate-500">
                                            {{ $param->referenceRanges->first()->text_range ?? 'Demographic specific' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-4 text-center text-slate-400">Tabular parameter mapping is configured dynamically on analyzer run.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Patient Instructions -->
                <div class="mt-8 p-5 bg-teal-50/50 dark:bg-teal-950/30 rounded-2xl border border-teal-100 dark:border-teal-900">
                    <h3 class="text-sm font-bold text-teal-900 dark:text-teal-300 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Specimen Preparation Instructions for Patient
                    </h3>
                    <p class="text-xs text-teal-800 dark:text-teal-400 leading-relaxed">
                        {{ $test->specimen_instructions ?? 'No special fasting is typically required unless combined with glucose or fasting lipid investigations. Drink adequate water before phlebotomy.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Sidebar / Booking Card -->
        <div class="space-y-6">
            <div class="p-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm sticky top-24">
                <div class="text-xs text-slate-400 font-semibold mb-1">Standard Lab Pricing</div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-white mb-6">
                    ${{ number_format($test->price, 2) }}
                </div>

                <div class="space-y-3.5 text-xs text-slate-600 dark:text-slate-300 mb-6">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400">Sample Specimen:</span>
                        <span class="font-semibold">{{ $test->sample_type }}</span>
                    </div>
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400">Collection Tube:</span>
                        <span class="font-semibold">{{ $test->collection_tube ?? 'Standard Tube' }}</span>
                    </div>
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400">Turnaround Time:</span>
                        <span class="font-semibold text-brand-600">{{ $test->tat_hours }} Hours (Routine)</span>
                    </div>
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400">Report Delivery:</span>
                        <span class="font-semibold">Online Portal / PDF / SMS</span>
                    </div>
                </div>

                <a href="{{ route('public.book-appointment', ['test' => $test->id]) }}" class="block w-full py-3 text-center text-sm font-semibold rounded-xl bg-brand-600 hover:bg-brand-700 text-white shadow-md shadow-brand-500/20 transition-colors">
                    Schedule Test Appointment
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
