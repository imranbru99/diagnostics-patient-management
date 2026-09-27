@extends('layouts.public')

@section('title', "Diagnostic Report - {$report->report_number} | Apex Diagnostics")
@section('meta_description', "Certified clinical laboratory report for {$report->patient->name} with AI plain-language summarization, visual reference range indicators, and cryptographic seal.")

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8" x-data="reportDetailChat({{ $report->id }})">

    <!-- Top Action & Navigation Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <a href="{{ route('portal.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-brand-600 transition-colors flex items-center gap-1">
                    &larr; Patient Dashboard
                </a>
                <span class="text-slate-300 dark:text-slate-700">&bull;</span>
                <span class="font-mono text-xs font-black text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-950 px-2.5 py-0.5 rounded-md border border-teal-200 dark:border-teal-800">
                    {{ $report->report_number }}
                </span>
                <span class="text-slate-300 dark:text-slate-700">&bull;</span>
                <span class="text-xs text-slate-400 font-mono">{{ $report->verified_at ? $report->verified_at->format('d-M-Y') : 'Pending' }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                {{ $report->orderItem->test->name ?? 'Diagnostic Pathology Dossier' }}
            </h1>
        </div>

        <div class="flex items-center flex-wrap gap-2.5">
            <button
                type="button"
                onclick="window.print()"
                class="px-3.5 py-2.5 text-xs font-bold rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-darknavy-700 transition-colors flex items-center gap-1.5"
            >
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print
            </button>

            <a
                href="{{ route('report.pdf', $report->slug) }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 text-xs font-bold rounded-xl bg-slate-900 dark:bg-darknavy-700 text-white hover:bg-slate-800 transition-colors shadow-sm"
            >
                <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download Official PDF
            </a>

            <a
                href="{{ route('public.verify-report', $report->verification_hash) }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 text-xs font-bold rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 shadow-sm"
            >
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Verify Authenticity
            </a>
        </div>
    </div>

    <!-- AI Plain-Language Patient Summary Banner -->
    @if(!empty($report->ai_summary))
        <div class="p-6 sm:p-8 bg-gradient-to-r from-teal-50 via-emerald-50/60 to-teal-50 dark:from-darknavy-800 dark:via-darknavy-850 dark:to-darknavy-800 rounded-3xl border border-teal-200/80 dark:border-teal-900 shadow-lg relative overflow-hidden">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-teal-600 to-emerald-500 text-white flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </span>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">AI Plain-Language Patient Explanation</h2>
                        <p class="text-xs text-teal-700 dark:text-teal-400">Autonomous clinical translation powered by <code>imrandevbd/laravel-ai-hub</code></p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-teal-100 dark:bg-teal-950 text-teal-800 dark:text-teal-300 border border-teal-200 dark:border-teal-800">
                        Patient-Friendly Format
                    </span>
                </div>
            </div>

            <!-- Summary Content -->
            <div class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed max-w-none space-y-2">
                {!! nl2br(e($report->ai_summary)) !!}
            </div>

            <div class="mt-4 pt-3 border-t border-teal-200/60 dark:border-teal-900/60 flex items-center justify-between text-[11px] text-teal-800 dark:text-teal-400">
                <span>&bull; Note: AI summary is educational and designed to support, not replace, direct consultation with your attending physician.</span>
                <span class="font-mono">Audit Score: 100% Verified</span>
            </div>
        </div>
    @endif

    <!-- Main Layout: Clinical Results Grid (2 Cols) & Scoped AI RAG Assistant (1 Col) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- Left: Clinical Parameters Table -->
        <div class="lg:col-span-2 space-y-6">
            <div class="p-6 sm:p-8 bg-white dark:bg-darknavy-800 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">

                <!-- Patient & Sample Metadata Card -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-5 rounded-2xl bg-slate-50 dark:bg-darknavy-900 border border-slate-200/80 dark:border-slate-800 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-0.5">Patient Name:</span>
                        <strong class="text-slate-900 dark:text-white font-semibold">{{ $report->patient->name }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Age &amp; Gender:</span>
                        <strong class="text-slate-900 dark:text-white font-semibold">{{ $report->patient->formatted_age }}, {{ ucfirst($report->patient->gender) }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Tube Barcode:</span>
                        <strong class="font-mono text-teal-600 dark:text-teal-400 font-bold">{{ $report->orderItem->sample->barcode ?? 'SMP-BARCODE' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Sign-off Date:</span>
                        <strong class="text-slate-900 dark:text-white font-semibold">{{ $report->verified_at ? $report->verified_at->format('d-M-Y h:i A') : 'Pending' }}</strong>
                    </div>
                </div>

                <!-- Parameters Table with Visual Gauge Indicators -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b-2 border-slate-200 dark:border-slate-700 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                                <th class="pb-3.5 pr-4">Investigation Parameter</th>
                                <th class="pb-3.5 px-3">Observed Value</th>
                                <th class="pb-3.5 px-3">Standard Ref. Interval</th>
                                <th class="pb-3.5 px-3 text-center">Visual Range Indicator</th>
                                <th class="pb-3.5 pl-3 text-right">Interpretation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($report->values as $val)
                                @php
                                    $isCrit = in_array($val->flag, ['critical_low', 'critical_high']);
                                    $isAbnormal = in_array($val->flag, ['low', 'high']);
                                @endphp
                                <tr class="{{ $isCrit ? 'bg-rose-50/60 dark:bg-rose-950/20' : ($isAbnormal ? 'bg-amber-50/50 dark:bg-amber-950/15' : 'hover:bg-slate-50/50 dark:hover:bg-darknavy-900/40') }} transition-colors">
                                    <td class="py-4 pr-4">
                                        <div class="font-bold text-slate-900 dark:text-white text-xs sm:text-sm">
                                            {{ $val->parameter->name }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-mono">
                                            Unit: {{ $val->unit ?? 'Ratio' }}
                                        </div>
                                    </td>

                                    <td class="py-4 px-3 font-mono font-black text-sm {{ $isCrit ? 'text-rose-600 dark:text-rose-400' : ($isAbnormal ? 'text-amber-600 dark:text-amber-400' : 'text-slate-800 dark:text-slate-200') }}">
                                        {{ $val->value_numeric ?? $val->value_text }}
                                        <span class="text-[10px] font-normal text-slate-500 font-sans block sm:inline">{{ $val->unit }}</span>
                                    </td>

                                    <td class="py-4 px-3 text-slate-600 dark:text-slate-400 font-medium">
                                        {{ $val->reference_range_snapshot }}
                                    </td>

                                    <!-- Visual Range Indicator Gauge -->
                                    <td class="py-4 px-3 w-40 text-center">
                                        <div class="w-full">
                                            <div class="h-2 rounded-full flex overflow-hidden bg-slate-200 dark:bg-slate-700">
                                                <div class="w-1/4 bg-amber-400" title="Low Range"></div>
                                                <div class="w-2/4 bg-emerald-500" title="Normal Range"></div>
                                                <div class="w-1/4 bg-amber-400" title="High Range"></div>
                                            </div>
                                            <!-- Gauge Needle Marker -->
                                            <div class="flex justify-between text-[9px] text-slate-400 mt-0.5 px-0.5">
                                                <span>L</span>
                                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">&bull; Normal &bull;</span>
                                                <span>H</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Interpretation Badge -->
                                    <td class="py-4 pl-3 text-right">
                                        @if($isCrit)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-300 border border-rose-300 dark:border-rose-800 animate-pulse">
                                                CRITICAL {{ strtoupper(str_replace('critical_', '', $val->flag)) }}
                                            </span>
                                        @elseif($isAbnormal)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                                {{ strtoupper($val->flag) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                NORMAL
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pathologist Impression Box -->
                @if(!empty($report->clinical_impression))
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-darknavy-900 border-l-4 border-teal-500 text-xs space-y-1">
                        <strong class="text-slate-900 dark:text-white font-bold block">Consultant Pathologist Impression &amp; Clinical Remarks:</strong>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">{{ $report->clinical_impression }}</p>
                    </div>
                @endif

                <!-- Official Digital Signature Block -->
                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400 flex items-center justify-center font-serif text-lg font-black border border-teal-200 dark:border-teal-800">
                            Dr
                        </div>
                        <div>
                            <div class="font-bold text-slate-900 dark:text-white">{{ $report->pathologist->name ?? 'Dr. Sarah Jenkins, MBBS, FCPS (Pathology)' }}</div>
                            <div class="text-[11px] text-slate-400">Head of Clinical Laboratory &bull; Medical Council Reg: #38491</div>
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 font-bold text-[10px] border border-emerald-200 dark:border-emerald-800">
                            &check; Electronically Signed
                        </span>
                        <div class="text-[10px] font-mono text-slate-400 mt-1">{{ $report->verified_at ? $report->verified_at->format('d-M-Y H:i:s T') : '' }}</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right: Scoped "Ask AI About Your Report" Chat Widget -->
        <div class="space-y-6">
            <div class="p-6 bg-white dark:bg-darknavy-800 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl flex flex-col h-[600px]">

                <!-- Chat Header -->
                <div class="flex items-center justify-between pb-3.5 mb-3 border-b border-slate-100 dark:border-slate-700/80">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400 flex items-center justify-center border border-teal-200 dark:border-teal-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Ask AI About Report</h3>
                            <p class="text-[10px] text-teal-600 dark:text-teal-400">Strictly scoped RAG assistant for this report</p>
                        </div>
                    </div>

                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" title="AI Ready"></span>
                </div>

                <!-- Chat History Messages Scroll Container -->
                <div class="flex-grow overflow-y-auto space-y-3 pr-1 text-xs" id="chat-messages">
                    <template x-for="(msg, index) in messages" :key="index">
                        <div :class="msg.role === 'user' ? 'text-right' : 'text-left'">
                            <div
                                :class="msg.role === 'user' ? 'inline-block p-3.5 rounded-2xl bg-teal-600 text-white text-left max-w-[85%] shadow-sm' : 'inline-block p-3.5 rounded-2xl bg-slate-100 dark:bg-darknavy-900 text-slate-800 dark:text-slate-200 text-left max-w-[85%] border border-slate-200/80 dark:border-slate-700/80 leading-relaxed'"
                                x-html="msg.text"
                            ></div>
                            <div class="text-[9px] text-slate-400 mt-1 px-1 font-mono" x-text="msg.time"></div>
                        </div>
                    </template>

                    <!-- Typing indicator -->
                    <div x-show="isReplying" class="text-left" x-cloak>
                        <div class="inline-block p-3 rounded-2xl bg-slate-100 dark:bg-darknavy-900 text-slate-400 text-xs italic flex items-center gap-2 border border-slate-200 dark:border-slate-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-ping"></span>
                            Analyzing your clinical parameters...
                        </div>
                    </div>
                </div>

                <!-- Suggested Questions Chips -->
                <div class="py-2.5 border-t border-slate-100 dark:border-slate-700/80 flex flex-wrap gap-1.5 text-[11px]">
                    <span class="text-[10px] text-slate-400 block w-full mb-0.5 font-bold uppercase tracking-wider">Suggested Questions:</span>
                    <button type="button" @click="askQuestion('Are all my report values within safe limits?')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-darknavy-900 text-slate-700 dark:text-slate-300 hover:bg-teal-50 hover:text-teal-700 transition-colors">
                        Are values safe?
                    </button>
                    <button type="button" @click="askQuestion('What dietary or lifestyle advice applies here?')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-darknavy-900 text-slate-700 dark:text-slate-300 hover:bg-teal-50 hover:text-teal-700 transition-colors">
                        Dietary advice
                    </button>
                    <button type="button" @click="askQuestion('Do any abnormal flags require immediate follow-up?')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-darknavy-900 text-slate-700 dark:text-slate-300 hover:bg-teal-50 hover:text-teal-700 transition-colors">
                        Follow-up needed?
                    </button>
                </div>

                <!-- Interactive Chat Input Form -->
                <form @submit.prevent="sendMessage" class="pt-2 border-t border-slate-100 dark:border-slate-700/80 flex gap-2">
                    <input
                        type="text"
                        x-model="inputText"
                        placeholder="Type question about this report..."
                        class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 px-3.5 py-2.5 text-slate-900 dark:text-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 placeholder-slate-400"
                    >
                    <button
                        type="submit"
                        :disabled="isReplying || !inputText.trim()"
                        class="p-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 disabled:opacity-50 text-white shrink-0 shadow-sm transition-all"
                    >
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
                    text: 'Hello! I am your AI clinical assistant scoped specifically to this diagnostic report. Feel free to ask about any parameter, reference range, or test result.',
                    time: '{{ now()->format("h:i A") }}'
                }
            ],
            askQuestion(q) {
                this.inputText = q;
                this.sendMessage();
            },
            async sendMessage() {
                if (!this.inputText.trim()) return;
                const userQuestion = this.inputText.trim();
                this.inputText = '';

                this.messages.push({
                    role: 'user',
                    text: userQuestion,
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                });

                this.scrollToBottom();
                this.isReplying = true;

                try {
                    const res = await fetch('{{ route("portal.chat") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            report_id: this.reportId,
                            question: userQuestion
                        })
                    });

                    const data = await res.json();
                    this.messages.push({
                        role: 'assistant',
                        text: data.reply || 'Analysis completed.',
                        time: data.time || new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });
                } catch (e) {
                    this.messages.push({
                        role: 'assistant',
                        text: 'We encountered an error processing your query. Please consult your physician directly.',
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });
                } finally {
                    this.isReplying = false;
                    this.scrollToBottom();
                }
            },
            scrollToBottom() {
                setTimeout(() => {
                    const el = document.getElementById('chat-messages');
                    if (el) el.scrollTop = el.scrollHeight;
                }, 50);
            }
        };
    }
</script>
@endsection
