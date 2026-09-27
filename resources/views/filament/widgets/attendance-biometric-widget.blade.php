<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </span>
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Biometric Attendance & Shift Roster</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Integrated via laravel-attendance-hub (ZKTeco / ADMS)</p>
                </div>
            </div>
            <span class="text-xs px-2 py-0.5 rounded font-mono bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300">
                ADMS Terminal Active
            </span>
        </div>

        <div class="grid grid-cols-3 gap-2 mb-4">
            <div class="p-2.5 bg-gray-50 dark:bg-gray-800/60 rounded-lg text-center">
                <div class="text-[11px] text-gray-500">Scheduled Staff</div>
                <div class="text-base font-bold text-gray-900 dark:text-white">{{ $totalStaff }}</div>
            </div>
            <div class="p-2.5 bg-gray-50 dark:bg-gray-800/60 rounded-lg text-center">
                <div class="text-[11px] text-gray-500">Punched In</div>
                <div class="text-base font-bold text-emerald-600 dark:text-emerald-400">{{ $presentToday }}</div>
            </div>
            <div class="p-2.5 bg-gray-50 dark:bg-gray-800/60 rounded-lg text-center">
                <div class="text-[11px] text-gray-500">Late Arrivals</div>
                <div class="text-base font-bold text-amber-500">{{ $lateToday }}</div>
            </div>
        </div>

        <div class="space-y-2">
            @forelse($activeStaffLogs as $log)
                <div class="flex items-center justify-between p-2 bg-white dark:bg-gray-800 rounded border border-gray-100 dark:border-gray-700 text-xs">
                    <div>
                        <div class="font-semibold text-gray-900 dark:text-white">{{ $log->user->name ?? 'Staff' }}</div>
                        <div class="text-[11px] text-gray-500">{{ $log->shift->name ?? 'Day Shift' }} &bull; {{ $log->branch->name ?? 'Branch' }}</div>
                    </div>
                    <div class="text-right">
                        <span class="font-mono text-gray-700 dark:text-gray-300">{{ $log->check_in ? $log->check_in->format('h:i A') : '--' }}</span>
                        <div class="text-[10px] font-semibold {{ $log->status === 'late' ? 'text-amber-500' : 'text-emerald-600' }}">
                            {{ strtoupper($log->status) }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-3 text-xs text-gray-400">No shift punches today</div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
