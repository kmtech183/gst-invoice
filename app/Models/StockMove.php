<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMove extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'product_id',
        'user_id',
        'type',
        'quantity',
        'balance_after',
        'reference_type',
        'reference_id',
        'notes'
    ];

    protected function casts(): array
    {
        return [
            'quantity'      => 'integer',
            'balance_after' => 'integer',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
