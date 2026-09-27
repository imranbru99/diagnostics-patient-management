@extends('layouts.public')

@section('title', 'Book Diagnostic Appointment Online | Apex Diagnostics')
@section('meta_description', 'Schedule an online lab appointment with AI-assisted test recommendations based on your symptoms.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="appointmentBooking()">
    <div class="text-center max-w-2xl mx-auto mb-10">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-50 dark:bg-teal-950 text-teal-700 dark:text-teal-400 text-xs font-semibold mb-3">
            <span class="w-2 h-2 rounded-full bg-teal-500"></span> Online Queue Fast-Track
        </span>
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Book Your Lab Appointment</h1>
        <p class="mt-2 text-sm text-slate-500">Fast token generation, minimal waiting lounge delays, and instant digital report tracking.</p>
    </div>

    <!-- AI Symptom Assistant Card -->
    <div class="p-6 bg-gradient-to-r from-teal-50 to-emerald-50 dark:from-slate-900 dark:to-slate-800 rounded-3xl border border-teal-200/60 dark:border-teal-900 mb-8 shadow-sm">
        <div class="flex items-center gap-2 mb-3">
            <span class="p-1.5 rounded-lg bg-teal-600 text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </span>
            <h3 class="text-sm font-bold text-slate-900 dark:text-white">AI Health Package Recommender (Optional)</h3>
        </div>
        <p class="text-xs text-slate-600 dark:text-slate-400 mb-3">
            Not sure which package to choose? Describe your current symptoms (e.g. "Fatigue, excessive thirst, and dizziness") and our AI will recommend relevant health screening panels.
        </p>
        <div class="flex gap-2">
            <input
                type="text"
                x-model="symptomsInput"
                placeholder="Enter symptoms (e.g. chest discomfort, weakness, fever)..."
                class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2 text-slate-900 dark:text-white focus:border-brand-500 focus:ring-brand-500"
                @keydown.enter.prevent="getRecommendations"
            >
            <button
                type="button"
                @click="getRecommendations"
                :disabled="isLoadingAi"
                class="px-4 py-2 text-xs font-semibold rounded-xl bg-teal-700 hover:bg-teal-800 text-white shrink-0 transition-colors"
            >
                <span x-show="!isLoadingAi">Recommend</span>
                <span x-show="isLoadingAi" x-cloak>Analyzing...</span>
            </button>
        </div>

        <!-- AI Recommendations Output -->
        <div x-show="recommendations.length > 0" x-cloak class="mt-4 pt-4 border-t border-teal-200/60 dark:border-teal-900/60 space-y-2">
            <div class="text-xs font-bold text-teal-900 dark:text-teal-300">Suggested Diagnostic Packages:</div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <template x-for="pkg in recommendations" :key="pkg.package_id">
                    <div class="p-3 bg-white dark:bg-slate-900 rounded-xl border border-teal-200 dark:border-teal-800 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-slate-900 dark:text-white" x-text="pkg.name"></div>
                            <div class="text-[11px] text-teal-600 dark:text-teal-400" x-text="pkg.relevance_reason"></div>
                        </div>
                        <button
                            type="button"
                            @click="selectPackage(pkg.package_id)"
                            class="px-2.5 py-1 text-[11px] font-semibold bg-teal-50 dark:bg-teal-950 text-teal-700 dark:text-teal-300 rounded-lg border border-teal-200"
                        >
                            Select
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Booking Form -->
    <div class="p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <form action="{{ route('public.store-appointment') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Patient Full Name *</label>
                    <input type="text" name="patient_name" required placeholder="e.g. Tanvir Hossain" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3.5 py-2.5 text-slate-900 dark:text-white focus:border-brand-500 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Mobile Phone (For SMS Token &amp; OTP) *</label>
                    <input type="tel" name="phone" required placeholder="e.g. +880 1712-345678" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3.5 py-2.5 text-slate-900 dark:text-white focus:border-brand-500 focus:ring-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Gender *</label>
                    <select name="gender" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3.5 py-2.5 text-slate-900 dark:text-white">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Age (Years) *</label>
                    <input type="number" name="age" required min="0" max="120" value="32" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3.5 py-2.5 text-slate-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Preferred Branch Location *</label>
                    <select name="branch_id" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3.5 py-2.5 text-slate-900 dark:text-white">
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Preferred Date *</label>
                    <input type="date" name="appointment_date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3.5 py-2.5 text-slate-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Time Slot *</label>
                    <select name="time_slot" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3.5 py-2.5 text-slate-900 dark:text-white">
                        <option value="08:00 - 09:00">08:00 AM - 09:00 AM (Fasting Sample)</option>
                        <option value="09:00 - 10:00" selected>09:00 AM - 10:00 AM</option>
                        <option value="10:00 - 11:00">10:00 AM - 11:00 AM</option>
                        <option value="11:00 - 12:00">11:00 AM - 12:00 PM</option>
                        <option value="14:00 - 15:00">02:00 PM - 03:00 PM (Afternoon Slot)</option>
                        <option value="16:00 - 17:00">04:00 PM - 05:00 PM</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Individual Test (Optional)</label>
                    <select name="test_id" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3.5 py-2.5 text-slate-900 dark:text-white">
                        <option value="">-- None (Select from Packages below) --</option>
                        @foreach($tests as $t)
                            <option value="{{ $t->id }}" {{ request('test') == $t->id ? 'selected' : '' }}>
                                {{ $t->name }} (${{ number_format($t->price, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Health Package (Optional)</label>
                    <select name="test_package_id" x-ref="packageSelect" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3.5 py-2.5 text-slate-900 dark:text-white">
                        <option value="">-- None --</option>
                        @foreach($packages as $p)
                            <option value="{{ $p->id }}" {{ request('package') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} (${{ number_format($p->price, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Chief Complaints / Clinical Symptoms</label>
                <textarea name="symptoms_chief_complaint" rows="2" placeholder="Describe any specific symptoms or referring doctor instructions..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3.5 py-2.5 text-slate-900 dark:text-white"></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 text-sm font-semibold rounded-xl bg-brand-600 hover:bg-brand-700 text-white shadow-md shadow-brand-500/20 transition-colors">
                Confirm &amp; Generate Queue Token
            </button>
        </form>
    </div>
</div>

<script>
    function appointmentBooking() {
        return {
            symptomsInput: '',
            isLoadingAi: false,
            recommendations: [],
            getRecommendations() {
                if (!this.symptomsInput.trim()) return;
                this.isLoadingAi = true;

                fetch('{{ route("api.recommend-packages") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ symptoms: this.symptomsInput })
                })
                .then(res => res.json())
                .then(data => {
                    this.recommendations = data.packages || [];
                    this.isLoadingAi = false;
                })
                .catch(() => {
                    this.isLoadingAi = false;
                });
            },
            selectPackage(pkgId) {
                this.$refs.packageSelect.value = pkgId;
            }
        }
    }
</script>
@endsection
