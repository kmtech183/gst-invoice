<?php

namespace App\Contracts;

use App\Models\Business;
use App\Models\Customer;

interface TaxCalculatorInterface
{
    /**
     * Calculate tax breakdown for an item or invoice line.
     *
     * @param float $unitPrice
     * @param int $quantity
     * @param int $gstRate (e.g. 0, 5, 12, 18, 28)
     * @param string $sellerStateCode (2 digits, e.g. "27")
     * @param string $buyerStateCode (2 digits, e.g. "27" or "24")
     * @return array
     */
    public function calculateItemTax(
        float $unitPrice,
        int $quantity,
        int $gstRate,
        string $sellerStateCode,
        string $buyerStateCode
    ): array;

    /**
     * Convert numeric amount to Indian Currency Words (Lakhs / Crores).
     *
     * @param float $amount
     * @return string
     */
    public function amountToWords(float $amount): string;
}
