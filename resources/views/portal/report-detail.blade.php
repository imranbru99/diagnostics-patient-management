@extends('layouts.public')

@section('title', "Diagnostic Report - {$report->report_number} | Apex Diagnostics")

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="reportDetailChat({{ $report->id }})">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('portal.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-brand-600">&larr; Back to Dashboard</a>
                <span class="text-slate-300">&bull;</span>
                <span class="font-mono text-xs font-bold text-teal-600 dark:text-teal-400">{{ $report->report_number }}</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">
                {{ $report->orderItem->test->name ?? 'Diagnostic Panel' }}
            </h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('report.pdf', $report->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-xl bg-slate-900 dark:bg-slate-800 text-white hover:bg-slate-800 transition-colors">
                <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download Official PDF
            </a>
            <a href="{{ route('public.verify-report', $report->verification_hash) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Verify Authenticity
            </a>
        </div>
    </div>

    <!-- AI Plain-Language Patient Summary Banner -->
    @if(!empty($report->ai_summary))
        <div class="mb-8 p-6 bg-gradient-to-r from-teal-50 via-emerald-50 to-teal-50/50 dark:from-slate-900 dark:via-slate-850 dark:to-slate-900 rounded-3xl border border-teal-200 dark:border-teal-900 shadow-md">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="p-2 rounded-xl bg-teal-600 text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </span>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">AI Plain-Language Patient Summary</h2>
                        <p class="text-xs text-slate-500">Autonomous medical analysis powered by <code>imrandevbd/laravel-ai-hub</code></p>
                    </div>
                </div>
                <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full bg-teal-100 dark:bg-teal-950 text-teal-800 dark:text-teal-300">
                    Patient Friendly
                </span>
            </div>

            <div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed prose prose-teal dark:prose-invert max-w-none">
                {!! nl2br(e($report->ai_summary)) !!}
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Clinical Results Table (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="p-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <!-- Patient / Specimen Meta -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 mb-6 text-xs">
                    <div>
                        <span class="text-slate-400 block">Patient Name:</span>
                        <strong class="text-slate-900 dark:text-white">{{ $report->patient->name }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Age &amp; Gender:</span>
                        <strong class="text-slate-900 dark:text-white">{{ $report->patient->formatted_age }}, {{ ucfirst($report->patient->gender) }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Sample Barcode:</span>
                        <strong class="font-mono text-teal-600">{{ $report->orderItem->sample->barcode ?? 'SMP-BARCODE' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Verified Date:</span>
                        <strong class="text-slate-900 dark:text-white">{{ $report->verified_at ? $report->verified_at->format('d-M-Y') : 'Pending' }}</strong>
                    </div>
                </div>

                <!-- Parameters Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700 text-slate-400">
                                <th class="pb-3">Investigation</th>
                                <th class="pb-3">Observed Value</th>
                                <th class="pb-3">Unit</th>
                                <th class="pb-3">Biological Ref. Interval</th>
                                <th class="pb-3 text-right">Interpretation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($report->values as $val)
                                @php
                                    $isCrit = in_array($val->flag, ['critical_low', 'critical_high']);
                                    $isAbnormal = in_array($val->flag, ['low', 'high']);
                                @endphp
                                <tr class="{{ $isCrit ? 'bg-rose-50/50 dark:bg-rose-950/20' : ($isAbnormal ? 'bg-amber-50/40 dark:bg-amber-950/10' : '') }}">
                                    <td class="py-3 font-semibold text-slate-900 dark:text-white">
                                        {{ $val->parameter->name }}
                                    </td>
                                    <td class="py-3 font-mono font-bold {{ $isCrit ? 'text-rose-600 dark:text-rose-400 text-sm' : ($isAbnormal ? 'text-amber-600 dark:text-amber-400' : 'text-slate-800 dark:text-slate-200') }}">
                                        {{ $val->value_numeric ?? $val->value_text }}
                                    </td>
                                    <td class="py-3 text-slate-500 font-mono">{{ $val->unit }}</td>
                                    <td class="py-3 text-slate-500">{{ $val->reference_range_snapshot }}</td>
                                    <td class="py-3 text-right">
                                        @if($isCrit)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-300">
                                                CRITICAL {{ strtoupper(str_replace('critical_', '', $val->flag)) }}
                                            </span>
                                        @elseif($isAbnormal)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300">
                                                {{ strtoupper($val->flag) }}
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300">
                                                NORMAL
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(!empty($report->clinical_impression))
                    <div class="mt-6 p-4 rounded-xl bg-slate-50 dark:bg-slate-850 border-l-4 border-slate-400 text-xs">
                        <strong class="text-slate-700 dark:text-slate-300 block mb-1">Pathologist Impression &amp; Remarks:</strong>
                        <div class="text-slate-600 dark:text-slate-400">{{ $report->clinical_impression }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Interactive "Ask About Your Report" AI Chat Widget -->
        <div class="space-y-6">
            <div class="p-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col h-[560px]">
                <div class="flex items-center gap-2 pb-3 mb-3 border-b border-slate-100 dark:border-slate-800">
                    <span class="p-1.5 rounded-lg bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Ask About Your Report</h3>
                        <p class="text-[10px] text-slate-400">Scoped RAG assistant for this report only</p>
                    </div>
                </div>

                <!-- Chat History Messages -->
                <div class="flex-grow overflow-y-auto space-y-3 pr-1 text-xs" id="chat-messages">
                    <template x-for="(msg, index) in messages" :key="index">
                        <div :class="msg.role === 'user' ? 'text-right' : 'text-left'">
                            <div
                                :class="msg.role === 'user' ? 'inline-block p-3 rounded-2xl bg-brand-600 text-white text-left max-w-[85%]' : 'inline-block p-3 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-left max-w-[85%]'"
                                x-html="msg.text"
                            ></div>
                            <div class="text-[9px] text-slate-400 mt-0.5" x-text="msg.time"></div>
                        </div>
                    </template>
                    <div x-show="isReplying" class="text-left" x-cloak>
                        <div class="inline-block p-3 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 text-xs italic">
                            Analyzing your report values...
                        </div>
                    </div>
                </div>

                <!-- Quick Suggested Prompts -->
                <div class="py-2 flex flex-wrap gap-1">
                    <button type="button" @click="askQuestion('Are all my values normal?')" class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-teal-50 hover:text-teal-700">
                        Are my values normal?
                    </button>
                    <button type="button" @click="askQuestion('What do abnormal flags mean?')" class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-teal-50 hover:text-teal-700">
                        Explain abnormal flags
                    </button>
                </div>

                <!-- Chat Input -->
                <form @submit.prevent="sendMessage" class="mt-2 pt-2 border-t border-slate-100 dark:border-slate-800 flex gap-2">
                    <input
                        type="text"
                        x-model="inputText"
                        placeholder="Type question about this report..."
                        class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-slate-900 dark:text-white focus:border-brand-500 focus:ring-brand-500"
                    >
                    <button type="submit" :disabled="isReplying" class="p-2 rounded-xl bg-brand-600 text-white shrink-0 hover:bg-brand-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function reportDetailChat(reportId) {
        return {
            reportId: reportId,
            inputText: '',
            isReplying: false,
            messages: [
                {
                    role: 'assistant',
                    text: 'Hello! I am your AI lab assistant. You can ask me any questions about the tests and parameters shown on this report.',
                    time: '{{ now()->format("h:i A") }}'
                }
            ],
            askQuestion(q) {
                this.inputText = q;
                this.sendMessage();
            },
            sendMessage() {
                if (!this.inputText.trim()) return;
                const userQuestion = this.inputText;
                this.messages.push({
                    role: 'user',
                    text: userQuestion,
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                });
                this.inputText = '';
                this.isReplying = true;

                fetch('{{ route("portal.chat") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        report_id: this.reportId,
                        question: userQuestion
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.messages.push({
                        role: 'assistant',
                        text: data.reply,
                        time: data.time || new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });
                    this.isReplying = false;
                    this.$nextTick(() => {
                        const box = document.getElementById('chat-messages');
                        box.scrollTop = box.scrollHeight;
                    });
                })
                .catch(() => {
                    this.messages.push({
                        role: 'assistant',
                        text: 'Unable to communicate with AI Hub service right now. Please consult your physician directly.',
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });
                    this.isReplying = false;
                });
            }
        }
    }
</script>
@endsection
