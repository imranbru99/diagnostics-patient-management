@extends('layouts.public')

@section('title', "Appointment Confirmed - Token #{$appointment->token_number} | Apex Diagnostics")

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl text-center">
        <div class="w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 mx-auto flex items-center justify-center mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </div>

        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">Appointment Scheduled!</h1>
        <p class="text-xs text-slate-500 mt-1">An SMS notification has been dispatched to {{ $appointment->patient->phone }}.</p>

        <!-- Token Card -->
        <div class="my-8 p-6 bg-gradient-to-br from-teal-50 to-emerald-50 dark:from-slate-800 dark:to-slate-850 rounded-2xl border border-teal-200 dark:border-teal-800">
            <div class="text-xs font-bold uppercase tracking-wider text-teal-800 dark:text-teal-400">Lounge Queue Token</div>
            <div class="text-5xl font-black font-mono text-teal-900 dark:text-teal-200 my-2">{{ $appointment->token_number }}</div>
            <div class="text-xs text-slate-500">Ref: {{ $appointment->appointment_number }}</div>
        </div>

        <!-- Appointment Details -->
        <div class="text-left text-xs space-y-2.5 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 mb-6">
            <div class="flex justify-between">
                <span class="text-slate-500">Patient:</span>
                <span class="font-semibold text-slate-900 dark:text-white">{{ $appointment->patient->name }} (MRN: {{ $appointment->patient->mrn }})</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Branch Location:</span>
                <span class="font-semibold text-slate-900 dark:text-white">{{ $appointment->branch->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Scheduled Slot:</span>
                <span class="font-semibold text-slate-900 dark:text-white">{{ $appointment->appointment_date->format('l, d-M-Y') }} ({{ $appointment->time_slot }})</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Investigation:</span>
                <span class="font-semibold text-brand-600 dark:text-brand-400">
                    {{ $appointment->test->name ?? $appointment->testPackage->name ?? 'Diagnostic Panel' }}
                </span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('public.tv-queue') }}" class="px-5 py-2.5 text-xs font-semibold rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200 transition-colors">
                View Lounge TV Queue
            </a>
            <a href="{{ route('portal.login') }}" class="px-5 py-2.5 text-xs font-semibold rounded-xl bg-brand-600 hover:bg-brand-700 text-white transition-colors">
                Open Patient Portal
            </a>
        </div>
    </div>
</div>
@endsection
