<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 30px;
            color: #334155;
        }

        .card {
            max-width: 580px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            padding: 32px;
            border: 1px solid #e2e8f0;
        }

        .badge {
            display: inline-block;
            background: #ecfdf5;
            color: #047857;
            font-size: 11px;
            font-weight: bold;
            padding: 4px 10px;
            border-radius: 9999px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 16px;
        }

        .summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            margin: 20px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #059669;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            margin-top: 8px;
        }

        .footer {
            font-size: 11px;
            color: #94a3b8;
            text-align: center;
            margin-top: 24px;
        }
    </style>
</head>

<body>
    <div class="card">
        <span class="badge">Tax Invoice Notification</span>
        <h1 class="title">Dear
            {{ $invoice->customer->name }},</h1>
        <p style="font-size: 14px; line-height: 1.5;">
            Thank you for choosing
            <strong>{{ $invoice->business->name }}</strong>.
            Please find attached your official GST Tax
            Invoice
            <strong>#{{ $invoice->invoice_number }}</strong>.
        </p>

        <div class="summary-box">
            <div class="row">
                <span>Invoice Date:</span>
                <strong>{{ $invoice->invoice_date->format('d M Y') }}</strong>
            </div>
            <div class="row">
                <span>Payment Due Date:</span>
                <strong>{{ $invoice->due_date->format('d M Y') }}</strong>
            </div>
            <div class="row">
                <span>GSTIN:</span>
                <strong>{{ $invoice->customer->gstin ?: 'Unregistered' }}</strong>
            </div>
            <div class="row grand-total">
                <span>Total Amount Due:</span>
                <span>₹{{ number_format($invoice->grand_total, 2) }}</span>
            </div>
        </div>

        <p style="font-size: 12px; color: #64748b;">
            A complete PDF breakdown including HSN/SAC
            codes, CGST, SGST, and IGST details has been
            attached to this email for your accounting
            records.
        </p>

        <div class="footer">
            &copy; {{ date('Y') }}
            {{ $invoice->business->name }}. GST Compliant
            Systems.
        </div>
    </div>
</body>

</html>
