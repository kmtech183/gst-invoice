<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Determine whether the user can view any products.
     */
    public function viewAny(User $user): bool
    {
        return $user->business_id !== null;
    }

    /**
     * Determine whether the user can view the product.
     */
    public function view(User $user, Product $product): bool
    {
        return $user->business_id === $product->business_id;
    }

    /**
     * Determine whether the user can create products.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'sales') && $user->business_id !== null;
    }

    /**
     * Determine whether the user can update the product.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->hasRole('admin', 'sales') && $user->business_id === $product->business_id;
    }

    /**
     * Determine whether the user can delete the product.
     * Only Admin can delete products.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->hasRole('admin') && $user->business_id === $product->business_id;
    }
}
