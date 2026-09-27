@extends('layouts.public')

@section('title', 'Electronic Report Authenticity Verification | Apex Diagnostics')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($report)
        <div class="p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl text-center">
            <!-- Green Shield Seal -->
            <div class="w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>

            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 text-xs font-semibold mb-3">
                &check; Verified Electronic Medical Record
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">Authentic Diagnostic Report</h1>
            <p class="text-xs text-slate-500 mt-1">This document matches our laboratory database records exactly.</p>

            <div class="my-6 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 text-left text-xs space-y-2 border border-slate-200 dark:border-slate-700">
                <div class="flex justify-between">
                    <span class="text-slate-400">Report Number:</span>
                    <span class="font-bold text-slate-900 dark:text-white font-mono">{{ $report->report_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Patient:</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ $report->patient->name }} (MRN: {{ $report->patient->mrn }})</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Investigation:</span>
                    <span class="font-semibold text-brand-600 dark:text-brand-400">{{ $report->orderItem->test->name ?? 'Diagnostic Test' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Signed Pathologist:</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ $report->pathologist->name ?? 'Consultant Pathologist' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Verification Timestamp:</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ $report->verified_at ? $report->verified_at->format('d-M-Y h:i A') : 'Pending' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Issuing Facility:</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ $report->branch->name ?? 'Apex Diagnostics Central Lab' }}</span>
                </div>
            </div>

            <!-- Cryptographic Signature -->
            <div class="p-3 bg-slate-100 dark:bg-slate-800 rounded-xl font-mono text-[10px] text-slate-500 break-all text-left mb-6">
                <span class="font-semibold text-slate-700 dark:text-slate-300 block mb-1">SHA-256 Digital Verification Fingerprint:</span>
                {{ $report->verification_hash }}
            </div>

            <a href="{{ route('report.view', $report->slug) }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-semibold rounded-xl bg-brand-600 hover:bg-brand-700 text-white transition-colors">
                View Full Patient Clinical Report
            </a>
        </div>
    @else
        <div class="p-8 bg-white dark:bg-slate-900 rounded-3xl border border-red-200 dark:border-red-900 shadow-xl text-center">
            <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-400 mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h1 class="text-2xl font-bold text-red-600 dark:text-red-400">Authenticity Verification Failed</h1>
            <p class="text-xs text-slate-500 mt-2">No active laboratory record matches the cryptographic hash: <code class="text-slate-700 dark:text-slate-300">{{ $hash }}</code></p>
            <p class="text-xs text-slate-500 mt-1">This report may be unissued, altered, or fraudulent. Contact the laboratory administration immediately.</p>
        </div>
    @endif
</div>
@endsection
