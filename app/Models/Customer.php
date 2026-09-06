<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'company_name',
        'gstin',
        'email',
        'phone',
        'billing_address',
        'city',
        'state',
        'state_code',
        'pincode'
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
