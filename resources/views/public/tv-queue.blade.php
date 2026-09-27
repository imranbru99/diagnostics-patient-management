<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="10">
    <title>Waiting Lounge TV Queue Display | Apex Diagnostics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&family=JetBrains+Mono:wght@700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="h-full flex flex-col p-6 overflow-hidden">

    <!-- Header -->
    <header class="flex items-center justify-between pb-4 border-b border-slate-800">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-xl bg-teal-500 text-slate-950 font-black">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </span>
            <div>
                <h1 class="text-2xl font-black tracking-tight">Apex Diagnostics &bull; <span class="text-teal-400">{{ $branch->name ?? 'Main Branch' }}</span></h1>
                <div class="text-xs text-slate-400">Patient Queue Token Calling System &bull; Live TV Display Mode</div>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="text-right">
                <div class="text-xl font-mono font-bold text-teal-400" id="live-clock">--:--:--</div>
                <div class="text-xs text-slate-400">{{ date('l, d F Y') }}</div>
            </div>
            <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-xs text-slate-300 hover:text-white">
                Exit TV Mode
            </a>
        </div>
    </header>

    <!-- Main TV Body -->
    <main class="flex-grow grid grid-cols-12 gap-6 pt-6">
        <!-- Now Serving (Left 7 Cols) -->
        <section class="col-span-7 flex flex-col">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
                <h2 class="text-lg font-bold uppercase tracking-wider text-emerald-400">Now Serving &bull; Please Proceed to Counter</h2>
            </div>

            <div class="grid grid-cols-2 gap-4 flex-grow">
                @forelse($serving as $index => $apt)
                    <div class="p-6 rounded-3xl bg-slate-900 border-2 border-emerald-500/80 shadow-2xl shadow-emerald-500/10 flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300">
                                Counter {{ $index + 1 }}
                            </span>
                            <span class="text-xs font-semibold text-slate-400">Phlebotomy Lab</span>
                        </div>
                        <div class="my-4 text-center">
                            <div class="text-6xl font-black mono text-emerald-400 tracking-tight">{{ $apt->token_number }}</div>
                            <div class="text-sm font-semibold text-slate-200 mt-2 truncate">{{ $apt->patient->name }}</div>
                            <div class="text-xs text-teal-400 truncate">{{ $apt->test->name ?? $apt->testPackage->name ?? 'Diagnostic Test' }}</div>
                        </div>
                        <div class="text-[11px] text-center text-slate-500">Sample draw in progress</div>
                    </div>
                @empty
                    <div class="col-span-2 flex flex-col items-center justify-center p-12 bg-slate-900/50 rounded-3xl border border-slate-800 text-center">
                        <div class="text-slate-500 text-base">Next token call shortly...</div>
                        <div class="text-xs text-slate-600 mt-1">Please have your token slip ready.</div>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Waiting / Up Next (Right 5 Cols) -->
        <section class="col-span-5 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold uppercase tracking-wider text-teal-400">Up Next &bull; Waiting Lounge</h2>
                <span class="text-xs font-mono text-slate-400">{{ count($waiting) }} in Queue</span>
            </div>

            <div class="space-y-2.5 flex-grow overflow-hidden">
                @forelse($waiting as $w)
                    <div class="p-3.5 bg-slate-900/90 rounded-2xl border border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-xl font-black mono text-teal-400">{{ $w->token_number }}</span>
                            <div>
                                <div class="text-sm font-semibold text-slate-200 truncate max-w-[200px]">{{ $w->patient->name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $w->test->name ?? 'Test' }}</div>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $w->queue_status === 'called' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/40 animate-pulse' : 'bg-slate-800 text-slate-300' }}">
                            {{ $w->queue_status === 'called' ? 'CALLED' : 'WAITING' }}
                        </span>
                    </div>
                @empty
                    <div class="p-6 bg-slate-900/40 rounded-2xl border border-slate-800 text-center text-slate-500 text-xs">
                        All waiting patients have been served!
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    <!-- Footer Marquee -->
    <footer class="mt-6 pt-3 border-t border-slate-850 flex items-center justify-between text-xs text-slate-400">
        <div>&bull; Please keep your token receipt ready when your number is displayed on the screen.</div>
        <div>Apex Diagnostics Clinical Automated Dispatch</div>
    </footer>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('live-clock').innerText = now.toLocaleTimeString();
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
