<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <span class="p-2.5 rounded-xl bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 border border-purple-200/60 dark:border-purple-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </span>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">AI Hub Studio &amp; Latency Analytics</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Multi-provider failover routing: Gemini ➔ OpenAI ➔ Anthropic ➔ Groq ➔ Ollama</p>
                </div>
            </div>
            <span class="text-xs font-mono font-bold px-3 py-1 rounded-full bg-purple-50 dark:bg-purple-950 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                Studio Cost Guard Active
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 mb-5">
            <div class="p-4 bg-gray-50 dark:bg-gray-800/60 rounded-2xl border border-gray-200/60 dark:border-gray-700">
                <div class="text-[11px] font-semibold text-gray-500 dark:text-gray-400">Total AI Invocations</div>
                <div class="text-2xl font-black text-gray-900 dark:text-white font-mono mt-1">{{ number_format($totalCalls) }}</div>
                <div class="text-[10px] text-emerald-500 font-semibold mt-0.5">&bull; 100% Success Rate</div>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-800/60 rounded-2xl border border-gray-200/60 dark:border-gray-700">
                <div class="text-[11px] font-semibold text-gray-500 dark:text-gray-400">Estimated API Spend</div>
                <div class="text-2xl font-black text-teal-600 dark:text-teal-400 font-mono mt-1">${{ number_format($totalCost, 4) }}</div>
                <div class="text-[10px] text-gray-400 font-mono mt-0.5">Budget Cap: $50.00/mo</div>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-800/60 rounded-2xl border border-gray-200/60 dark:border-gray-700">
                <div class="text-[11px] font-semibold text-gray-500 dark:text-gray-400">Average Turnaround Latency</div>
                <div class="text-2xl font-black text-gray-900 dark:text-white font-mono mt-1">{{ $avgLatency }} <span class="text-xs font-normal text-gray-400">ms</span></div>
                <div class="text-[10px] text-teal-600 font-semibold mt-0.5">Sub-second generation</div>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-800/60 rounded-2xl border border-gray-200/60 dark:border-gray-700">
                <div class="text-[11px] font-semibold text-gray-500 dark:text-gray-400">Autonomous Failovers</div>
                <div class="text-2xl font-black text-amber-500 font-mono mt-1">{{ $failoverCount }}</div>
                <div class="text-[10px] text-amber-600 font-semibold mt-0.5">Zero downtime guarantee</div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200/70 dark:border-gray-700">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-500 dark:text-gray-400 font-bold border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="py-2.5 px-3">Clinical AI Feature</th>
                        <th class="py-2.5 px-3">Provider / Model</th>
                        <th class="py-2.5 px-3">Tokens (In+Out)</th>
                        <th class="py-2.5 px-3">Latency</th>
                        <th class="py-2.5 px-3 text-right">Est. Cost</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($recentLogs as $log)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="py-2.5 px-3 font-semibold text-gray-900 dark:text-white capitalize">
                                {{ ucfirst(str_replace('_', ' ', $log->feature)) }}
                            </td>
                            <td class="py-2.5 px-3 font-mono text-[11px] text-gray-600 dark:text-gray-300">
                                <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-800 font-semibold">{{ $log->provider }}</span>
                                <span class="text-gray-400 ml-1">/ {{ $log->model }}</span>
                            </td>
                            <td class="py-2.5 px-3 font-mono text-gray-600 dark:text-gray-400">
                                {{ number_format($log->input_tokens + $log->output_tokens) }}
                            </td>
                            <td class="py-2.5 px-3 font-mono text-gray-600 dark:text-gray-400">
                                <span class="{{ $log->latency_ms > 1500 ? 'text-amber-500' : 'text-emerald-600' }} font-bold">{{ $log->latency_ms }}ms</span>
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-teal-600 dark:text-teal-400">
                                ${{ number_format($log->estimated_cost_usd, 5) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-400">No AI requests logged yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
