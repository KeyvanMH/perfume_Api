<?php

namespace App\Policies;

use App\Models\PerfumeBasedFactor;
use App\Models\User;

class PerfumeBasedFactorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PerfumeBasedFactor $perfumeBasedFactor): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PerfumeBasedFactor $perfumeBasedFactor): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PerfumeBasedFactor $perfumeBasedFactor): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PerfumeBasedFactor $perfumeBasedFactor): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PerfumeBasedFactor $perfumeBasedFactor): bool
    {
        //
    }
}
