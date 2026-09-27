<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>Lab Report - {{ $report->report_number }}</title>
    <style>
        @page {
            margin: 15mm 15mm 20mm 15mm;
            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
                font-size: 8pt;
                color: #64748b;
            }
        }
        body {
            font-family: 'SolaimanLipi', 'DejaVu Sans', 'Arial', sans-serif;
            color: #1e293b;
            font-size: 11pt;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #0d9488;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }
        .center-title {
            text-align: center;
        }
        .center-name {
            font-size: 18pt;
            font-weight: bold;
            color: #0f766e;
            margin: 0;
            text-transform: uppercase;
        }
        .sub-title {
            font-size: 9pt;
            color: #64748b;
            margin: 2px 0 4px 0;
        }
        .center-contact {
            font-size: 8.5pt;
            color: #334155;
        }
        .patient-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 16px;
            font-size: 9.5pt;
        }
        .patient-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .patient-box td {
            padding: 3px 6px;
            vertical-align: top;
        }
        .label {
            color: #64748b;
            font-weight: 600;
        }
        .test-title-bar {
            background: #0d9488;
            color: #ffffff;
            padding: 6px 12px;
            font-size: 11pt;
            font-weight: bold;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .results-table th {
            background: #f1f5f9;
            color: #334155;
            text-align: left;
            padding: 8px;
            font-size: 9.5pt;
            border-bottom: 1.5px solid #cbd5e1;
            text-transform: uppercase;
        }
        .results-table td {
            padding: 8px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 9.5pt;
        }
        .flag-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
        }
        .flag-normal {
            color: #059669;
        }
        .flag-high, .flag-low {
            background: #fef3c7;
            color: #b45309;
        }
        .flag-critical {
            background: #fee2e2;
            color: #b91c1c;
            animation: blink 1s;
        }
        .ai-summary-box {
            background: #f0fdfa;
            border: 1px solid #99f6e4;
            border-left: 4px solid #0d9488;
            padding: 10px 14px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 9pt;
        }
        .ai-title {
            color: #0f766e;
            font-weight: bold;
            font-size: 9.5pt;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
        }
        .signatures {
            margin-top: 35px;
            width: 100%;
            border-collapse: collapse;
        }
        .signatures td {
            width: 33.33%;
            vertical-align: bottom;
            text-align: center;
            font-size: 9pt;
        }
        .sig-line {
            border-top: 1px solid #94a3b8;
            margin: 0 15px 4px 15px;
        }
        .qr-block {
            text-align: center;
            padding-top: 10px;
        }
        .qr-placeholder {
            border: 1px dashed #0d9488;
            padding: 6px;
            display: inline-block;
            font-size: 7.5pt;
            color: #0f766e;
            background: #f8fafc;
            border-radius: 4px;
        }
        .footer-note {
            font-size: 8pt;
            color: #94a3b8;
            text-align: center;
            margin-top: 16px;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
        }
    </style>
