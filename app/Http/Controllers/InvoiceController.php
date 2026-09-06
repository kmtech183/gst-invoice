<?php

namespace App\Http\Controllers;

use App\Contracts\TaxCalculatorInterface;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class InvoiceController extends Controller
{
    public function index()
    {
        $businessId = Auth::user()->business_id;
        $invoices = Invoice::where('business_id', $businessId)
            ->with('customer')
            ->latest('invoice_date')
            ->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('invoices.create');
    }

    public function show(Invoice $invoice)
    {
        Gate::authorize('view', $invoice);

        $invoice->load(['customer', 'business', 'items.product']);
        $amountInWords = app(TaxCalculatorInterface::class)->amountToWords((float)$invoice->grand_total);

        return view('invoices.show', compact('invoice', 'amountInWords'));
    }

    public function downloadPdf(Invoice $invoice)
    {
        Gate::authorize('viewPdf', $invoice);

        $invoice->load(['customer', 'business', 'items']);
        $amountInWords = app(TaxCalculatorInterface::class)->amountToWords((float)$invoice->grand_total);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'amountInWords'))
            ->setPaper('a4', 'portrait');

        // Sanitize invoice number for HTTP Content-Disposition header
        $safeFilename = str_replace(['/', '\\'], '-', $invoice->invoice_number);
        return $pdf->download("Invoice-{$safeFilename}.pdf");
    }
}
