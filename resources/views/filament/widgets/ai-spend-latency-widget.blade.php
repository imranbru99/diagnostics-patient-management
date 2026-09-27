<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="p-2 rounded-lg bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </span>
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">AI Hub Studio & Latency Analytics</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Multi-provider failover chain: Gemini ➔ OpenAI ➔ Anthropic ➔ Groq</p>
                </div>
            </div>
            <span class="text-xs font-mono font-medium px-2 py-0.5 rounded bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300">
                Studio Cost Guard Active
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
            <div class="p-3 bg-gray-50 dark:bg-gray-800/60 rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="text-[11px] text-gray-500 dark:text-gray-400">AI Invocations</div>
                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($totalCalls) }}</div>
            </div>
            <div class="p-3 bg-gray-50 dark:bg-gray-800/60 rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="text-[11px] text-gray-500 dark:text-gray-400">Est. API Spend</div>
                <div class="text-lg font-bold text-teal-600 dark:text-teal-400">${{ number_format($totalCost, 4) }}</div>
            </div>
            <div class="p-3 bg-gray-50 dark:bg-gray-800/60 rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="text-[11px] text-gray-500 dark:text-gray-400">Avg Latency</div>
                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $avgLatency }} <span class="text-xs font-normal text-gray-400">ms</span></div>
            </div>
            <div class="p-3 bg-gray-50 dark:bg-gray-800/60 rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="text-[11px] text-gray-500 dark:text-gray-400">Failover Switches</div>
                <div class="text-lg font-bold text-amber-500">{{ $failoverCount }}</div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 text-gray-400">
                        <th class="pb-2">Feature</th>
                        <th class="pb-2">Provider / Model</th>
                        <th class="pb-2">Tokens</th>
                        <th class="pb-2">Latency</th>
                        <th class="pb-2 text-right">Cost</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($recentLogs as $log)
                        <tr>
                            <td class="py-2 font-medium text-gray-800 dark:text-gray-200">{{ ucfirst(str_replace('_', ' ', $log->feature)) }}</td>
                            <td class="py-2 font-mono text-[11px] text-gray-500">{{ $log->provider }} / {{ $log->model }}</td>
                            <td class="py-2 text-gray-500">{{ $log->input_tokens + $log->output_tokens }}</td>
                            <td class="py-2 text-gray-500">{{ $log->latency_ms }}ms</td>
                            <td class="py-2 text-right font-mono text-teal-600 dark:text-teal-400">${{ number_format($log->estimated_cost_usd, 5) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-400">No AI requests logged yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
