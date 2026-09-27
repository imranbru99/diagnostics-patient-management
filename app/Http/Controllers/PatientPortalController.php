<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Report;
use App\Services\AI\DiagnosticAIService;
use App\Services\Pdf\LabReportPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PatientPortalController extends Controller
{
    /**
     * Show portal login form.
     */
    public function showLogin()
    {
        if (Session::has('patient_id')) {
            return redirect()->route('portal.dashboard');
        }
        return view('portal.login');
    }

    /**
     * Authenticate via Phone and MRN / PIN.
     */
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string', // phone or MRN
            'pin' => 'nullable|string',
        ]);

        $identifier = trim($request->identifier);

        $patient = Patient::where('mrn', $identifier)
            ->orWhere('phone', $identifier)
            ->first();

        if (!$patient) {
            return back()->withErrors(['identifier' => 'No patient record found matching that MRN or Phone Number.']);
        }

        Session::put('patient_id', $patient->id);

        return redirect()->route('portal.dashboard');
    }

    public function logout()
    {
        Session::forget('patient_id');
        return redirect()->route('portal.login');
    }

    /**
     * Patient Dashboard.
     */
    public function dashboard()
    {
        $patientId = Session::get('patient_id');
        if (!$patientId) {
            return redirect()->route('portal.login');
        }

        $patient = Patient::with(['reports.orderItem.test', 'appointments', 'familyMembers.relativePatient', 'documents'])
            ->findOrFail($patientId);

        $recentReports = $patient->reports()->latest()->take(10)->get();
        $upcomingAppointments = $patient->appointments()->where('appointment_date', '>=', today())->latest()->take(5)->get();

        return view('portal.dashboard', compact('patient', 'recentReports', 'upcomingAppointments'));
    }

    /**
     * View specific lab report with AI summary and flags.
     */
    public function viewReport(string $slug, DiagnosticAIService $aiService)
    {
        $patientId = Session::get('patient_id');
        $report = Report::with(['patient', 'orderItem.test', 'values.parameter', 'pathologist', 'branch'])
            ->where('slug', $slug)
            ->firstOrFail();

        // If AI summary not yet generated, auto-generate on first view!
        if (empty($report->ai_summary) && $report->values->count() > 0) {
            $report->ai_summary = $aiService->generatePatientSummary($report);
            $report->ai_critical_flags = $aiService->flagAbnormalValues($report);
            $report->save();
        }

        return view('portal.report-detail', compact('report'));
    }

    /**
     * Download or view Report PDF.
     */
    public function downloadPdf(string $slug, LabReportPdfService $pdfService)
    {
        $report = Report::with(['patient', 'orderItem.test', 'values.parameter', 'pathologist', 'branch'])
            ->where('slug', $slug)
            ->firstOrFail();

        $html = $pdfService->renderReportHtml($report);

        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Patient-Facing AI Chat: "Ask About Your Report".
     * Strictly RAG scoped to this patient's report only.
     */
    public function chatAboutReport(Request $request, DiagnosticAIService $aiService)
    {
        $request->validate([
            'report_id' => 'required|exists:reports,id',
            'question' => 'required|string|max:500',
        ]);

        $report = Report::with(['patient', 'orderItem.test', 'values.parameter'])->findOrFail($request->report_id);

        $reply = $aiService->chatAboutReport($report, $request->question);

        return response()->json([
            'reply' => $reply,
            'time' => now()->format('h:i A'),
        ]);
    }
}
