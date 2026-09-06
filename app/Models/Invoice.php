<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'customer_id',
        'created_by',
        'invoice_number',
        'invoice_date',
        'due_date',
        'place_of_supply',
        'is_interstate',
        'subtotal',
        'cgst_total',
        'sgst_total',
        'igst_total',
        'total_gst',
        'shipping_charges',
        'discount_amount',
        'grand_total',
        'status',
        'payment_mode',
        'notes'
    ];

    protected function casts(): array
    {
        return [
            'invoice_date'     => 'date',
            'due_date'         => 'date',
            'is_interstate'    => 'boolean',
            'subtotal'         => 'decimal:2',
            'cgst_total'       => 'decimal:2',
            'sgst_total'       => 'decimal:2',
            'igst_total'       => 'decimal:2',
            'total_gst'        => 'decimal:2',
            'shipping_charges' => 'decimal:2',
            'discount_amount'  => 'decimal:2',
            'grand_total'      => 'decimal:2',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Scopes
    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'paid');
    }
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }
    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('invoice_date', now()->month)
            ->whereYear('invoice_date', now()->year);
    }
}
