<?php

namespace App\Jobs;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInvoiceEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Number of seconds to wait before retrying the job.
     */
    public int $backoff = 60;

    public function __construct(
        public Invoice $invoice
    ) {}

    public function handle(): void
    {
        $this->invoice->load(['customer', 'business', 'items']);

        $recipientEmail = $this->invoice->customer->email;

        if (empty($recipientEmail)) {
            Log::info("Skipping email for invoice #{$this->invoice->invoice_number}: Customer has no email address.");
            return;
        }

        Mail::to($recipientEmail)->send(new InvoiceMail($this->invoice));

        Log::info("GST Invoice #{$this->invoice->invoice_number} successfully emailed to {$recipientEmail}");
    }
}
