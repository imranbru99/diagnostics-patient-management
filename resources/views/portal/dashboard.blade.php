@extends('layouts.public')

@section('title', "Patient Health Command Center - {$patient->name} | Apex Diagnostics")

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Patient Master Header Card -->
    <div class="p-8 bg-gradient-to-r from-teal-900 via-teal-800 to-slate-900 rounded-3xl text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
        <!-- Radial glow -->
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-teal-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex items-center gap-5 relative z-10">
            <div class="w-16 h-16 rounded-2xl bg-teal-500/20 border border-teal-400/30 text-teal-300 font-black text-2xl flex items-center justify-center shadow-inner">
                {{ substr($patient->name, 0, 1) }}
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">{{ $patient->name }}</h1>
                    @if($patient->blood_group)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-500/20 text-rose-300 border border-rose-500/40">
                            {{ $patient->blood_group }}
                        </span>
                    @endif
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-400/20 text-teal-300 border border-teal-400/30">
                        Verified Patient
                    </span>
                </div>
                <div class="text-xs text-teal-200/90 mt-1.5 flex flex-wrap gap-x-4 gap-y-1">
                    <span>MRN: <strong class="font-mono text-white">{{ $patient->mrn }}</strong></span>
                    <span>Demographics: <strong class="text-white">{{ $patient->formatted_age }}, {{ ucfirst($patient->gender) }}</strong></span>
                    <span>Phone: <strong class="text-white font-mono">{{ $patient->phone }}</strong></span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 relative z-10">
            <a href="{{ route('public.book-appointment') }}" class="px-4 py-2.5 text-xs font-bold rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 shadow-md transition-all">
                + Book New Test
            </a>
            <form action="{{ route('portal.logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2.5 text-xs font-bold rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-colors">
                    Sign Out
                </button>
            </form>
        </div>
    </div>

    <!-- Health Telemetry Overview (4 Tiles) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="text-xs font-semibold text-slate-400">Total Diagnostic Reports</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white font-mono mt-1">
                {{ $patient->reports->count() }}
            </div>
            <div class="text-[11px] text-teal-600 dark:text-teal-400 font-semibold mt-1">Available online 24/7</div>
        </div>

        <div class="p-5 rounded-2xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="text-xs font-semibold text-slate-400">Upcoming Appointments</div>
            <div class="text-3xl font-black text-brand-600 dark:text-brand-400 font-mono mt-1">
                {{ $upcomingAppointments->count() }}
            </div>
            <div class="text-[11px] text-slate-500 mt-1">Tokens reserved</div>
        </div>

        <div class="p-5 rounded-2xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="text-xs font-semibold text-slate-400">Linked Family Accounts</div>
            <div class="text-3xl font-black text-purple-600 dark:text-purple-400 font-mono mt-1">
                {{ $patient->familyMembers->count() }}
            </div>
            <div class="text-[11px] text-slate-500 mt-1">Dependents &amp; Spouses</div>
        </div>

        <div class="p-5 rounded-2xl bg-white dark:bg-darknavy-800 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="text-xs font-semibold text-slate-400">External Documents Vault</div>
            <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400 font-mono mt-1">
                {{ $patient->documents->count() }}
            </div>
            <div class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold mt-1">Prescriptions &amp; Scans</div>
        </div>
    </div>

    <!-- Main Content Area: Reports (2 Cols) & Sidebar (1 Col) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- Reports Column (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="p-6 sm:p-8 bg-white dark:bg-darknavy-800 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700/80">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Diagnostic Laboratory Reports</h2>
                        <p class="text-xs text-slate-400">Certified clinical parameters, AI summaries, and tamper-proof downloads</p>
                    </div>
                    <span class="text-xs font-mono font-bold text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-950 px-2.5 py-1 rounded-full border border-teal-200 dark:border-teal-800">
                        {{ $recentReports->count() }} Records
                    </span>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($recentReports as $rep)
                        <div class="py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 group">
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-mono text-xs font-bold text-teal-600 dark:text-teal-400">{{ $rep->report_number }}</span>
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full {{ $rep->status === 'published' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                        {{ strtoupper($rep->status) }}
                                    </span>
                                    @if($rep->ai_summary)
                                        <span class="px-2.5 py-0.5 text-[10px] font-semibold rounded-full bg-purple-50 dark:bg-purple-950 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800 flex items-center gap-1">
                                            <span>⚡</span> AI Plain Explanation
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
                                    {{ $rep->orderItem->test->name ?? 'Clinical Investigation Panel' }}
                                </h3>

                                <div class="text-xs text-slate-400 flex items-center gap-3">
                                    <span>Verified: <strong>{{ $rep->verified_at ? $rep->verified_at->format('d-M-Y h:i A') : 'Processing in Laboratory' }}</strong></span>
                                    <span>&bull;</span>
                                    <span>Branch: <strong>{{ $rep->branch->name ?? 'Central Lab' }}</strong></span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('report.view', $rep->slug) }}" class="px-4 py-2 text-xs font-bold rounded-xl bg-teal-600 hover:bg-teal-700 text-white shadow-sm hover:shadow-glow-teal transition-all flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    View &amp; AI Chat
                                </a>
                                <a href="{{ route('report.pdf', $rep->slug) }}" target="_blank" class="px-3 py-2 text-xs font-semibold rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-darknavy-700 transition-colors" title="Download Official PDF">
                                    PDF
                                </a>
                                <a href="{{ route('public.verify-report', $rep->verification_hash) }}" target="_blank" class="p-2 text-xs font-semibold rounded-xl border border-slate-300 dark:border-slate-700 text-slate-400 hover:text-emerald-500 hover:bg-slate-100 dark:hover:bg-darknavy-700 transition-colors" title="Verify QR Authenticity">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-xs text-slate-400">
                            No laboratory reports issued yet. Once your phlebotomy sample is processed, reports will appear here automatically.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar: Active Appointments & Family Profiles (1 Col) -->
        <div class="space-y-6">

            <!-- Active Appointments & Tokens -->
            <div class="p-6 bg-white dark:bg-darknavy-800 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700/80">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Active Queue Tokens</h3>
                    <a href="{{ route('public.tv-queue') }}" class="text-[11px] font-bold text-teal-600 dark:text-teal-400 hover:underline">Lounge TV &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($upcomingAppointments as $apt)
                        <div class="p-3.5 bg-slate-50 dark:bg-darknavy-900 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 text-xs space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="font-black text-teal-600 dark:text-teal-400 font-mono text-sm">{{ $apt->token_number }}</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-lg bg-teal-100 dark:bg-teal-950 text-teal-800 dark:text-teal-300">
                                    {{ strtoupper($apt->queue_status) }}
                                </span>
                            </div>
                            <div class="font-bold text-slate-900 dark:text-white truncate">
                                {{ $apt->test->name ?? $apt->testPackage->name ?? 'Diagnostic Test' }}
                            </div>
                            <div class="text-[11px] text-slate-500">
                                {{ $apt->appointment_date->format('d M Y') }} &bull; {{ $apt->time_slot }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-slate-400">
                            No active phlebotomy appointments in queue.
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('public.book-appointment') }}" class="w-full py-2.5 rounded-xl border border-dashed border-teal-500 text-teal-600 dark:text-teal-400 text-xs font-bold hover:bg-teal-50 dark:hover:bg-teal-950/60 flex items-center justify-center transition-colors">
                    + Schedule Phlebotomy Draw
                </a>
            </div>

            <!-- Linked Family Members -->
            <div class="p-6 bg-white dark:bg-darknavy-800 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700/80">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Linked Family Members</h3>
                    <span class="text-xs font-mono text-slate-400">{{ $patient->familyMembers->count() }} profiles</span>
                </div>

                <div class="space-y-2.5">
                    @forelse($patient->familyMembers as $fam)
                        <div class="p-3 bg-slate-50 dark:bg-darknavy-900 rounded-xl border border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                            <div>
                                <strong class="text-slate-900 dark:text-white block">{{ $fam->relativePatient->name ?? 'Family Member' }}</strong>
                                <span class="text-[11px] text-slate-400 capitalize">{{ $fam->relationship }} &bull; MRN: {{ $fam->relativePatient->mrn ?? 'N/A' }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 dark:bg-purple-950 text-purple-700 dark:text-purple-300">
                                Linked
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-4 text-xs text-slate-400">
                            No dependent family accounts linked.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Laboratory Helpline -->
            <div class="p-5 rounded-2xl bg-teal-50/70 dark:bg-darknavy-900 border border-teal-200 dark:border-teal-900 text-xs space-y-2">
                <div class="font-bold text-teal-950 dark:text-teal-200">Patient Laboratory Support</div>
                <p class="text-teal-800 dark:text-teal-400 text-[11px] leading-relaxed">
                    Have questions about your report or need home specimen collection? Our clinical counselors are available 24/7.
                </p>
                <div class="font-mono text-teal-900 dark:text-teal-200 font-bold">+880 1700-000000</div>
            </div>

        </div>

    </div>

</div>
@endsection
