<?php

use App\Http\Controllers\Api\InvoiceApiController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:60,1'])->group(function () {
    // Products API
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/low-stock', [ProductController::class, 'lowStock']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // Invoices API
    Route::get('/invoices', [InvoiceApiController::class, 'index']);
    Route::get('/invoices/{invoice}', [InvoiceApiController::class, 'show']);
});
