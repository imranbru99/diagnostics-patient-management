@extends('layouts.public')

@section('title', "Appointment Confirmed - Token #{$appointment->token_number} | Apex Diagnostics")

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="p-8 sm:p-10 bg-white dark:bg-darknavy-800 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl relative overflow-hidden">

        <!-- Top Status Seal -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white mx-auto flex items-center justify-center shadow-lg shadow-emerald-500/25 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 text-xs font-bold mb-2 border border-emerald-200 dark:border-emerald-800">
                &check; Appointment Confirmed &amp; Token Dispatched
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                Digital Phlebotomy Pass
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                An automated SMS confirmation was sent to <strong class="text-slate-700 dark:text-slate-300 font-mono">{{ $appointment->patient->phone }}</strong>
            </p>
        </div>

        <!-- Electronic Pass Body -->
        <div class="p-6 sm:p-8 bg-gradient-to-br from-teal-50/70 via-slate-50 to-emerald-50/70 dark:from-darknavy-900 dark:via-darknavy-850 dark:to-darknavy-900 rounded-3xl border-2 border-dashed border-teal-300 dark:border-teal-800 mb-8 relative">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pb-6 border-b border-teal-200/60 dark:border-teal-800/80">
                <div class="text-center sm:text-left">
                    <span class="text-xs font-black uppercase tracking-wider text-teal-800 dark:text-teal-400">Queue Token Callout</span>
                    <div class="text-6xl font-black font-mono text-teal-900 dark:text-teal-200 tracking-tight my-1">
                        {{ $appointment->token_number }}
                    </div>
                    <div class="text-xs font-mono text-slate-500">Ref: {{ $appointment->appointment_number }}</div>
                </div>

                <!-- Simulated Cryptographic QR Block -->
                <div class="p-3 bg-white dark:bg-darknavy-950 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm text-center shrink-0">
                    <div class="w-24 h-24 bg-slate-900 dark:bg-slate-100 p-2 rounded-xl flex items-center justify-center">
                        <!-- High tech QR graphic placeholder -->
                        <svg class="w-full h-full text-white dark:text-slate-900" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm8-2h8v8h-8V2zm2 2v4h4V4h-4zM2 14h8v8H2v-8zm2 2v4h4v-4H4zm14-2h4v4h-4v-4zm-6 0h4v2h-4v-2zm2 4h4v4h-2v-2h-2v-2zm-2 2h2v2h-2v-2zm6-2h2v4h-2v-4z"/>
                        </svg>
                    </div>
                    <span class="text-[9px] font-mono text-slate-400 block mt-1">Scan at Phlebotomy Kiosk</span>
                </div>
            </div>

            <!-- Meta Data Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-6 text-xs">
                <div>
                    <span class="text-slate-400 block mb-0.5">Patient Name &amp; MRN:</span>
                    <strong class="text-slate-900 dark:text-white font-semibold">{{ $appointment->patient->name }}</strong>
                    <span class="text-teal-600 dark:text-teal-400 font-mono text-[11px] block">MRN: {{ $appointment->patient->mrn }}</span>
                </div>

                <div>
                    <span class="text-slate-400 block mb-0.5">Diagnostic Center Branch:</span>
                    <strong class="text-slate-900 dark:text-white font-semibold">{{ $appointment->branch->name }}</strong>
                    <span class="text-slate-500 text-[11px] block truncate">{{ $appointment->branch->address }}</span>
                </div>

                <div>
                    <span class="text-slate-400 block mb-0.5">Appointment Date &amp; Slot:</span>
                    <strong class="text-slate-900 dark:text-white font-semibold">{{ $appointment->appointment_date->format('l, d M Y') }}</strong>
                    <span class="text-teal-600 dark:text-teal-400 font-semibold text-[11px] block">{{ $appointment->time_slot }}</span>
                </div>

                <div>
                    <span class="text-slate-400 block mb-0.5">Clinical Investigation:</span>
                    <strong class="text-brand-600 dark:text-brand-400 font-semibold">
                        {{ $appointment->test->name ?? $appointment->testPackage->name ?? 'Diagnostic Test' }}
                    </strong>
                </div>
            </div>
        </div>

        <!-- Phlebotomy Draw Preparation Checklist -->
        <div class="mb-8 p-5 rounded-2xl bg-slate-50 dark:bg-darknavy-900 border border-slate-200 dark:border-slate-800 text-xs space-y-2">
            <div class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Phlebotomy Pre-Collection Checklist:
            </div>
            <ul class="space-y-1.5 text-slate-600 dark:text-slate-400 pl-6 list-disc">
                <li>Please arrive approximately 10 minutes prior to your selected slot: <strong>{{ $appointment->time_slot }}</strong>.</li>
                <li>Present this token number (<strong>{{ $appointment->token_number }}</strong>) or your MRN at the reception counter.</li>
                <li>If your test involves Fasting Blood Sugar or Lipids, maintain an 8-12 hour water-only fast.</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <button
                type="button"
                onclick="window.print()"
                class="w-full sm:w-auto px-5 py-2.5 text-xs font-bold rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-darknavy-700 transition-colors flex items-center justify-center gap-2"
            >
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Token Slip
            </button>

            <a
                href="{{ route('public.tv-queue') }}"
                class="w-full sm:w-auto px-5 py-2.5 text-xs font-bold rounded-xl bg-slate-900 dark:bg-darknavy-700 hover:bg-slate-800 text-white transition-colors flex items-center justify-center gap-2"
            >
                <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Track on Lounge TV Screen
            </a>

            <a
                href="{{ route('portal.login') }}"
                class="w-full sm:w-auto px-6 py-2.5 text-xs font-bold rounded-xl bg-gradient-to-r from-brand-600 to-teal-600 hover:from-brand-700 hover:to-teal-700 text-white shadow-md transition-all flex items-center justify-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Patient Portal
            </a>
        </div>

    </div>
</div>
@endsection
