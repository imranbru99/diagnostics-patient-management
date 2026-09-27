<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Patient;
use App\Models\Report;
use App\Models\Test;
use App\Models\TestCategory;
use App\Models\TestPackage;
use App\Services\AI\DiagnosticAIService;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Home / Landing Page with Test Search.
     */
    public function index()
    {
        $categories = TestCategory::withCount('tests')->where('is_active', true)->get();
        $popularPackages = TestPackage::with('tests')->where('is_active', true)->where('is_popular', true)->take(4)->get();
        $featuredTests = Test::with('category')->where('is_active', true)->take(8)->get();
        $branches = Branch::where('is_active', true)->get();

        return view('public.index', compact('categories', 'popularPackages', 'featuredTests', 'branches'));
    }

    /**
     * Test Catalog with Search & Filter.
     */
    public function testCatalog(Request $request)
    {
        $query = Test::with('category')->where('is_active', true);

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('sample_type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->get('category'));
            });
        }

        $tests = $query->paginate(12)->withQueryString();
        $categories = TestCategory::where('is_active', true)->get();
        $packages = TestPackage::with('tests')->where('is_active', true)->get();

        return view('public.test-catalog', compact('tests', 'categories', 'packages'));
    }

    /**
     * Test Details Page (SEO-Safe Universal Slug).
     */
    public function testDetail(string $slug)
    {
        $test = Test::with(['category', 'parameters.referenceRanges', 'packages'])->where('slug', $slug)->firstOrFail();
        $relatedTests = Test::where('test_category_id', $test->test_category_id)->where('id', '!=', $test->id)->take(4)->get();

        return view('public.test-detail', compact('test', 'relatedTests'));
    }

    /**
     * Public Online Appointment Booking Form.
     */
    public function bookAppointmentForm()
    {
        $branches = Branch::where('is_active', true)->get();
        $tests = Test::where('is_active', true)->get();
        $packages = TestPackage::where('is_active', true)->get();

        return view('public.book-appointment', compact('branches', 'tests', 'packages'));
    }

    /**
     * AI Symptom-Based Package Recommendation API.
     */
    public function recommendPackagesApi(Request $request, DiagnosticAIService $aiService)
    {
        $symptoms = $request->input('symptoms', '');
        if (empty($symptoms)) {
            return response()->json(['packages' => []]);
        }

        $recommendations = $aiService->recommendPackages($symptoms);
        return response()->json(['packages' => $recommendations]);
    }

    /**
     * Store Appointment Booking.
     */
    public function storeAppointment(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:0|max:120',
            'branch_id' => 'required|exists:branches,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
            'test_id' => 'nullable|exists:tests,id',
            'test_package_id' => 'nullable|exists:test_packages,id',
            'symptoms_chief_complaint' => 'nullable|string',
        ]);

        // Find or create patient
        $patient = Patient::firstOrCreate(
            ['phone' => $validated['phone']],
            [
                'name' => $validated['patient_name'],
                'gender' => $validated['gender'],
                'age_years' => $validated['age'],
                'branch_id' => $validated['branch_id'],
            ]
        );

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'branch_id' => $validated['branch_id'],
            'test_id' => $validated['test_id'] ?? null,
            'test_package_id' => $validated['test_package_id'] ?? null,
            'appointment_date' => $validated['appointment_date'],
            'time_slot' => $validated['time_slot'],
            'queue_status' => 'booked',
            'is_walk_in' => false,
            'symptoms_chief_complaint' => $validated['symptoms_chief_complaint'] ?? null,
        ]);

        return redirect()->route('public.booking-confirmation', $appointment->appointment_number);
    }

    public function bookingConfirmation(string $appointmentNumber)
    {
        $appointment = Appointment::with(['patient', 'branch', 'test', 'testPackage'])
            ->where('appointment_number', $appointmentNumber)
            ->firstOrFail();

        return view('public.booking-confirmation', compact('appointment'));
    }

    /**
     * Lounge TV Queue Display Screen.
     */
    public function tvQueue(Request $request)
    {
        $branchId = $request->input('branch_id', 1);
        $branch = Branch::find($branchId) ?? Branch::first();

        $serving = Appointment::where('branch_id', $branch->id ?? 1)
            ->whereDate('appointment_date', today())
            ->where('queue_status', 'in_service')
            ->with(['patient', 'test', 'testPackage'])
            ->take(4)
            ->get();

        $waiting = Appointment::where('branch_id', $branch->id ?? 1)
            ->whereDate('appointment_date', today())
            ->whereIn('queue_status', ['checked_in', 'called'])
            ->with(['patient', 'test', 'testPackage'])
            ->orderBy('id')
            ->take(8)
            ->get();

        return view('public.tv-queue', compact('branch', 'serving', 'waiting'));
    }

    /**
     * Report Authenticity Public QR Verification.
     */
    public function verifyReport(string $hash)
    {
        $report = Report::with(['patient', 'orderItem.test', 'pathologist', 'branch'])
            ->where('verification_hash', $hash)
            ->first();

        return view('public.verify-report', compact('report', 'hash'));
    }
}
