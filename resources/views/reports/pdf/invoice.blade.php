<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10pt; color: #1e293b; margin: 0; padding: 15px; }
        .header { border-bottom: 2px solid #0d9488; padding-bottom: 10px; margin-bottom: 15px; text-align: center; }
        .title { font-size: 16pt; font-weight: bold; color: #0f766e; text-transform: uppercase; margin: 0; }
        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .meta-table td { padding: 4px; font-size: 9pt; vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .items-table th { background: #f1f5f9; padding: 6px; text-align: left; font-size: 9pt; border-bottom: 1px solid #cbd5e1; }
        .items-table td { padding: 6px; border-bottom: 1px solid #f1f5f9; font-size: 9pt; }
        .totals-table { width: 45%; margin-left: auto; border-collapse: collapse; }
        .totals-table td { padding: 4px; font-size: 9.5pt; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 8pt; font-weight: bold; }
        .badge-paid { background: #dcfce7; color: #15803d; }
        .badge-unpaid { background: #fee2e2; color: #b91c1c; }
        .footer { font-size: 8pt; color: #64748b; text-align: center; margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">{{ $letterhead['center_name'] }}</h1>
        <div style="font-size: 8.5pt; color: #64748b;">{{ $letterhead['address'] }} | Phone: {{ $letterhead['phone'] }}</div>
        <div style="font-size: 11pt; font-weight: bold; margin-top: 6px; color: #0f766e;">PATIENT BILLING INVOICE & RECEIPT</div>
    </div>

    <table class="meta-table">
        <tr>
            <td width="55%">
                <strong>Patient:</strong> {{ $patient->name }}<br>
                <strong>MRN:</strong> {{ $patient->mrn }}<br>
                <strong>Age/Gender:</strong> {{ $patient->formatted_age }} / {{ ucfirst($patient->gender) }}<br>
                <strong>Phone:</strong> {{ $patient->phone }}
            </td>
            <td width="45%">
                <strong>Invoice No:</strong> {{ $invoice->invoice_number }}<br>
                <strong>Order Ref:</strong> {{ $order->order_number }}<br>
                <strong>Date & Time:</strong> {{ $invoice->created_at->format('d-M-Y h:i A') }}<br>
                <strong>Payment Status:</strong> 
                <span class="badge {{ $invoice->payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                    {{ strtoupper($invoice->payment_status) }}
                </span>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="8%">#</th>
                <th width="52%">Investigation / Service Description</th>
                <th width="20%" style="text-align: right;">Unit Price</th>
                <th width="20%" style="text-align: right;">Net Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->test->name ?? $item->testPackage->name ?? 'Diagnostic Test' }}</td>
                    <td style="text-align: right;">${{ number_format($item->price, 2) }}</td>
                    <td style="text-align: right;">${{ number_format($item->final_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td style="text-align: right;">${{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td>Discount:</td>
            <td style="text-align: right;">-${{ number_format($invoice->discount_amount, 2) }}</td>
        </tr>
        <tr style="border-top: 1px solid #cbd5e1; font-weight: bold;">
            <td>Total Payable:</td>
            <td style="text-align: right; color: #0f766e;">${{ number_format($invoice->net_total, 2) }}</td>
        </tr>
        <tr>
            <td>Paid Amount:</td>
            <td style="text-align: right;">${{ number_format($invoice->paid_amount, 2) }}</td>
        </tr>
        <tr style="font-weight: bold; color: #b91c1c;">
            <td>Balance Due:</td>
            <td style="text-align: right;">${{ number_format($invoice->balance_due, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        Thank you for choosing {{ $letterhead['center_name'] }}. Keep this receipt for report collection.
    </div>
</body>
</html>
