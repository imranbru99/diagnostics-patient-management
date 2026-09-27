<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="p-2 rounded-lg bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </span>
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Live Sample Tracking Kanban</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Real-time custody pipeline across central lab & satellite branches</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Auto-Syncing Active
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <!-- Stage 1: Collected -->
            <div class="bg-gray-50/80 dark:bg-gray-900/60 p-3 rounded-xl border border-gray-200/70 dark:border-gray-800">
                <div class="flex items-center justify-between pb-2 mb-2 border-b border-gray-200 dark:border-gray-800">
                    <span class="text-xs font-bold uppercase tracking-wider text-sky-700 dark:text-sky-400">1. Collected</span>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-sky-100 dark:bg-sky-950 text-sky-700 dark:text-sky-300">{{ count($collected) }}</span>
                </div>
                <div class="space-y-2">
                    @forelse($collected as $item)
                        <div class="p-2.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:border-teal-500 transition-all">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-mono text-xs font-bold text-gray-800 dark:text-gray-200">{{ $item->barcode }}</span>
                                <span class="text-[10px] text-gray-400">{{ $item->collected_at ? $item->collected_at->diffForHumans(null, true) : 'just now' }}</span>
                            </div>
                            <div class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $item->patient->name ?? 'Patient' }}</div>
                            <div class="text-[11px] text-teal-600 dark:text-teal-400 truncate">{{ $item->orderItem->test->name ?? 'Test' }}</div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-xs text-gray-400">No samples in queue</div>
                    @endforelse
                </div>
            </div>

            <!-- Stage 2: In Transit -->
            <div class="bg-gray-50/80 dark:bg-gray-900/60 p-3 rounded-xl border border-gray-200/70 dark:border-gray-800">
                <div class="flex items-center justify-between pb-2 mb-2 border-b border-gray-200 dark:border-gray-800">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">2. In Transit</span>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300">{{ count($in_transit) }}</span>
                </div>
                <div class="space-y-2">
                    @forelse($in_transit as $item)
                        <div class="p-2.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:border-amber-500 transition-all">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-mono text-xs font-bold text-gray-800 dark:text-gray-200">{{ $item->barcode }}</span>
                                <span class="text-[10px] text-amber-500 font-medium">Couriered</span>
                            </div>
                            <div class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $item->patient->name ?? 'Patient' }}</div>
                            <div class="text-[11px] text-amber-600 dark:text-amber-400 truncate">{{ $item->orderItem->test->name ?? 'Test' }}</div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-xs text-gray-400">No transit transfers</div>
                    @endforelse
                </div>
            </div>

            <!-- Stage 3: Received at Central Lab -->
            <div class="bg-gray-50/80 dark:bg-gray-900/60 p-3 rounded-xl border border-gray-200/70 dark:border-gray-800">
                <div class="flex items-center justify-between pb-2 mb-2 border-b border-gray-200 dark:border-gray-800">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 dark:text-indigo-400">3. Lab Received</span>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300">{{ count($received_at_lab) }}</span>
                </div>
                <div class="space-y-2">
                    @forelse($received_at_lab as $item)
                        <div class="p-2.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:border-indigo-500 transition-all">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-mono text-xs font-bold text-gray-800 dark:text-gray-200">{{ $item->barcode }}</span>
                                <span class="text-[10px] text-indigo-500">Accessioned</span>
                            </div>
                            <div class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $item->patient->name ?? 'Patient' }}</div>
                            <div class="text-[11px] text-indigo-600 dark:text-indigo-400 truncate">{{ $item->orderItem->test->name ?? 'Test' }}</div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-xs text-gray-400">No accessioned items</div>
                    @endforelse
                </div>
            </div>

            <!-- Stage 4: In Testing / Analyzer -->
            <div class="bg-gray-50/80 dark:bg-gray-900/60 p-3 rounded-xl border border-gray-200/70 dark:border-gray-800">
                <div class="flex items-center justify-between pb-2 mb-2 border-b border-gray-200 dark:border-gray-800">
                    <span class="text-xs font-bold uppercase tracking-wider text-purple-700 dark:text-purple-400">4. In Testing</span>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300">{{ count($in_testing) }}</span>
                </div>
                <div class="space-y-2">
                    @forelse($in_testing as $item)
                        <div class="p-2.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:border-purple-500 transition-all">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-mono text-xs font-bold text-gray-800 dark:text-gray-200">{{ $item->barcode }}</span>
                                <span class="w-2 h-2 rounded-full bg-purple-500 animate-ping"></span>
                            </div>
                            <div class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $item->patient->name ?? 'Patient' }}</div>
                            <div class="text-[11px] text-purple-600 dark:text-purple-400 truncate">{{ $item->orderItem->test->name ?? 'Test' }}</div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-xs text-gray-400">No analyzer active runs</div>
                    @endforelse
                </div>
            </div>

            <!-- Stage 5: Verified & Report Ready -->
            <div class="bg-gray-50/80 dark:bg-gray-900/60 p-3 rounded-xl border border-gray-200/70 dark:border-gray-800">
                <div class="flex items-center justify-between pb-2 mb-2 border-b border-gray-200 dark:border-gray-800">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">5. Report Ready</span>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300">{{ count($verified) }}</span>
                </div>
                <div class="space-y-2">
                    @forelse($verified as $item)
                        <div class="p-2.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:border-emerald-500 transition-all">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-mono text-xs font-bold text-gray-800 dark:text-gray-200">{{ $item->barcode }}</span>
                                <span class="text-[10px] text-emerald-600 font-semibold">Verified</span>
                            </div>
                            <div class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $item->patient->name ?? 'Patient' }}</div>
                            <div class="text-[11px] text-emerald-600 dark:text-emerald-400 truncate">{{ $item->orderItem->test->name ?? 'Test' }}</div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-xs text-gray-400">No published reports</div>
                    @endforelse
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
