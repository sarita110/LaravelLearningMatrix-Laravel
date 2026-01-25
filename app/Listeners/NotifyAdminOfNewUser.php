<?php

namespace App\Listeners;

use App\Mail\NewUserRegisteredMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

/**
 * Listens for Laravel's built-in Registered event (already fired by
 * RegisteredUserController) and sends a notification email to every admin.
 *
 * No changes to the controller are needed — we just hook into the event
 * that's already there.
 */
class NotifyAdminOfNewUser
{
    public function handle(Registered $event): void
    {
        $newUser = $event->user;

        // Find all admin users to notify
        $admins = User::where('is_admin', true)->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NewUserRegisteredMail($newUser));
        }
    }
}
