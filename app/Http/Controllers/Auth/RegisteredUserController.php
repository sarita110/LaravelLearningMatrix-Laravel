<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration form.
     *
     * 📚 GET /register
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * 📚 POST /register
     *
     * Rules\Password::defaults() enforces strong password requirements.
     * Hash::make() bcrypt-hashes the password — never stored as plain text.
     * event(new Registered($user)) fires Laravel's built-in event, which
     * can trigger email verification if you enable it later.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'is_approved' => false,  // must be approved by admin before login
        ]);

        event(new Registered($user));

        // Do NOT auto-login — redirect to login with a pending message.
        return redirect()->route('login')
            ->with('status', 'Registration successful! Your account is pending admin approval. You will receive access once an admin approves your account.');
    }
}
