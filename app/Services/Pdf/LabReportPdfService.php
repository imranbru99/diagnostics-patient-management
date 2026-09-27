<?php

namespace App\Services\Pdf;

use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Report;
use Illuminate\Support\Facades\View;

class LabReportPdfService
{
    /**
     * Render the HTML report ready for mPDF or laravel-unicode-pdf.
     */
    public function renderReportHtml(Report $report, string $locale = 'en'): string
    {
        $letterhead = config('unicode-pdf.report_letterhead');
        $isRtl = ($locale === 'ar');

        return View::make('reports.pdf.lab-report', [
            'report' => $report,
            'patient' => $report->patient,
            'test' => $report->orderItem->test,
            'values' => $report->values()->with('parameter')->get(),
            'letterhead' => $letterhead,
            'locale' => $locale,
            'isRtl' => $isRtl,
            'verificationUrl' => url('/verify-report/' . $report->verification_hash),
        ])->render();
    }

    /**
     * Render Patient Invoice & Receipt HTML for PDF.
     */
    public function renderInvoiceHtml(Invoice $invoice, string $locale = 'en'): string
    {
        $letterhead = config('unicode-pdf.report_letterhead');

        return View::make('reports.pdf.invoice', [
            'invoice' => $invoice,
            'order' => $invoice->order,
            'patient' => $invoice->patient,
            'branch' => $invoice->branch,
            'letterhead' => $letterhead,
            'locale' => $locale,
        ])->render();
    }

    /**
     * Render Doctor Referral Commission Statement HTML for PDF.
     */
    public function renderReferralStatementHtml(Doctor $doctor, $startDate, $endDate): string
    {
        $letterhead = config('unicode-pdf.report_letterhead');
        $commissions = $doctor->commissions()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['order.patient', 'invoice'])
            ->get();

        return View::make('reports.pdf.referral-statement', [
            'doctor' => $doctor,
            'commissions' => $commissions,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'letterhead' => $letterhead,
        ])->render();
    }
}
