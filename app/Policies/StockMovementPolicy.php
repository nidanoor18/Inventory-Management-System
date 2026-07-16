<?php

namespace App\Policies;

use App\Models\StockMovement;
use App\Models\User;

class StockMovementPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, StockMovement $stockMovement): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        // Both roles record stock movements (receiving, dispatching, adjustments).
        return in_array($user->role, ['admin', 'staff'], true);
    }

    public function delete(User $user, StockMovement $stockMovement): bool
    {
        // Deleting a movement rewrites history; restrict to admins.
        return $user->isAdmin();
    }
}
