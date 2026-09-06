<?php

use App\Services\GstTaxCalculator;

it('correctly splits intra-state supply into 50% CGST and 50% SGST', function () {
    $calculator = new GstTaxCalculator();

    // Seller: Maharashtra (27), Buyer: Maharashtra (27), Rate: 18%, Qty: 2, Price: 1000
    $result = $calculator->calculateItemTax(1000.00, 2, 18, '27', '27');

    expect($result['taxable_amount'])->toBe(2000.00)
        ->and($result['is_interstate'])->toBeFalse()
        ->and($result['cgst_amount'])->toBe(180.00)
        ->and($result['sgst_amount'])->toBe(180.00)
        ->and($result['igst_amount'])->toBe(0.00)
        ->and($result['total_tax'])->toBe(360.00)
        ->and($result['total_amount'])->toBe(2360.00);
});

it('correctly applies 100% IGST for inter-state supply', function () {
    $calculator = new GstTaxCalculator();

    // Seller: Maharashtra (27), Buyer: Gujarat (24), Rate: 18%, Qty: 1, Price: 5000
    $result = $calculator->calculateItemTax(5000.00, 1, 18, '27', '24');

    expect($result['taxable_amount'])->toBe(5000.00)
        ->and($result['is_interstate'])->toBeTrue()
        ->and($result['cgst_amount'])->toBe(0.00)
        ->and($result['sgst_amount'])->toBe(0.00)
        ->and($result['igst_amount'])->toBe(900.00)
        ->and($result['total_tax'])->toBe(900.00)
        ->and($result['total_amount'])->toBe(5900.00);
});

it('correctly formats Indian Rupee numbers to words with Lakhs and Crores', function () {
    $calculator = new GstTaxCalculator();

    $words1 = $calculator->amountToWords(150000.00);
    expect($words1)->toBe('One Lakh Fifty Thousand Rupees Only');

    $words2 = $calculator->amountToWords(2450.75);
    expect($words2)->toBe('Two Thousand Four Hundred and Fifty Rupees and Seventy Five Paise Only');
});
