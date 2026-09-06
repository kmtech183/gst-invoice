<?php

namespace App\Jobs;

use App\Contracts\TaxCalculatorInterface;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateInvoicePdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Invoice $invoice
    ) {}

    public function handle(TaxCalculatorInterface $taxCalculator): void
    {
        $this->invoice->load(['customer', 'business', 'items']);
        $amountInWords = $taxCalculator->amountToWords((float)$this->invoice->grand_total);

        $pdf = Pdf::loadView('invoices.pdf', [
            'invoice'       => $this->invoice,
            'amountInWords' => $amountInWords,
        ])->setPaper('a4', 'portrait');

        $fileName = "invoices/{$this->invoice->business_id}/Invoice-{$this->invoice->invoice_number}.pdf";
        Storage::disk('public')->put($fileName, $pdf->output());
    }
}
