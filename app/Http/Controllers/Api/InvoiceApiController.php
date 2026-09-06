<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InvoiceApiController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $businessId = $request->user()?->business_id ?? 1;

        $invoices = Invoice::where('business_id', $businessId)
            ->with('customer')
            ->withCount('items')
            ->latest('invoice_date')
            ->cursorPaginate(15);

        return InvoiceResource::collection($invoices);
    }

    public function show(Request $request, Invoice $invoice): InvoiceResource
    {
        $invoice->load(['customer', 'items']);
        return new InvoiceResource($invoice);
    }
}
