<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'company_name',
        'gstin',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'state_code'
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
