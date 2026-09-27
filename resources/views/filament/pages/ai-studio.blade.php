<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Overview Banner -->
        <div class="p-6 sm:p-8 bg-gradient-to-r from-teal-950 via-teal-900 to-slate-900 rounded-3xl text-white shadow-2xl relative overflow-hidden border border-teal-800/60">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-teal-400/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div class="space-y-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-500/20 text-teal-300 rounded-full text-xs font-bold border border-teal-500/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Powered by imrandevbd/laravel-ai-hub
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight">AI Diagnostic Intelligence Center</h2>
                    <p class="text-xs sm:text-sm text-teal-100/80 max-w-2xl leading-relaxed">
                        Autonomous multi-provider failover routing, plain-language patient report summarization, abnormal reference interval flagging, and prescription OCR auto-entry parser.
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="px-5 py-3.5 bg-white/10 backdrop-blur rounded-2xl border border-white/10 text-center">
                        <div class="text-[10px] uppercase font-bold text-teal-200">Active Primary</div>
                        <div class="text-lg font-black text-white uppercase font-mono">{{ config('ai-hub.default', 'Gemini') }}</div>
                    </div>
                    <div class="px-5 py-3.5 bg-white/10 backdrop-blur rounded-2xl border border-white/10 text-center">
                        <div class="text-[10px] uppercase font-bold text-teal-200">Total Spend</div>
                        <div class="text-lg font-black text-teal-300 font-mono">${{ number_format($totalCost, 4) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Provider Failover Chain -->
        <x-filament::section>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Autonomous Multi-Provider Failover Matrix</h3>
                    <p class="text-xs text-gray-500">Auto-routes to the next provider in &lt; 200ms on 429 rate limit or 5xx outage</p>
                </div>
                <span class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950 px-2.5 py-1 rounded-full border border-emerald-200 dark:border-emerald-800">
                    5 Providers Registered
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-3.5">
                @foreach($providers as $index => $provider)
                    <div class="p-4 rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-900/60 relative hover:border-teal-500 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black uppercase text-gray-400">Tier {{ $index + 1 }}</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        </div>
                        <div class="font-black text-sm text-gray-900 dark:text-white capitalize">{{ $provider }}</div>
                        <div class="text-[11px] text-gray-500 font-mono mt-1">
                            {{ $provider === 'gemini' ? 'Gemini 1.5 Flash' : ($provider === 'openai' ? 'GPT-4o Mini' : ($provider === 'anthropic' ? 'Claude 3.5 Sonnet' : ($provider === 'groq' ? 'Llama 3.3 70B' : 'Ollama Local'))) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <!-- OCR & Auto-Entry Interactive Sandbox -->
        <x-filament::section>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <span class="p-2.5 rounded-xl bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400 border border-teal-200/60 dark:border-teal-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">AI Prescription &amp; Requisition OCR Auto-Entry</h3>
                        <p class="text-xs text-gray-500">Parses raw handwritten doctor slips into structured JSON to instantly pre-fill patient orders</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                        Sample Doctor Prescription / Requisition Text:
                    </label>
                    <textarea
                        wire:model="ocrInput"
                        rows="7"
                        class="w-full rounded-2xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs font-mono p-3.5 focus:border-teal-500 focus:ring-teal-500 placeholder-gray-400"
                        placeholder="Paste sample clinical prescription text here... (e.g. Pt: Farhan Akhtar, 45M, c/o fatigue. Adv: CBC, HbA1c, Serum Creatinine, Lipid Profile. Ref: Prof. Dr. Azad)"
                    ></textarea>
                    <div class="mt-2.5 flex justify-end">
                        <x-filament::button wire:click="runOcrTest" icon="heroicon-m-bolt" color="primary">
                            Run AI OCR Extraction
                        </x-filament::button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                        Structured Order JSON Output (Pre-fills Lab Intake):
                    </label>
                    <div class="h-[185px] overflow-y-auto p-3.5 bg-gray-900 rounded-2xl text-emerald-400 font-mono text-xs border border-gray-800 shadow-inner">
                        @if($ocrResult)
                            <pre>{{ json_encode($ocrResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        @else
                            <div class="text-gray-500 italic flex items-center justify-center h-full text-center p-4">
                                Click "Run AI OCR Extraction" to test structured JSON auto-entry...
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-filament::section>

        <!-- Latency & Log History -->
        <x-filament::section>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Live AI Hub Studio Telemetry</h3>
                    <p class="text-xs text-gray-500">Full audit log of token usage, latency (ms), and cost per clinical request</p>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-200/80 dark:border-gray-800">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-500 dark:text-gray-400 font-bold border-b border-gray-200 dark:border-gray-800">
                        <tr>
                            <th class="py-2.5 px-3">Timestamp</th>
                            <th class="py-2.5 px-3">Feature</th>
                            <th class="py-2.5 px-3">Provider</th>
                            <th class="py-2.5 px-3">Model</th>
                            <th class="py-2.5 px-3">Tokens (In/Out)</th>
                            <th class="py-2.5 px-3">Latency</th>
                            <th class="py-2.5 px-3 text-right">Cost (USD)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($recentLogs as $log)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/40 transition-colors">
                                <td class="py-2.5 px-3 text-gray-500 font-mono">{{ $log->created_at->format('H:i:s') }}</td>
                                <td class="py-2.5 px-3 font-bold text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $log->feature)) }}</td>
                                <td class="py-2.5 px-3 font-mono font-bold text-teal-600 dark:text-teal-400">{{ $log->provider }}</td>
                                <td class="py-2.5 px-3 text-gray-500 font-mono">{{ $log->model }}</td>
                                <td class="py-2.5 px-3 text-gray-600 dark:text-gray-400 font-mono">{{ $log->input_tokens }} / {{ $log->output_tokens }}</td>
                                <td class="py-2.5 px-3 text-gray-600 dark:text-gray-400 font-mono">
                                    <span class="{{ $log->latency_ms > 1500 ? 'text-amber-500 font-bold' : 'text-emerald-600 font-bold' }}">{{ $log->latency_ms }} ms</span>
                                </td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($log->estimated_cost_usd, 6) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-gray-400">No telemetry recorded yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
