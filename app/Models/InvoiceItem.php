<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'product_name',
        'hsn_code',
        'quantity',
        'unit',
        'unit_price',
        'gst_rate',
        'taxable_amount',
        'cgst_amount',
        'sgst_amount',
        'igst_amount',
        'total_amount'
    ];

    protected function casts(): array
    {
        return [
            'quantity'       => 'integer',
            'unit_price'     => 'decimal:2',
            'gst_rate'       => 'integer',
            'taxable_amount' => 'decimal:2',
            'cgst_amount'    => 'decimal:2',
            'sgst_amount'    => 'decimal:2',
            'igst_amount'    => 'decimal:2',
            'total_amount'   => 'decimal:2',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
