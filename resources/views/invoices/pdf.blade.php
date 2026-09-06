<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type"
        content="text/html; charset=utf-8" />
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 20px 25px;
        }

        body {
            font-family: 'DejaVu Sans', 'Helvetica', sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            margin-bottom: 12px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
        }

        .company-title {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }

        .invoice-badge {
            font-size: 13px;
            font-weight: bold;
            text-align: right;
            color: #047857;
            text-transform: uppercase;
        }

        .info-box {
            border: 1px solid #cbd5e1;
            padding: 8px;
            vertical-align: top;
            width: 50%;
        }

        .info-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }

        .items-table {
            margin-top: 15px;
            border: 1px solid #0f172a;
        }

        .items-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 4px;
            text-align: center;
            border: 1px solid #0f172a;
        }

        .items-table td {
            padding: 5px 4px;
            border: 1px solid #cbd5e1;
            font-size: 9px;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .bg-gray {
            background-color: #f8fafc;
        }

        .totals-table {
            margin-top: 10px;
            width: 100%;
        }

        .totals-table td {
            padding: 4px 6px;
            font-size: 9px;
        }

        .grand-total-row {
            background-color: #f1f5f9;
            font-size: 11px;
            font-weight: bold;
            border-top: 1px solid #0f172a;
            border-bottom: 1px solid #0f172a;
        }

        .footer-note {
            margin-top: 25px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 8px;
            font-size: 8px;
            color: #64748b;
        }

        .signature-box {
            margin-top: 30px;
            text-align: right;
        }

        .signature-line {
            display: inline-block;
            width: 180px;
            border-top: 1px solid #0f172a;
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td style="width: 60%; vertical-align: bottom;">
                <div class="company-title">
                    {{ $invoice->business->name }}</div>
                <div
                    style="font-size: 9px; color: #475569; margin-top: 3px;">
                    {{ $invoice->business->address }},
                    {{ $invoice->business->city }} -
                    {{ $invoice->business->pincode }}<br>
                    State: {{ $invoice->business->state }}
                    (State Code:
                    {{ $invoice->business->state_code }})<br>
                    <strong>GSTIN:
                        {{ $invoice->business->gstin }}</strong>
                    | Phone: {{ $invoice->business->phone }}
                </div>
            </td>
            <td style="width: 40%; vertical-align: bottom;"
                class="text-right">
                <div class="invoice-badge">TAX INVOICE</div>
                <div
                    style="font-size: 10px; margin-top: 4px;">
                    <strong>Invoice No:</strong>
                    {{ $invoice->invoice_number }}<br>
                    <strong>Invoice Date:</strong>
                    {{ $invoice->invoice_date->format('d/m/Y') }}<br>
                    <strong>Due Date:</strong>
                    {{ $invoice->due_date->format('d/m/Y') }}<br>
                    <strong>Place of Supply:</strong>
                    {{ $invoice->place_of_supply }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Buyer / Billed To Section -->
    <table style="margin-bottom: 10px;">
        <tr>
            <td class="info-box" style="margin-right: 5px;">
                <div class="info-title">Details of Receiver
                    (Billed To):</div>
                <div class="bold"
                    style="font-size: 11px;">
                    {{ $invoice->customer->name }}</div>
                @if ($invoice->customer->company_name)
                    <div>
                        {{ $invoice->customer->company_name }}
                    </div>
                @endif
                <div>
                    {{ $invoice->customer->billing_address }}
                </div>
                <div>{{ $invoice->customer->city }},
                    {{ $invoice->customer->state }} -
                    {{ $invoice->customer->pincode }}</div>
                <div style="margin-top: 4px;">
                    <strong>GSTIN:</strong>
                    {{ $invoice->customer->gstin ?: 'Unregistered' }}<br>
                    <strong>State:</strong>
                    {{ $invoice->customer->state }} (Code:
                    {{ $invoice->customer->state_code }})
                </div>
            </td>
            <td style="width: 2%;"></td>
            <td class="info-box">
                <div class="info-title">Tax & Supply Terms:
                </div>
                <div><strong>Supply Category:</strong>
                    {{ $invoice->is_interstate ? 'Inter-State Supply (IGST)' : 'Intra-State Supply (CGST + SGST)' }}
                </div>
                <div><strong>Payment Mode:</strong>
                    {{ strtoupper($invoice->payment_mode ?? 'UPI/Bank') }}
                </div>
                <div><strong>Payment Status:</strong>
                    {{ strtoupper($invoice->status) }}
                </div>
                @if ($invoice->notes)
                    <div
                        style="margin-top: 4px; font-style: italic; color: #475569;">
                        <strong>Note:</strong>
                        {{ $invoice->notes }}
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Line Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 32%;" class="text-left">
                    Item Description</th>
                <th style="width: 10%;">HSN/SAC</th>
                <th style="width: 8%;">Qty</th>
                <th style="width: 10%;" class="text-right">
                    Unit Rate</th>
                <th style="width: 12%;" class="text-right">
                    Taxable Val</th>
                @if ($invoice->is_interstate)
                    <th style="width: 12%;"
                        class="text-right">IGST</th>
                @else
                    <th style="width: 6%;"
                        class="text-right">CGST</th>
                    <th style="width: 6%;"
                        class="text-right">SGST</th>
                @endif
                <th style="width: 12%;" class="text-right">
                    Total (&#8377;)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $index => $item)
                <tr
                    class="{{ $index % 2 == 1 ? 'bg-gray' : '' }}">
                    <td class="text-center">
                        {{ $index + 1 }}</td>
                    <td class="bold text-left">
                        {{ $item->product_name }}</td>
                    <td class="text-center">
                        {{ $item->hsn_code ?: '-' }}</td>
                    <td class="text-center">
                        {{ $item->quantity }}
                        {{ $item->unit }}</td>
                    <td class="text-right">
                        {{ number_format($item->unit_price, 2) }}
                    </td>
                    <td class="text-right">
                        {{ number_format($item->taxable_amount, 2) }}
                    </td>
                    @if ($invoice->is_interstate)
                        <td class="text-right">
                            <span
                                style="font-size: 7px; color: #64748b;">({{ $item->gst_rate }}%)</span><br>
                            {{ number_format($item->igst_amount, 2) }}
                        </td>
                    @else
                        <td class="text-right">
                            <span
                                style="font-size: 7px; color: #64748b;">({{ $item->gst_rate / 2 }}%)</span><br>
                            {{ number_format($item->cgst_amount, 2) }}
                        </td>
                        <td class="text-right">
                            <span
                                style="font-size: 7px; color: #64748b;">({{ $item->gst_rate / 2 }}%)</span><br>
                            {{ number_format($item->sgst_amount, 2) }}
                        </td>
                    @endif
                    <td class="text-right bold">
                        {{ number_format($item->total_amount, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals and Words Calculation -->
    <table class="totals-table">
        <tr>
            <td
                style="width: 58%; vertical-align: top; padding-right: 15px;">
                <div
                    style="border: 1px solid #cbd5e1; padding: 6px; border-radius: 4px; background-color: #f8fafc;">
                    <div
                        style="font-size: 8px; font-weight: bold; text-transform: uppercase; color: #475569;">
                        Total Amount in Words:</div>
                    <div
                        style="font-size: 10px; font-weight: bold; color: #0f172a; margin-top: 3px;">
                        {{ $amountInWords }}
                    </div>
                </div>

                @if ($invoice->business->terms_and_conditions)
                    <div
                        style="margin-top: 10px; font-size: 8px; color: #475569;">
                        <strong>Terms &
                            Conditions:</strong><br>
                        {!! nl2br(
                            e($invoice->business->terms_and_conditions),
                        ) !!}
                    </div>
                @endif
            </td>
            <td style="width: 42%; vertical-align: top;">
                <table
                    style="width: 100%; border: 1px solid #cbd5e1;">
                    <tr>
                        <td class="text-left">Taxable
                            Amount:</td>
                        <td class="text-right bold">&#8377;
                            {{ number_format($invoice->subtotal, 2) }}
                        </td>
                    </tr>
                    @if ($invoice->is_interstate)
                        <tr>
                            <td class="text-left">Total
                                IGST:</td>
                            <td class="text-right bold">
                                &#8377;
                                {{ number_format($invoice->igst_total, 2) }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="text-left">Total
                                CGST:</td>
                            <td class="text-right bold">
                                &#8377;
                                {{ number_format($invoice->cgst_total, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">Total
                                SGST:</td>
                            <td class="text-right bold">
                                &#8377;
                                {{ number_format($invoice->sgst_total, 2) }}
                            </td>
                        </tr>
                    @endif

                    @if ($invoice->shipping_charges > 0)
                        <tr>
                            <td class="text-left">Shipping /
                                Delivery:</td>
                            <td class="text-right bold">
                                &#8377;
                                {{ number_format($invoice->shipping_charges, 2) }}
                            </td>
                        </tr>
                    @endif

                    @if ($invoice->discount_amount > 0)
                        <tr>
                            <td class="text-left"
                                style="color: #dc2626;">
                                Discount:</td>
                            <td class="text-right bold"
                                style="color: #dc2626;">-
                                &#8377;
                                {{ number_format($invoice->discount_amount, 2) }}
                            </td>
                        </tr>
                    @endif

                    <tr class="grand-total-row">
                        <td class="text-left">Grand Total
                            (Incl. GST):</td>
                        <td class="text-right"
                            style="color: #047857;">&#8377;
                            {{ number_format($invoice->grand_total, 2) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Signature Block -->
    <table style="margin-top: 20px;">
        <tr>
            <td
                style="width: 50%; vertical-align: bottom; font-size: 8px; color: #64748b;">
                This is a computer-generated tax invoice
                issued under the Central Goods and Services
                Tax Act, 2017.
            </td>
            <td style="width: 50%; text-align: right;">
                <div
                    style="font-size: 9px; font-weight: bold; margin-bottom: 35px;">
                    For {{ $invoice->business->name }}
                </div>
                <div class="signature-line">Authorised
                    Signatory</div>
            </td>
        </tr>
    </table>

</body>

</html>
