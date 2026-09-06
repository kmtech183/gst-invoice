<?php

namespace App\Services;

use App\Contracts\InvoiceNumberGeneratorInterface;
use App\Models\Business;
use Illuminate\Support\Facades\DB;

class SequentialInvoiceNumberGenerator implements InvoiceNumberGeneratorInterface
{
    /**
     * Atomically generate formatted invoice number: e.g. "APX/24-25/00101".
     */
    public function generate(Business $business): string
    {
        return DB::transaction(function () use ($business) {
            // Lock the business record for atomic increment
            $lockedBusiness = Business::where('id', $business->id)->lockForUpdate()->first();

            $nextNumber = $lockedBusiness->next_invoice_number;
            $prefix = $lockedBusiness->invoice_prefix ?: 'INV-';

            // Pad with leading zeros (e.g. 00101)
            $formattedNumber = $prefix . str_pad((string)$nextNumber, 5, '0', STR_PAD_LEFT);

            // Increment for next time
            $lockedBusiness->increment('next_invoice_number');

            return $formattedNumber;
        });
    }
}
