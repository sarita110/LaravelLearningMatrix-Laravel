<?php

namespace App\Policies;

use App\Models\Concept;
use App\Models\User;

/**
 * Authorization policy for the Concept model.
 * Registered automatically by Laravel's policy auto-discovery.
 *
 * Usage in controllers:    $this->authorize('update', $concept);
 * Usage in Blade:          @can('update', $concept) ... @endcan
 */
class ConceptPolicy
{
    /**
     * Anyone (including guests) can view published concepts.
     * Admins can view drafts too.
     */
    public function view(?User $user, Concept $concept): bool
    {
        if ($concept->is_published) {
            return true;
        }

        return $user !== null && $user->isAdmin();
    }

    /** Only logged-in users can create concepts. */
    public function create(User $user): bool
    {
        return true; // Any authenticated user
    }

    /** The concept author or any admin may edit. */
    public function update(User $user, Concept $concept): bool
    {
        return $user->id === $concept->created_by
            || $user->isAdmin();
    }

    /** Only admins may delete concepts. */
    public function delete(User $user, Concept $concept): bool
    {
        return $user->isAdmin();
    }
}
