<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <span class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </span>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Biometric Attendance &amp; Shift Roster</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Integrated via <code>laravel-attendance-hub</code> (ZKTeco / ADMS)</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full font-mono font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Terminal Active
            </span>
        </div>

        <div class="grid grid-cols-3 gap-3.5 mb-5">
            <div class="p-4 bg-gray-50 dark:bg-gray-800/60 rounded-2xl border border-gray-200/60 dark:border-gray-700 text-center">
                <div class="text-[11px] font-semibold text-gray-500 dark:text-gray-400">Scheduled Technicians</div>
                <div class="text-2xl font-black text-gray-900 dark:text-white font-mono mt-1">{{ $totalStaff }}</div>
                <div class="text-[10px] text-gray-400 mt-0.5">Morning &bull; STAT &bull; Night</div>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-800/60 rounded-2xl border border-gray-200/60 dark:border-gray-700 text-center">
                <div class="text-[11px] font-semibold text-gray-500 dark:text-gray-400">Punched In On Duty</div>
                <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono mt-1">{{ $presentToday }}</div>
                <div class="text-[10px] text-emerald-500 font-semibold mt-0.5">&bull; Active on Analyzer Bench</div>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-800/60 rounded-2xl border border-gray-200/60 dark:border-gray-700 text-center">
                <div class="text-[11px] font-semibold text-gray-500 dark:text-gray-400">Late / Shift Deviations</div>
                <div class="text-2xl font-black text-amber-500 font-mono mt-1">{{ $lateToday }}</div>
                <div class="text-[10px] text-amber-500 font-semibold mt-0.5">&gt; 15 min grace period</div>
            </div>
        </div>

        <div class="space-y-2.5">
            @forelse($activeStaffLogs as $log)
                <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800/80 rounded-xl border border-gray-200/70 dark:border-gray-700 text-xs shadow-sm hover:border-emerald-500 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center text-xs">
                            {{ substr($log->user->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 dark:text-white">{{ $log->user->name ?? 'Laboratory Staff' }}</div>
                            <div class="text-[11px] text-gray-500">{{ $log->shift->name ?? 'Standard Shift' }} &bull; {{ $log->branch->name ?? 'Central Lab' }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="font-mono text-gray-800 dark:text-gray-200 font-bold">{{ $log->check_in ? $log->check_in->format('h:i A') : '--:--' }}</span>
                        <div class="text-[10px] font-bold uppercase tracking-wider {{ $log->status === 'late' ? 'text-amber-500' : 'text-emerald-600 dark:text-emerald-400' }}">
                            {{ strtoupper($log->status) }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-xs text-gray-400">No shift punches registered today</div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
