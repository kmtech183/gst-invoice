<?php

namespace App\Services;

use App\Contracts\TaxCalculatorInterface;

class GstTaxCalculator implements TaxCalculatorInterface
{
    /**
     * Calculate taxable amount, CGST, SGST, IGST, and total.
     */
    public function calculateItemTax(
        float $unitPrice,
        int $quantity,
        int $gstRate,
        string $sellerStateCode,
        string $buyerStateCode
    ): array {
        $taxableAmount = round($unitPrice * $quantity, 2);
        $isInterstate = ($sellerStateCode !== $buyerStateCode);

        $totalTax = round(($taxableAmount * $gstRate) / 100, 2);

        $cgstAmount = 0.00;
        $sgstAmount = 0.00;
        $igstAmount = 0.00;

        if ($isInterstate) {
            // Inter-state supply -> 100% IGST
            $igstAmount = $totalTax;
        } else {
            // Intra-state supply -> 50% CGST + 50% SGST
            $cgstAmount = round($totalTax / 2, 2);
            $sgstAmount = round($totalTax - $cgstAmount, 2); // Handles odd cent rounding
        }

        $totalAmount = round($taxableAmount + $totalTax, 2);

        return [
            'taxable_amount' => $taxableAmount,
            'is_interstate'  => $isInterstate,
            'gst_rate'       => $gstRate,
            'cgst_amount'    => $cgstAmount,
            'sgst_amount'    => $sgstAmount,
            'igst_amount'    => $igstAmount,
            'total_tax'      => $totalTax,
            'total_amount'   => $totalAmount,
        ];
    }

    /**
     * Convert amount to Indian numbering currency words (Lakhs & Crores).
     */
    public function amountToWords(float $amount): string
    {
        $number = floor($amount);
        $paisa = round(($amount - $number) * 100);

        $words = $this->convertNumberToIndianWords((int)$number) . ' Rupees';

        if ($paisa > 0) {
            $words .= ' and ' . $this->convertNumberToIndianWords((int)$paisa) . ' Paise';
        }

        return $words . ' Only';
    }

    private function convertNumberToIndianWords(int $num): string
    {
        $ones = [
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen'
        ];

        $tens = [
            2 => 'Twenty',
            3 => 'Thirty',
            4 => 'Forty',
            5 => 'Fifty',
            6 => 'Sixty',
            7 => 'Seventy',
            8 => 'Eighty',
            9 => 'Ninety'
        ];

        if ($num < 20) {
            return $ones[$num];
        }

        if ($num < 100) {
            return $tens[(int)($num / 10)] . (($num % 10 != 0) ? ' ' . $ones[$num % 10] : '');
        }

        if ($num < 1000) {
            return $ones[(int)($num / 100)] . ' Hundred' . (($num % 100 != 0) ? ' and ' . $this->convertNumberToIndianWords($num % 100) : '');
        }

        if ($num < 100000) {
            return $this->convertNumberToIndianWords((int)($num / 1000)) . ' Thousand' . (($num % 1000 != 0) ? ' ' . $this->convertNumberToIndianWords($num % 1000) : '');
        }

        if ($num < 10000000) {
            return $this->convertNumberToIndianWords((int)($num / 100000)) . ' Lakh' . (($num % 100000 != 0) ? ' ' . $this->convertNumberToIndianWords($num % 100000) : '');
        }

        return $this->convertNumberToIndianWords((int)($num / 10000000)) . ' Crore' . (($num % 10000000 != 0) ? ' ' . $this->convertNumberToIndianWords($num % 10000000) : '');
    }
}
