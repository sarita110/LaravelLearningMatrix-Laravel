<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Base Controller
 *
 * All application controllers extend this class.
 *
 * 📚 Learning note:
 * AuthorizesRequests provides $this->authorize() in any controller,
 * which checks the matching Policy method before allowing the action.
 *
 * Example usage in ConceptController:
 *   $this->authorize('update', $concept);
 *   → runs ConceptPolicy::update($user, $concept) and throws a
 *     403 AuthorizationException if it returns false.
 *
 * In Laravel 11 this trait is no longer included automatically —
 * we opt in here to keep all controllers clean.
 */
abstract class Controller
{
    use AuthorizesRequests;
}
