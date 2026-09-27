<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-white select-none overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="15">
    <title>Waiting Lounge TV Queue Display | Apex Diagnostics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#14b8a6',
                            600: '#0d9488',
                        },
                        clinical: {
                            500: '#10b981',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    boxShadow: {
                        'neon-emerald': '0 0 35px -5px rgba(16, 185, 129, 0.45)',
                        'neon-teal': '0 0 35px -5px rgba(20, 184, 166, 0.45)',
                    }
                }
            }
        }
    </script>
    <style>
        .mono { font-family: 'JetBrains Mono', monospace; }
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.85; transform: scale(1.01); }
        }
        .animate-pulse-slow {
            animation: pulse-slow 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="h-full flex flex-col p-6 bg-radial from-slate-900 via-slate-950 to-black">

    <!-- Broadcast Header -->
    <header class="flex items-center justify-between pb-5 border-b border-slate-800/80">
        <div class="flex items-center gap-4">
            <span class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-600 via-teal-500 to-emerald-400 text-slate-950 flex items-center justify-center font-black shadow-lg shadow-teal-500/20">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </span>
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-2xl font-black tracking-tight text-white">
                        Apex Diagnostics <span class="text-teal-400">&bull; {{ $branch->name ?? 'Main Branch' }}</span>
                    </h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                        LIVE QUEUE
                    </span>
                </div>
                <div class="text-xs text-slate-400 mt-0.5">Automated Phlebotomy Dispatch &bull; Lounge TV Display Mode</div>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <!-- Melodic Chime Sound Button -->
            <button
                type="button"
                onclick="playHospitalChime()"
                class="px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700/80 hover:border-teal-500 text-xs text-slate-300 hover:text-white flex items-center gap-2 transition-all"
                title="Play Notification Chime"
            >
                <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                <span>Audio Chime</span>
            </button>

            <!-- Fullscreen Button -->
            <button
                type="button"
                onclick="toggleFullscreen()"
                class="px-3 py-2 rounded-xl bg-slate-900 border border-slate-700/80 hover:border-slate-500 text-xs text-slate-300 hover:text-white transition-colors"
                title="Fullscreen Mode"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
            </button>

            <!-- Live Digital Clock -->
            <div class="text-right pl-4 border-l border-slate-800">
                <div class="text-2xl font-black mono text-teal-400 tracking-tight" id="live-clock">--:--:--</div>
                <div class="text-[11px] text-slate-400 uppercase font-semibold tracking-wider">{{ date('l, d M Y') }}</div>
            </div>

            <!-- Exit Link -->
            <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-lg bg-slate-900/60 border border-slate-800 text-xs text-slate-400 hover:text-white transition-colors">
                Exit
            </a>
        </div>
    </header>

    <!-- Main TV Body -->
    <main class="flex-grow grid grid-cols-12 gap-8 pt-6 min-h-0">

        <!-- Now Serving (Left 7 Cols) -->
        <section class="col-span-7 flex flex-col min-h-0">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 animate-ping"></span>
                    <h2 class="text-lg font-black uppercase tracking-wider text-emerald-400">
                        NOW SERVING &bull; PLEASE PROCEED TO BOOTH
                    </h2>
                </div>
                <span class="text-xs font-mono text-slate-400 font-bold">
                    {{ count($serving) }} Active Stations
                </span>
            </div>

            <div class="grid grid-cols-2 gap-5 flex-grow">
                @forelse($serving as $index => $apt)
                    <div class="p-8 rounded-3xl bg-slate-900/90 border-2 border-emerald-500/80 shadow-neon-emerald flex flex-col justify-between animate-pulse-slow">
                        <div class="flex items-center justify-between pb-3 border-b border-emerald-950">
                            <span class="px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-500 text-slate-950 shadow-md">
                                Phlebotomy Booth {{ $index + 1 }}
                            </span>
                            <span class="text-xs font-mono text-emerald-400 font-bold">IN SERVICE</span>
                        </div>

                        <div class="my-auto py-6 text-center">
                            <div class="text-7xl font-black mono text-emerald-400 tracking-tight drop-shadow-md">
                                {{ $apt->token_number }}
                            </div>
                            <div class="text-lg font-bold text-white mt-3 truncate">{{ $apt->patient->name }}</div>
                            <div class="text-xs text-teal-400 font-medium mt-1 truncate">
                                {{ $apt->test->name ?? $apt->testPackage->name ?? 'Diagnostic Test' }}
                            </div>
                        </div>

                        <div class="pt-3 border-t border-emerald-950 flex items-center justify-between text-xs text-slate-400">
                            <span>Specimen Draw in Progress</span>
                            <span class="text-emerald-400 font-mono font-bold">&check; Barcode Printed</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 flex flex-col items-center justify-center p-12 bg-slate-900/40 rounded-3xl border border-slate-800 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-slate-800 text-slate-500 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="text-slate-300 text-lg font-bold">Next Token Calling Shortly...</div>
                        <div class="text-xs text-slate-500 mt-1 max-w-sm">Please have your digital token pass or paper receipt ready for scanning at the draw station.</div>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Waiting Lounge / Up Next (Right 5 Cols) -->
        <section class="col-span-5 flex flex-col min-h-0">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-teal-400"></span>
                    <h2 class="text-lg font-black uppercase tracking-wider text-teal-400">
                        UP NEXT &bull; WAITING QUEUE
                    </h2>
                </div>
                <span class="text-xs font-mono text-teal-300 font-bold px-2.5 py-0.5 rounded-full bg-teal-950 border border-teal-800">
                    {{ count($waiting) }} in Queue
                </span>
            </div>

            <div class="space-y-3 flex-grow overflow-y-auto pr-1">
                @forelse($waiting as $index => $w)
                    <div class="p-4 bg-slate-900/90 rounded-2xl border {{ $w->queue_status === 'called' ? 'border-amber-500/80 bg-amber-950/20 shadow-lg' : 'border-slate-800' }} flex items-center justify-between transition-all">
                        <div class="flex items-center gap-3.5">
                            <span class="text-xs font-mono text-slate-500 w-5">#{{ $index + 1 }}</span>
                            <span class="text-2xl font-black mono text-teal-300">{{ $w->token_number }}</span>
                            <div class="truncate max-w-[210px]">
                                <div class="text-sm font-bold text-white truncate">{{ $w->patient->name }}</div>
                                <div class="text-[11px] text-slate-400 truncate">{{ $w->test->name ?? $w->testPackage->name ?? 'Diagnostic Test' }}</div>
                            </div>
                        </div>

                        <span class="px-3 py-1 text-xs font-black uppercase rounded-lg {{ $w->queue_status === 'called' ? 'bg-amber-500 text-slate-950 animate-pulse' : 'bg-slate-800 text-slate-300' }}">
                            {{ $w->queue_status === 'called' ? 'CALLED' : 'WAITING' }}
                        </span>
                    </div>
                @empty
                    <div class="p-8 bg-slate-900/40 rounded-2xl border border-slate-800 text-center text-slate-500 text-xs">
                        All scheduled patients have been served.
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    <!-- Footer Marquee Ticker -->
    <footer class="mt-6 pt-3 border-t border-slate-800 flex items-center justify-between text-xs text-slate-400">
        <div class="flex items-center gap-3 overflow-hidden">
            <span class="px-2 py-0.5 rounded bg-teal-950 text-teal-400 font-bold uppercase text-[10px] shrink-0 border border-teal-800">
                NOTICE
            </span>
            <div class="truncate text-slate-300">
                Please proceed to your assigned booth immediately when called &bull; Free Waiting Lounge Wi-Fi: <strong class="text-white font-mono">Apex-Guest (No Password)</strong> &bull; Official AI reports published within 4 hours via WhatsApp &amp; Patient Portal &bull; Fasting blood draw open until 11:30 AM
            </div>
        </div>
        <div class="shrink-0 pl-4 font-mono text-[11px] text-teal-400">
            Apex Diagnostics Automated Dispatch System
        </div>
    </footer>

    <!-- Interactive Audio Chime & Clock Scripts -->
    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('live-clock').innerText = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }
        setInterval(updateClock, 1000);
        updateClock();

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => alert(`Fullscreen error: ${err.message}`));
            } else {
                document.exitFullscreen();
            }
        }

        // Web Audio API Hospital Notification Chime (Two-tone crystal chime)
        function playHospitalChime() {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                const ctx = new AudioContext();

                // Tone 1: High crisp bell (880Hz - A5)
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(880, ctx.currentTime);
                gain1.gain.setValueAtTime(0.3, ctx.currentTime);
                gain1.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.8);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start();
                osc1.stop(ctx.currentTime + 0.8);

                // Tone 2: Harmonious resolve (1174Hz - D6) after 250ms
                setTimeout(() => {
                    const osc2 = ctx.createOscillator();
                    const gain2 = ctx.createGain();
                    osc2.type = 'sine';
                    osc2.frequency.setValueAtTime(1174.66, ctx.currentTime);
                    gain2.gain.setValueAtTime(0.35, ctx.currentTime);
                    gain2.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 1.2);
                    osc2.connect(gain2);
                    gain2.connect(ctx.destination);
                    osc2.start();
                    osc2.stop(ctx.currentTime + 1.2);
                }, 250);
            } catch (e) {
                console.error("Audio chime error:", e);
            }
        }
    </script>
</body>
</html>
