@extends('layouts.public')

@section('title', "Patient Dashboard - {$patient->name} | Apex Diagnostics")

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Patient Header Card -->
    <div class="p-6 bg-gradient-to-r from-teal-800 to-slate-900 rounded-3xl text-white shadow-xl mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-teal-500/20 border border-teal-400/40 text-teal-300 font-bold text-xl flex items-center justify-center">
                {{ substr($patient->name, 0, 1) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold tracking-tight">{{ $patient->name }}</h1>
                    @if($patient->blood_group)
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                            {{ $patient->blood_group }}
                        </span>
                    @endif
                </div>
                <div class="text-xs text-teal-200 mt-1 flex flex-wrap gap-x-4 gap-y-1">
                    <span>MRN: <strong class="font-mono text-white">{{ $patient->mrn }}</strong></span>
                    <span>Age/Gender: <strong>{{ $patient->formatted_age }}, {{ ucfirst($patient->gender) }}</strong></span>
                    <span>Phone: <strong>{{ $patient->phone }}</strong></span>
                </div>
            </div>
        </div>

        <form action="{{ route('portal.logout') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 text-xs font-semibold rounded-xl bg-white/10 hover:bg-white/20 text-white transition-colors">
                Sign Out
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Reports Column (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="p-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Diagnostic Laboratory Reports</h2>
                    <span class="text-xs text-slate-500">{{ $recentReports->count() }} records</span>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($recentReports as $rep)
                        <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-mono text-xs font-bold text-brand-600 dark:text-brand-400">{{ $rep->report_number }}</span>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $rep->status === 'published' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-50 text-amber-700' }}">
                                        {{ strtoupper($rep->status) }}
                                    </span>
                                    @if($rep->ai_summary)
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-purple-50 dark:bg-purple-950 text-purple-700 dark:text-purple-300">
                                            🤖 AI Summarized
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                                    {{ $rep->orderItem->test->name ?? 'Diagnostic Panel' }}
                                </h3>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    Verified: {{ $rep->verified_at ? $rep->verified_at->format('d-M-Y h:i A') : 'Processing in Laboratory' }}
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('report.view', $rep->slug) }}" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-teal-50 dark:bg-teal-950 text-teal-700 dark:text-teal-300 hover:bg-teal-100 transition-colors">
                                    View Report &amp; AI
                                </a>
                                <a href="{{ route('report.pdf', $rep->slug) }}" target="_blank" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                    PDF
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400">
                            No laboratory reports published yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar / Linked Family & Appointments -->
        <div class="space-y-6">
            <!-- Upcoming Appointments -->
            <div class="p-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3">Appointments &amp; Queue Tokens</h3>
                <div class="space-y-3">
                    @forelse($upcomingAppointments as $apt)
                        <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-100 dark:border-slate-700/60 text-xs">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-brand-600 font-mono">{{ $apt->token_number }}</span>
                                <span class="px-2 py-0.5 text-[10px] rounded bg-teal-100 dark:bg-teal-950 text-teal-800 dark:text-teal-300 font-medium">
                                    {{ strtoupper($apt->queue_status) }}
                                </span>
                            </div>
                            <div class="font-semibold text-slate-900 dark:text-white">{{ $apt->appointment_date->format('d-M-Y') }} &bull; {{ $apt->time_slot }}</div>
                            <div class="text-[11px] text-slate-500 mt-1">{{ $apt->test->name ?? $apt->testPackage->name ?? 'Diagnostic Test' }}</div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 py-2">No active appointments scheduled.</div>
                    @endforelse
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('public.book-appointment') }}" class="block text-center text-xs font-semibold text-brand-600 hover:underline">
                        + Schedule New Investigation
                    </a>
                </div>
            </div>

            <!-- Linked Family Profiles -->
            <div class="p-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3">Linked Family Profiles</h3>
                <div class="space-y-2">
                    @forelse($patient->familyMembers as $fam)
                        <div class="flex items-center justify-between p-2.5 bg-slate-50 dark:bg-slate-800 rounded-xl text-xs">
                            <div>
                                <div class="font-semibold text-slate-900 dark:text-white">{{ $fam->relativePatient->name ?? 'Family Member' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $fam->relationship }} &bull; MRN: {{ $fam->relativePatient->mrn ?? '' }}</div>
                            </div>
                            <span class="text-[10px] text-emerald-600 font-medium">Synced</span>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 py-1">No linked dependent profiles.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