</head>
<body>

    <!-- Letterhead -->
    <div class="header">
        <div class="center-title">
            <h1 class="center-name">{{ $letterhead['center_name'] }}</h1>
            <div class="sub-title">{{ $letterhead['sub_title'] }}</div>
            <div class="center-contact">
                {{ $letterhead['address'] }} | Phone: {{ $letterhead['phone'] }} | {{ $letterhead['email'] }}
            </div>
        </div>
    </div>

    <!-- Patient Details Matrix -->
    <div class="patient-box">
        <table>
            <tr>
                <td width="20%"><span class="label">Patient Name:</span></td>
                <td width="30%"><strong>{{ $patient->name }}</strong></td>
                <td width="20%"><span class="label">Report No:</span></td>
                <td width="30%"><strong>{{ $report->report_number }}</strong></td>
            </tr>
            <tr>
                <td><span class="label">Patient ID (MRN):</span></td>
                <td>{{ $patient->mrn }}</td>
                <td><span class="label">Order / Sample ID:</span></td>
                <td>{{ $report->order->order_number }} / {{ $report->orderItem->sample->barcode ?? 'SMP-BARCODE' }}</td>
            </tr>
            <tr>
                <td><span class="label">Age / Gender:</span></td>
                <td>{{ $patient->formatted_age }} / {{ ucfirst($patient->gender) }}</td>
                <td><span class="label">Sample Collected:</span></td>
                <td>{{ $report->orderItem->sample->collected_at ? $report->orderItem->sample->collected_at->format('d-M-Y h:i A') : 'N/A' }}</td>
            </tr>
            <tr>
                <td><span class="label">Ref. Doctor:</span></td>
                <td>{{ $report->order->doctor->name ?? 'Self / Walk-in' }}</td>
                <td><span class="label">Report Verified:</span></td>
                <td>{{ $report->verified_at ? $report->verified_at->format('d-M-Y h:i A') : 'Pending Verification' }}</td>
            </tr>
        </table>
    </div>

    <!-- Test Title -->
    <div class="test-title-bar">
        DEPARTMENT OF {{ strtoupper($test->category->name ?? 'PATHOLOGY') }} &mdash; {{ strtoupper($test->name) }}
    </div>

    <!-- Results Table -->
    <table class="results-table">
        <thead>
            <tr>
                <th width="35%">Investigation Parameter</th>
                <th width="18%">Observed Value</th>
                <th width="15%">Unit</th>
                <th width="20%">Biological Ref. Range</th>
                <th width="12%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($values as $val)
                @php
                    $isCritical = in_array($val->flag, ['critical_low', 'critical_high']);
                    $isAbnormal = in_array($val->flag, ['low', 'high']);
                @endphp
                <tr>
                    <td><strong>{{ $val->parameter->name }}</strong></td>
                    <td style="{{ $isCritical ? 'color:#b91c1c; font-weight:bold;' : ($isAbnormal ? 'color:#b45309; font-weight:bold;' : '') }}">
                        {{ $val->value_numeric ?? $val->value_text }}
                    </td>
                    <td>{{ $val->unit }}</td>
                    <td style="color:#475569;">{{ $val->reference_range_snapshot }}</td>
                    <td>
                        @if($isCritical)
                            <span class="flag-badge flag-critical">CRITICAL</span>
                        @elseif($isAbnormal)
                            <span class="flag-badge flag-high">{{ strtoupper($val->flag) }}</span>
                        @else
                            <span class="flag-badge flag-normal">NORMAL</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- AI Clinical Insights (if enabled) -->
    @if(!empty($report->ai_summary))
        <div class="ai-summary-box">
            <div class="ai-title">🤖 AI Clinical & Patient Summary (Powered by AI Hub)</div>
            <div>{!! nl2br(e($report->ai_summary)) !!}</div>
        </div>
    @endif

    @if(!empty($report->clinical_impression))
        <div style="background:#f8fafc; border-left:4px solid #64748b; padding:8px 12px; margin-bottom:15px; font-size:9pt;">
            <strong>Pathologist Impression:</strong> {{ $report->clinical_impression }}
        </div>
    @endif

    <!-- QR Verification & Digital Signatures -->
    <table class="signatures">
        <tr>
            <td>
                <div class="qr-block">
                    <div class="qr-placeholder">
                        <div style="font-weight:bold; margin-bottom:2px;">SCAN TO VERIFY</div>
                        <div style="font-family:monospace; font-size:6.5pt;">{{ substr($report->verification_hash, 0, 16) }}...</div>
                        <div style="font-size:7pt; color:#64748b; margin-top:2px;">Authentic Electronic Record</div>
                    </div>
                </div>
            </td>
            <td>
                <div class="sig-line"></div>
                <strong>{{ $report->technician->name ?? 'Medical Technologist' }}</strong><br>
                <span style="color:#64748b;">Medical Laboratory Technologist</span>
            </td>
            <td>
                <div class="sig-line"></div>
                <strong>{{ $report->pathologist->name ?? 'Consultant Pathologist' }}</strong><br>
                <span style="color:#64748b;">{{ $report->pathologist->designation ?? 'MBBS, M.Phil / FCPS (Pathology)' }}</span>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        This document is an electronically verified lab report. Results relate solely to the sample received in the laboratory.
        For verification inquiries, visit {{ $letterhead['web'] }}
    </div>

</body>
</html>
