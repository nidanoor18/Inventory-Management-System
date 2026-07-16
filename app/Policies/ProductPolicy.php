<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Product $product): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        // Both admins and staff can create/manage products day-to-day.
        return in_array($user->role, ['admin', 'staff'], true);
    }

    public function update(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin', 'staff'], true);
    }

    public function delete(User $user, Product $product): bool
    {
        // Deleting a product is destructive; restrict to admins.
        return $user->isAdmin();
    }
}
