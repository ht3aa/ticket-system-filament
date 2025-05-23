<?php

namespace App\Policies;

use App\Models\ProjectLabel;
use App\Models\User;

class ProjectLabelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProjectLabel $projectLabel): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProjectLabel $projectLabel): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjectLabel $projectLabel): bool
    {
        return ! $projectLabel->hasChildren();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProjectLabel $projectLabel): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProjectLabel $projectLabel): bool
    {
        return true;
    }

    public function hasChildren(User $user, ProjectLabel $projectLabel): bool
    {
        return $projectLabel->hasChildren();
    }
}
