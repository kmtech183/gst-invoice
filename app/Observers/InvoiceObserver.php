<?php

namespace App\Observers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Cache;

class InvoiceObserver
{
    /**
     * Invalidate cached stats whenever an invoice is created.
     */
    public function created(Invoice $invoice): void
    {
        $this->flushBusinessCache($invoice->business_id);
    }

    /**
     * Invalidate cached stats whenever an invoice is updated.
     */
    public function updated(Invoice $invoice): void
    {
        $this->flushBusinessCache($invoice->business_id);
    }

    /**
     * Invalidate cached stats whenever an invoice is deleted.
     */
    public function deleted(Invoice $invoice): void
    {
        $this->flushBusinessCache($invoice->business_id);
    }

    private function flushBusinessCache(int $businessId): void
    {
        Cache::forget("business.{$businessId}.dashboard_stats");
        Cache::forget("business.{$businessId}.recent_invoices");
    }
}
