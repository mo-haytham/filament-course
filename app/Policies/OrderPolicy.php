<?php

namespace App\Policies;

use App\Models\User;
use Filament\Facades\Filament;

class OrderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user): bool
    {
        if (Filament::getCurrentPanel()->getId() === 'admin') {
            return $user->is_admin == 1;
        }

        if (Filament::getCurrentPanel()->getId() === 'accountant') {
            return $user->is_accountant == 1 || $user->is_admin == 1;
        }

        return false;
    }
}
