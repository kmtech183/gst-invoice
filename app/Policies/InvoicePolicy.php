<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    /**
     * Determine whether the user can view any invoices.
     */
    public function viewAny(User $user): bool
    {
        return $user->business_id !== null;
    }

    /**
     * Determine whether the user can view the invoice.
     */
    public function view(User $user, Invoice $invoice): bool
    {
        return $user->business_id === $invoice->business_id;
    }

    /**
     * Determine whether the user can create invoices.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'sales') && $user->business_id !== null;
    }

    /**
     * Determine whether the user can update the invoice.
     * Only allow updates if invoice is still in 'draft' status.
     */
    public function update(User $user, Invoice $invoice): bool
    {
        if ($user->business_id !== $invoice->business_id) {
            return false;
        }

        // Once an invoice is 'paid' or 'sent', only admin/accountant can edit notes, but items cannot be altered
        if ($invoice->status === 'draft') {
            return $user->hasRole('admin', 'sales');
        }

        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can cancel or delete the invoice.
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->hasRole('admin') && $user->business_id === $invoice->business_id;
    }

    /**
     * Determine whether the user can download or stream the PDF.
     */
    public function viewPdf(User $user, Invoice $invoice): bool
    {
        return $user->business_id === $invoice->business_id;
    }

    /**
     * Determine whether the user can record payment on an invoice.
     */
    public function recordPayment(User $user, Invoice $invoice): bool
    {
        return $user->hasRole('admin', 'accountant') && $user->business_id === $invoice->business_id;
    }
}
