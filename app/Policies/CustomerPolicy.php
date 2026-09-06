<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->business_id !== null;
    }

    public function view(User $user, Customer $customer): bool
    {
        return $user->business_id === $customer->business_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'sales') && $user->business_id !== null;
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->hasRole('admin', 'sales') && $user->business_id === $customer->business_id;
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->hasRole('admin') && $user->business_id === $customer->business_id;
    }
}
