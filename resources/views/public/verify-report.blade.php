@extends('layouts.public')

@section('title', 'Electronic Report Authenticity Verification | Apex Diagnostics')
@section('meta_description', 'Cryptographic verification portal for clinical pathology reports with SHA-256 digital signature validation and anti-tamper authenticity check.')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    <!-- Manual Hash / Code Search Input -->
    <div class="p-6 bg-white dark:bg-darknavy-800 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
            Verify Any Diagnostic Report by Cryptographic Hash or Number:
        </label>
        <form action="{{ route('home') }}" method="GET" onsubmit="event.preventDefault(); window.location.href='/verify-report/' + encodeURIComponent(document.getElementById('hashInput').value.trim());" class="flex gap-2">
            <input
                type="text"
                id="hashInput"
                value="{{ $hash ?? '' }}"
                placeholder="Paste SHA-256 verification hash or enter report code..."
                class="w-full text-xs font-mono rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 px-4 py-3 text-slate-900 dark:text-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 placeholder-slate-400"
            >
            <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-2xl shadow-sm hover:shadow-glow-teal transition-all shrink-0">
                Verify
            </button>
        </form>
    </div>

    @if($report)
        <!-- Verified Certificate Card -->
        <div class="p-8 sm:p-10 bg-white dark:bg-darknavy-800 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl relative overflow-hidden">
            <!-- Background Security Watermark -->
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>

            <div class="text-center pb-8 border-b border-slate-100 dark:border-slate-700/80">
                <!-- Glowing Shield Icon -->
                <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white mx-auto flex items-center justify-center shadow-lg shadow-emerald-500/25 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>

                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 text-xs font-bold mb-2 border border-emerald-200 dark:border-emerald-800">
                    &check; Cryptographically Verified Electronic Health Record
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                    Authentic Diagnostic Document
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-md mx-auto">
                    This document matches the authoritative laboratory information database records exactly with zero alterations.
                </p>
            </div>

            <!-- Certified Parameters Grid -->
            <div class="my-8 p-6 rounded-2xl bg-slate-50 dark:bg-darknavy-900 border border-slate-200 dark:border-slate-800 text-xs space-y-3.5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-2 border-b border-slate-200/60 dark:border-slate-800">
                    <span class="text-slate-500">Report Serial Number:</span>
                    <span class="font-bold text-slate-900 dark:text-white font-mono text-sm">{{ $report->report_number }}</span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-2 border-b border-slate-200/60 dark:border-slate-800">
                    <span class="text-slate-500">Patient Identifier:</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ $report->patient->name }} <span class="font-mono text-teal-600 dark:text-teal-400">(MRN: {{ $report->patient->mrn }})</span></span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-2 border-b border-slate-200/60 dark:border-slate-800">
                    <span class="text-slate-500">Clinical Investigation:</span>
                    <span class="font-bold text-brand-600 dark:text-brand-400">{{ $report->orderItem->test->name ?? 'Diagnostic Panel' }}</span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-2 border-b border-slate-200/60 dark:border-slate-800">
                    <span class="text-slate-500">Signed Consultant Pathologist:</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ $report->pathologist->name ?? 'Consultant Clinical Pathologist' }}</span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-2 border-b border-slate-200/60 dark:border-slate-800">
                    <span class="text-slate-500">Digital Sign-Off Timestamp:</span>
                    <span class="font-semibold text-slate-900 dark:text-white font-mono">{{ $report->verified_at ? $report->verified_at->format('d-M-Y h:i:s A') : 'Verified' }}</span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                    <span class="text-slate-500">Issuing Reference Facility:</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ $report->branch->name ?? 'Apex Diagnostics Central Lab' }}</span>
                </div>
            </div>

            <!-- Cryptographic Ledger Fingerprint -->
            <div class="p-4 bg-slate-100 dark:bg-darknavy-950 rounded-2xl font-mono text-[11px] text-slate-600 dark:text-slate-400 break-all space-y-1 mb-8 border border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center justify-between text-[10px] text-slate-500 uppercase font-bold tracking-wider mb-1">
                    <span>SHA-256 Digital Verification Fingerprint</span>
                    <span class="text-emerald-500 font-bold">&check; Integrity Intact</span>
                </div>
                <div class="text-slate-800 dark:text-slate-200 font-bold">{{ $report->verification_hash }}</div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('report.view', $report->slug) }}" class="w-full sm:w-auto px-6 py-3 text-xs font-bold rounded-xl bg-brand-600 hover:bg-brand-700 text-white shadow-md hover:shadow-glow-teal transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    View Full Interactive Patient Report
                </a>

                <a href="{{ route('report.pdf', $report->slug) }}" target="_blank" class="w-full sm:w-auto px-5 py-3 text-xs font-bold rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-darknavy-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download Official Encrypted PDF
                </a>
            </div>
        </div>
    @else
        <!-- Failed State -->
        <div class="p-8 sm:p-10 bg-white dark:bg-darknavy-800 rounded-3xl border border-rose-200 dark:border-rose-900/80 shadow-2xl text-center">
            <div class="w-16 h-16 rounded-3xl bg-rose-100 dark:bg-rose-950 text-rose-600 dark:text-rose-400 mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h1 class="text-2xl font-black text-rose-600 dark:text-rose-400">Authenticity Verification Failed</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 max-w-md mx-auto">
                No active laboratory record matches the cryptographic fingerprint: <code class="text-slate-800 dark:text-slate-200 font-bold">{{ $hash }}</code>
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-md mx-auto">
                This report may be invalid, modified, or forged. If you received this document as an official medical record, please alert the laboratory administration.
            </p>
            <div class="mt-6">
                <a href="{{ route('home') }}" class="px-5 py-2.5 text-xs font-bold rounded-xl bg-slate-900 dark:bg-slate-700 text-white">
                    Return to Homepage
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
