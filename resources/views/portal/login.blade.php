@extends('layouts.public')

@section('title', 'Patient Portal Sign In | Apex Diagnostics')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="text-center mb-6">
            <span class="p-3 rounded-2xl bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400 inline-block mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </span>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Patient Portal Sign In</h1>
            <p class="text-xs text-slate-500 mt-1">Access past lab reports, AI insights, and family test records.</p>
        </div>

        @if($errors->any())
            <div class="p-3 mb-4 rounded-xl bg-red-50 dark:bg-red-950/60 border border-red-200 text-xs text-red-600 dark:text-red-300">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('portal.login.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                    Medical Record Number (MRN) or Registered Phone Number
                </label>
                <input
                    type="text"
                    name="identifier"
                    required
                    placeholder="e.g. MRN-2026-00001 or +880 1712-345678"
                    class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3.5 py-2.5 text-slate-900 dark:text-white focus:border-brand-500 focus:ring-brand-500"
                >
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                    Portal Security PIN (Provided on receipt / SMS)
                </label>
                <input
                    type="password"
                    name="pin"
                    placeholder="4-digit PIN (or leave blank for demo)"
                    class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3.5 py-2.5 text-slate-900 dark:text-white focus:border-brand-500 focus:ring-brand-500"
                >
            </div>

            <button type="submit" class="w-full py-3 text-xs font-semibold rounded-xl bg-brand-600 hover:bg-brand-700 text-white shadow-md shadow-brand-500/20 transition-colors">
                Sign In to Patient Portal
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 text-center text-xs text-slate-400">
            Lost your MRN or token receipt? Call front desk at <span class="text-slate-600 dark:text-slate-300">+880 1700-000000</span>
        </div>
    </div>
</div>
@endsection
