<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Referral Statement - {{ $doctor->name }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10pt; color: #1e293b; margin: 0; padding: 15px; }
        .header { border-bottom: 2px solid #0d9488; padding-bottom: 10px; margin-bottom: 15px; text-align: center; }
        .title { font-size: 16pt; font-weight: bold; color: #0f766e; text-transform: uppercase; margin: 0; }
        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .meta-table td { padding: 4px; font-size: 9pt; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table th { background: #f1f5f9; padding: 6px; text-align: left; font-size: 9pt; border-bottom: 1px solid #cbd5e1; }
        .table td { padding: 6px; border-bottom: 1px solid #f1f5f9; font-size: 9pt; }
        .footer { font-size: 8pt; color: #64748b; text-align: center; margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">{{ $letterhead['center_name'] }}</h1>
        <div style="font-size: 8.5pt; color: #64748b;">{{ $letterhead['address'] }} | Phone: {{ $letterhead['phone'] }}</div>
        <div style="font-size: 11pt; font-weight: bold; margin-top: 6px; color: #0f766e;">DOCTOR REFERRAL COMMISSION STATEMENT</div>
    </div>

    <table class="meta-table">
        <tr>
            <td width="55%">
                <strong>Referring Doctor:</strong> {{ $doctor->name }}<br>
                <strong>Designation:</strong> {{ $doctor->designation }} ({{ $doctor->department }})<br>
                <strong>Affiliation:</strong> {{ $doctor->hospital_affiliation }}<br>
                <strong>Doctor Code:</strong> {{ $doctor->code }}
            </td>
            <td width="45%">
                <strong>Period:</strong> {{ $startDate }} to {{ $endDate }}<br>
                <strong>Default Rate:</strong> {{ $doctor->default_commission_rate }}%<br>
                <strong>Payment Method:</strong> {{ $doctor->payout_method ?? 'Bank / Electronic Payout' }}
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Order Ref</th>
                <th>Patient Name</th>
                <th>Order Amount</th>
                <th>Rate (%)</th>
                <th style="text-align: right;">Commission</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $totalCommission = 0; @endphp
            @foreach($commissions as $comm)
                @php $totalCommission += $comm->commission_amount; @endphp
                <tr>
                    <td>{{ $comm->created_at->format('d-M-Y') }}</td>
                    <td>{{ $comm->order->order_number }}</td>
                    <td>{{ $comm->order->patient->name }}</td>
                    <td>${{ number_format($comm->order_total, 2) }}</td>
                    <td>{{ $comm->commission_rate }}%</td>
                    <td style="text-align: right; font-weight: bold;">${{ number_format($comm->commission_amount, 2) }}</td>
                    <td><span style="font-size: 8pt; text-transform: uppercase;">{{ $comm->status }}</span></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f8fafc; font-weight: bold;">
                <td colspan="5" style="text-align: right;">Total Accrued / Payable Commission:</td>
                <td style="text-align: right; color: #0f766e; font-size: 11pt;">${{ number_format($totalCommission, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Confidential Document &bull; Generated on {{ now()->format('d-M-Y H:i') }} &bull; {{ $letterhead['center_name'] }}
    </div>
</body>
</html>
