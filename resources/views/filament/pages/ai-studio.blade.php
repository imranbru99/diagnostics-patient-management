<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Overview Banner -->
        <div class="p-6 bg-gradient-to-r from-teal-900 via-teal-800 to-slate-900 rounded-2xl text-white shadow-xl">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="inline-block px-3 py-1 bg-teal-500/20 text-teal-300 rounded-full text-xs font-semibold mb-2">
                        Powered by imrandevbd/laravel-ai-hub
                    </span>
                    <h2 class="text-2xl font-bold tracking-tight">AI Diagnostic Intelligence Center</h2>
                    <p class="text-sm text-teal-100/80 mt-1 max-w-2xl">
                        Autonomous multi-provider failover routing, plain-language patient report summarization, abnormal reference interval flagging, and requisition OCR parser.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-3 bg-white/10 backdrop-blur rounded-xl text-center">
                        <div class="text-xs text-teal-200">Active Provider</div>
                        <div class="text-lg font-bold text-white uppercase">{{ config('ai-hub.default', 'Gemini') }}</div>
                    </div>
                    <div class="px-4 py-3 bg-white/10 backdrop-blur rounded-xl text-center">
                        <div class="text-xs text-teal-200">Total Spend</div>
                        <div class="text-lg font-bold text-teal-300">${{ number_format($totalCost, 4) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Provider Failover Chain -->
        <x-filament::section>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Active Multi-Provider Failover Chain</h3>
                <span class="text-xs text-gray-500">Auto-routes on rate limit or 5xx status</span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                @foreach($providers as $index => $provider)
                    <div class="p-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/40 relative">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase text-gray-400">Tier {{ $index + 1 }}</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        </div>
                        <div class="font-bold text-sm text-gray-900 dark:text-white capitalize">{{ $provider }}</div>
                        <div class="text-[11px] text-gray-500 mt-1">
                            {{ $provider === 'gemini' ? 'Gemini 1.5 Flash' : ($provider === 'openai' ? 'GPT-4o Mini' : 'Claude 3.5 Sonnet') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <!-- OCR & Auto-Entry Interactive Sandbox -->
        <x-filament::section>
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="p-2 rounded-lg bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">AI Prescription / Requisition OCR Sandbox</h3>
                        <p class="text-xs text-gray-500">Extracts patient demographics, requested tests, and doctor notes from raw paper text</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Doctor Prescription / Handwritten Requisition Note:</label>
                    <textarea
                        wire:model="ocrInput"
                        rows="7"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs font-mono p-3 focus:border-teal-500 focus:ring-teal-500"
                        placeholder="Paste sample clinical prescription text here... (e.g. Pt: Farhan Akhtar, 45M, c/o fatigue. Adv: CBC, HbA1c, Serum Creatinine, Lipid Profile. Ref: Prof. Dr. Azad)"
                    ></textarea>
                    <div class="mt-2 flex justify-end">
                        <x-filament::button wire:click="runOcrTest" icon="heroicon-m-bolt" color="primary">
                            Run AI OCR Extraction
                        </x-filament::button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Structured JSON Output (Pre-fills Order Entry):</label>
                    <div class="h-[175px] overflow-y-auto p-3 bg-gray-900 rounded-lg text-emerald-400 font-mono text-xs border border-gray-800">
                        @if($ocrResult)
                            <pre>{{ json_encode($ocrResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        @else
                            <div class="text-gray-500 italic flex items-center justify-center h-full">
                                Click "Run AI OCR Extraction" to test structured JSON auto-entry...
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-filament::section>

        <!-- Latency & Log History -->
        <x-filament::section>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Live AI Hub Studio Telemetry</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 text-gray-400">
                            <th class="pb-2">Time</th>
                            <th class="pb-2">Feature</th>
                            <th class="pb-2">Provider</th>
                            <th class="pb-2">Model</th>
                            <th class="pb-2">Prompt Tokens</th>
                            <th class="pb-2">Completion Tokens</th>
                            <th class="pb-2">Latency</th>
                            <th class="pb-2 text-right">Cost (USD)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($recentLogs as $log)
                            <tr>
                                <td class="py-2 text-gray-500 font-mono">{{ $log->created_at->format('H:i:s') }}</td>
                                <td class="py-2 font-semibold text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $log->feature)) }}</td>
                                <td class="py-2 font-mono text-teal-600">{{ $log->provider }}</td>
                                <td class="py-2 text-gray-500 font-mono">{{ $log->model }}</td>
                                <td class="py-2 text-gray-500">{{ $log->input_tokens }}</td>
                                <td class="py-2 text-gray-500">{{ $log->output_tokens }}</td>
                                <td class="py-2 text-gray-500">{{ $log->latency_ms }} ms</td>
                                <td class="py-2 text-right font-mono text-emerald-600">${{ number_format($log->estimated_cost_usd, 6) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-4 text-center text-gray-400">No telemetry recorded yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
