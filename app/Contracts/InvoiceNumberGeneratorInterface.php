<?php

namespace App\Contracts;

use App\Models\Business;

interface InvoiceNumberGeneratorInterface
{
    /**
     * Generate the next sequential unique invoice number for a given business.
     *
     * @param Business $business
     * @return string
     */
    public function generate(Business $business): string;
}
