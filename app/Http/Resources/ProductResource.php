<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'sku'            => $this->sku,
            'hsn_code'       => $this->hsn_code,
            'purchase_price' => (float)$this->purchase_price,
            'selling_price'  => (float)$this->selling_price,
            'gst_rate'       => (int)$this->gst_rate,
            'stock'          => (int)$this->stock,
            'is_low_stock'   => $this->stock <= $this->reorder_level,
            'unit'           => $this->unit,
            'is_active'      => (bool)$this->is_active,
            'category'       => $this->whenLoaded('category', fn() => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
            'created_at'     => $this->created_at->toIso8601String(),
        ];
    }
}
