<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    /** GET /admin/users — list all users, pending ones first */
    public function users(): View
    {
        $pending  = User::where('is_approved', false)->orderBy('created_at')->get();
        $approved = User::where('is_approved', true)->orderBy('name')->get();

        return view('admin.users', compact('pending', 'approved'));
    }

    /** POST /admin/users/{user}/approve — approve a pending account */
    public function approve(User $user): RedirectResponse
    {
        $user->update(['is_approved' => true]);

        return back()->with('success', "{$user->name}'s account has been approved. They can now log in.");
    }
}
