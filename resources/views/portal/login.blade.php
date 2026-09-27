@extends('layouts.public')

@section('title', 'Patient Health Portal Sign In | Apex Diagnostics')
@section('meta_description', 'Secure online patient portal to access diagnostic pathology reports, AI plain-language explanations, and family medical histories.')

@section('content')
<div class="max-w-md mx-auto px-4 py-16" x-data="{ identifier: '', pin: '' }">
    <div class="p-8 sm:p-10 bg-white dark:bg-darknavy-800 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl relative overflow-hidden">
        <!-- Ambient Glow -->
        <div class="absolute -top-16 -right-16 w-48 h-48 bg-brand-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="text-center mb-8 relative z-10">
            <span class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 via-teal-500 to-emerald-400 text-white inline-flex items-center justify-center shadow-lg shadow-brand-500/25 mb-3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </span>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Patient Health Portal</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Access your encrypted laboratory reports, AI summaries, and family profiles.
            </p>
        </div>

        @if($errors->any())
            <div class="p-3.5 mb-6 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-xs text-rose-700 dark:text-rose-300 flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('portal.login.submit') }}" method="POST" class="space-y-4 relative z-10">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                    Medical Record Number (MRN) or Mobile Phone *
                </label>
                <div class="relative">
                    <input
                        type="text"
                        name="identifier"
                        x-model="identifier"
                        required
                        placeholder="e.g. MRN-2026-00001 or +880 1712-345678"
                        class="w-full text-xs font-mono rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 px-4 py-3 text-slate-900 dark:text-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 placeholder-slate-400"
                    >
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                        Portal Security PIN (Optional)
                    </label>
                    <span class="text-[10px] text-teal-600 dark:text-teal-400">Printed on receipt</span>
                </div>
                <input
                    type="password"
                    name="pin"
                    x-model="pin"
                    placeholder="4-digit PIN (or leave blank for demo)"
                    class="w-full text-xs font-mono rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 px-4 py-3 text-slate-900 dark:text-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 placeholder-slate-400"
                >
            </div>

            <button
                type="submit"
                class="w-full py-3.5 text-xs font-bold rounded-xl bg-gradient-to-r from-brand-600 to-teal-600 hover:from-brand-700 hover:to-teal-700 text-white shadow-md shadow-brand-500/25 hover:shadow-glow-teal transition-all flex items-center justify-center gap-2 transform hover:-translate-y-0.5"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                Sign In to Health Portal
            </button>
        </form>

        <!-- Quick 1-Click Demo Profiles for Rapid Testing -->
        <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800 text-xs">
            <span class="text-slate-400 block mb-2 font-semibold text-[11px] text-center uppercase tracking-wider">
                Quick 1-Click Demo Logins:
            </span>
            <div class="grid grid-cols-2 gap-2">
                <button
                    type="button"
                    @click="identifier = 'MRN-2026-00001'; pin = '1234'"
                    class="p-2.5 rounded-xl bg-slate-50 dark:bg-darknavy-900 border border-slate-200 dark:border-slate-700 text-left hover:border-brand-500 transition-colors"
                >
                    <div class="font-bold text-slate-800 dark:text-slate-200 truncate">Farhan Akhtar</div>
                    <div class="text-[10px] text-teal-600 font-mono">MRN-2026-00001</div>
                </button>

                <button
                    type="button"
                    @click="identifier = 'MRN-2026-00002'; pin = '1234'"
                    class="p-2.5 rounded-xl bg-slate-50 dark:bg-darknavy-900 border border-slate-200 dark:border-slate-700 text-left hover:border-brand-500 transition-colors"
                >
                    <div class="font-bold text-slate-800 dark:text-slate-200 truncate">Nusrat Jahan</div>
                    <div class="text-[10px] text-teal-600 font-mono">MRN-2026-00002</div>
                </button>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 text-center text-[11px] text-slate-400">
            Need token or MRN recovery? Contact Central Helpdesk at <span class="font-bold text-slate-700 dark:text-slate-300 font-mono">+880 1700-000001</span>
        </div>
    </div>
</div>
@endsection
