<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'invoice_number'   => $this->invoice_number,
            'invoice_date'     => $this->invoice_date->format('Y-m-d'),
            'due_date'         => $this->due_date->format('Y-m-d'),
            'place_of_supply'  => $this->place_of_supply,
            'is_interstate'    => (bool)$this->is_interstate,
            'financials'       => [
                'subtotal'         => (float)$this->subtotal,
                'cgst_total'       => (float)$this->cgst_total,
                'sgst_total'       => (float)$this->sgst_total,
                'igst_total'       => (float)$this->igst_total,
                'total_gst'        => (float)$this->total_gst,
                'shipping_charges' => (float)$this->shipping_charges,
                'discount_amount'  => (float)$this->discount_amount,
                'grand_total'      => (float)$this->grand_total,
            ],
            'status'           => $this->status,
            'payment_mode'     => $this->payment_mode,
            'customer'         => $this->whenLoaded('customer', fn() => [
                'id'           => $this->customer->id,
                'name'         => $this->customer->name,
                'gstin'        => $this->customer->gstin,
                'state'        => $this->customer->state,
            ]),
            'items_count'      => $this->whenCounted('items'),
            'created_at'       => $this->created_at->toIso8601String(),
        ];
    }
}
