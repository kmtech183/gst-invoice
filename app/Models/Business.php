<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gstin',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'state_code',
        'pincode',
        'logo_path',
        'invoice_prefix',
        'next_invoice_number',
        'terms_and_conditions'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function categories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
    public function stockMoves(): HasMany
    {
        return $this->hasMany(StockMove::class);
    }
}
