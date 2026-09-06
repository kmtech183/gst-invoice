<?php

namespace App\Mail;

use App\Contracts\TaxCalculatorInterface;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invoice $invoice
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "GST Tax Invoice #{$this->invoice->invoice_number} from {$this->invoice->business->name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice-notification',
            with: [
                'invoice' => $this->invoice,
            ],
        );
    }

    /**
     * Attach the generated GST PDF directly in-memory without polluting disk storage.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $this->invoice->load(['customer', 'business', 'items']);
        $amountInWords = app(TaxCalculatorInterface::class)->amountToWords((float)$this->invoice->grand_total);

        $pdf = Pdf::loadView('invoices.pdf', [
            'invoice'       => $this->invoice,
            'amountInWords' => $amountInWords,
        ])->setPaper('a4', 'portrait');

        $safeFilename = str_replace(['/', '\\'], '-', $this->invoice->invoice_number);

        return [
            Attachment::fromData(fn() => $pdf->output(), "Invoice-{$safeFilename}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
