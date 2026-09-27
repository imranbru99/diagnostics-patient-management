@extends('layouts.public')

@section('title', 'Book Diagnostic Phlebotomy Appointment | Apex Diagnostics')
@section('meta_description', 'Schedule online laboratory phlebotomy appointments with AI symptom-based test recommendation and digital queue token generation.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="appointmentBooking()">

    <!-- Header & Step Indicator -->
    <div class="text-center max-w-2xl mx-auto mb-10">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 dark:bg-teal-950 text-teal-700 dark:text-teal-300 text-xs font-bold mb-3 border border-teal-200 dark:border-teal-800">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Online Queue Fast-Track &bull; Zero Lounge Delays
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
            Schedule Lab Appointment
        </h1>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
            Get an instant digital token slip, prepare for specimen draw, and track live queue progress.
        </p>

        <!-- Stepper Progress Bar -->
        <div class="mt-8 flex items-center justify-center gap-3">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold" :class="step >= 1 ? 'bg-teal-600 text-white shadow-sm' : 'bg-slate-200 dark:bg-darknavy-800 text-slate-500'">1</span>
                <span class="text-xs font-bold" :class="step >= 1 ? 'text-slate-900 dark:text-white' : 'text-slate-400'">Tests &amp; AI</span>
            </div>
            <div class="w-8 sm:w-12 h-0.5 rounded-full" :class="step >= 2 ? 'bg-teal-600' : 'bg-slate-200 dark:bg-slate-700'"></div>
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold" :class="step >= 2 ? 'bg-teal-600 text-white shadow-sm' : 'bg-slate-200 dark:bg-darknavy-800 text-slate-500'">2</span>
                <span class="text-xs font-bold" :class="step >= 2 ? 'text-slate-900 dark:text-white' : 'text-slate-400'">Schedule</span>
            </div>
            <div class="w-8 sm:w-12 h-0.5 rounded-full" :class="step >= 3 ? 'bg-teal-600' : 'bg-slate-200 dark:bg-slate-700'"></div>
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold" :class="step >= 3 ? 'bg-teal-600 text-white shadow-sm' : 'bg-slate-200 dark:bg-darknavy-800 text-slate-500'">3</span>
                <span class="text-xs font-bold" :class="step >= 3 ? 'text-slate-900 dark:text-white' : 'text-slate-400'">Demographics</span>
            </div>
        </div>
    </div>

    <!-- AI Health Package Recommender Card -->
    <div class="p-6 sm:p-8 bg-gradient-to-r from-teal-50 via-emerald-50/50 to-teal-50 dark:from-darknavy-800 dark:via-darknavy-850 dark:to-darknavy-800 rounded-3xl border border-teal-200/80 dark:border-teal-900/80 mb-8 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-xl bg-teal-600 text-white flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </span>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">AI Health Package Recommender</h3>
                    <p class="text-[11px] text-teal-700 dark:text-teal-400">Powered by <code>laravel-ai-hub</code> clinical inference engine</p>
                </div>
            </div>
            <span class="hidden sm:inline-block px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-teal-100 dark:bg-teal-950 text-teal-800 dark:text-teal-300">
                Decision Support
            </span>
        </div>

        <p class="text-xs text-slate-600 dark:text-slate-300 mb-4 leading-relaxed">
            Unsure which diagnostic test or checkup package fits your condition? Describe your current symptoms below to receive instant suggestions:
        </p>

        <!-- Quick Symptom Tags -->
        <div class="flex flex-wrap gap-1.5 mb-3 text-[11px]">
            <span class="text-slate-400 font-semibold self-center mr-1">Quick Select:</span>
            <button type="button" @click="symptomsInput = 'Fatigue, dizziness and cold extremities'; getRecommendations()" class="px-2.5 py-1 rounded-lg bg-white dark:bg-darknavy-900 border border-teal-200 dark:border-teal-800 text-slate-700 dark:text-slate-300 hover:bg-teal-50">
                Fatigue &amp; Dizziness
            </button>
            <button type="button" @click="symptomsInput = 'Frequent urination, excessive thirst, weight loss'; getRecommendations()" class="px-2.5 py-1 rounded-lg bg-white dark:bg-darknavy-900 border border-teal-200 dark:border-teal-800 text-slate-700 dark:text-slate-300 hover:bg-teal-50">
                High Blood Sugar
            </button>
            <button type="button" @click="symptomsInput = 'Chest discomfort, shortness of breath on exertion'; getRecommendations()" class="px-2.5 py-1 rounded-lg bg-white dark:bg-darknavy-900 border border-teal-200 dark:border-teal-800 text-slate-700 dark:text-slate-300 hover:bg-teal-50">
                Cardiac Check
            </button>
            <button type="button" @click="symptomsInput = 'Joint pain, morning stiffness and swelling'; getRecommendations()" class="px-2.5 py-1 rounded-lg bg-white dark:bg-darknavy-900 border border-teal-200 dark:border-teal-800 text-slate-700 dark:text-slate-300 hover:bg-teal-50">
                Rheumatoid &amp; Uric Acid
            </button>
        </div>

        <div class="flex gap-2">
            <input
                type="text"
                x-model="symptomsInput"
                placeholder="Enter symptoms (e.g. constant exhaustion, hair loss, elevated blood pressure)..."
                class="w-full text-xs sm:text-sm rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-darknavy-900 px-4 py-2.5 text-slate-900 dark:text-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 placeholder-slate-400"
                @keydown.enter.prevent="getRecommendations"
            >
            <button
                type="button"
                @click="getRecommendations"
                :disabled="isLoadingAi"
                class="px-5 py-2.5 text-xs font-bold rounded-xl bg-teal-600 hover:bg-teal-700 text-white shrink-0 shadow-sm transition-all flex items-center gap-1.5"
            >
                <span x-show="!isLoadingAi">Analyze</span>
                <span x-show="isLoadingAi" x-cloak class="flex items-center gap-1">
                    <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Matching...
                </span>
            </button>
        </div>

        <!-- AI Output Cards -->
        <div x-show="recommendations.length > 0" x-cloak class="mt-4 pt-4 border-t border-teal-200 dark:border-teal-800 space-y-2">
            <div class="text-xs font-bold text-teal-900 dark:text-teal-300 flex items-center justify-between">
                <span>Recommended Health Screening Bundles:</span>
                <span class="text-[10px] text-slate-400">Click Select to auto-populate form</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
                <template x-for="pkg in recommendations" :key="pkg.package_id">
                    <div class="p-3.5 bg-white dark:bg-darknavy-900 rounded-2xl border border-teal-200 dark:border-teal-800 flex items-center justify-between shadow-sm">
                        <div class="pr-2">
                            <div class="text-xs font-bold text-slate-900 dark:text-white" x-text="pkg.name"></div>
                            <div class="text-[11px] text-teal-600 dark:text-teal-400 mt-0.5" x-text="pkg.relevance_reason"></div>
                        </div>
                        <button
                            type="button"
                            @click="selectPackage(pkg.package_id)"
                            class="px-3 py-1.5 text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white rounded-xl shadow-sm shrink-0 transition-all"
                        >
                            Select
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Booking Form Container -->
    <div class="p-8 bg-white dark:bg-darknavy-800 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl">
        <form action="{{ route('public.store-appointment') }}" method="POST" class="space-y-8">
            @csrf

            <!-- STEP 1: Investigation Selection -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-700/80">
                    <span class="w-6 h-6 rounded-lg bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400 text-xs font-bold flex items-center justify-center">1</span>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Select Investigation or Health Package</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Single Test Dropdown -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                            Individual Diagnostic Test:
                        </label>
                        <select
                            name="test_id"
                            x-model="selectedTestId"
                            @change="onTestChange"
                            class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 px-3.5 py-3 text-slate-900 dark:text-white focus:border-brand-500 focus:ring-brand-500"
                        >
                            <option value="">-- Choose Individual Test (Optional) --</option>
                            @foreach($tests as $t)
                                <option value="{{ $t->id }}" {{ (string) request('test') === (string) $t->id ? 'selected' : '' }}>
                                    {{ $t->name }} ({{ $t->code }}) - ${{ number_format($t->price, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Health Checkup Bundle Dropdown -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                            Comprehensive Package Bundle:
                        </label>
                        <select
                            name="test_package_id"
                            x-model="selectedPackageId"
                            @change="onPackageChange"
                            class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 px-3.5 py-3 text-slate-900 dark:text-white focus:border-brand-500 focus:ring-brand-500"
                        >
                            <option value="">-- Choose Checkup Package (Optional) --</option>
                            @foreach($packages as $p)
                                <option value="{{ $p->id }}" {{ (string) request('package') === (string) $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} - ${{ number_format($p->price, 2) }} ({{ $p->discount_percentage }}% Off)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Scheduling & Branch -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-700/80">
                    <span class="w-6 h-6 rounded-lg bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400 text-xs font-bold flex items-center justify-center">2</span>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Facility &amp; Time Slot</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Branch -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Lab Branch *</label>
                        <select
                            name="branch_id"
                            required
                            class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 px-3.5 py-3 text-slate-900 dark:text-white"
                        >
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Appointment Date *</label>
                        <input
                            type="date"
                            name="appointment_date"
                            required
                            min="{{ date('Y-m-d') }}"
                            value="{{ date('Y-m-d') }}"
                            class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 px-3.5 py-3 text-slate-900 dark:text-white"
                        >
                    </div>

                    <!-- Time Slot -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Phlebotomy Slot *</label>
                        <select
                            name="time_slot"
                            required
                            class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 px-3.5 py-3 text-slate-900 dark:text-white"
                        >
                            <option value="07:00-09:00">07:00 AM - 09:00 AM (Fasting Draw)</option>
                            <option value="09:00-11:00" selected>09:00 AM - 11:00 AM (Morning Routine)</option>
                            <option value="11:00-13:00">11:00 AM - 01:00 PM (Midday)</option>
                            <option value="14:00-17:00">02:00 PM - 05:00 PM (Afternoon)</option>
                            <option value="17:00-20:00">05:00 PM - 08:00 PM (Evening STAT)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Patient Demographics -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-700/80">
                    <span class="w-6 h-6 rounded-lg bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400 text-xs font-bold flex items-center justify-center">3</span>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Patient Demographics</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Patient Full Name *</label>
                        <input
                            type="text"
                            name="patient_name"
                            required
                            placeholder="e.g. Farhan Akhtar"
                            class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 px-3.5 py-3 text-slate-900 dark:text-white placeholder-slate-400"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Mobile Phone (For Token SMS &amp; OTP) *</label>
                        <input
                            type="tel"
                            name="phone"
                            required
                            placeholder="e.g. +880 1712-345678"
                            class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 px-3.5 py-3 text-slate-900 dark:text-white font-mono placeholder-slate-400"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Gender *</label>
                        <select
                            name="gender"
                            required
                            class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 px-3.5 py-3 text-slate-900 dark:text-white"
                        >
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Age (Years) *</label>
                        <input
                            type="number"
                            name="age"
                            required
                            min="0"
                            max="120"
                            value="35"
                            class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 px-3.5 py-3 text-slate-900 dark:text-white font-mono"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Chief Complaint / Doctor Notes</label>
                        <input
                            type="text"
                            name="symptoms_chief_complaint"
                            x-model="symptomsInput"
                            placeholder="e.g. Prescribed by Dr. Rahman, routine check"
                            class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-darknavy-900 px-3.5 py-3 text-slate-900 dark:text-white placeholder-slate-400"
                        >
                    </div>
                </div>
            </div>

            <!-- Submit Button & Guarantee Seal -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-700/80 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                    <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span>Instant queue token issued. No advance credit card required.</span>
                </div>

                <button
                    type="submit"
                    class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-gradient-to-r from-brand-600 to-teal-600 hover:from-brand-700 hover:to-teal-700 text-white text-xs sm:text-sm font-bold shadow-lg shadow-brand-500/25 hover:shadow-glow-teal transition-all flex items-center justify-center gap-2 transform hover:-translate-y-0.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Confirm &amp; Generate Token
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function appointmentBooking() {
        return {
            step: 1,
            symptomsInput: '{{ request("symptoms", "") }}',
            selectedTestId: '{{ request("test", "") }}',
            selectedPackageId: '{{ request("package", "") }}',
            isLoadingAi: false,
            recommendations: [],
            onTestChange() {
                if (this.selectedTestId) {
                    this.step = 2;
                }
            },
            onPackageChange() {
                if (this.selectedPackageId) {
                    this.step = 2;
                }
            },
            async getRecommendations() {
                if (!this.symptomsInput.trim()) return;
                this.isLoadingAi = true;
                try {
                    const res = await fetch('{{ route("api.recommend-packages") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ symptoms: this.symptomsInput })
                    });
                    const data = await res.json();
                    this.recommendations = data.packages || [];
                } catch (e) {
                    console.error('AI Recommender error:', e);
                } finally {
                    this.isLoadingAi = false;
                }
            },
            selectPackage(pkgId) {
                this.selectedPackageId = pkgId;
                this.step = 2;
            }
        };
    }
</script>
@endsection
