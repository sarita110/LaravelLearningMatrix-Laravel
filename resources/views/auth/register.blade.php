@extends('layouts.app')

@section('title', 'Create Account')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">Join the Learning Matrix to contribute your own concepts.</p>

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            {{-- Name --}}
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-input @error('name') input-error @enderror"
                    required
                    autofocus
                    autocomplete="name"
                >
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-input @error('email') input-error @enderror"
                    required
                    autocomplete="username"
                >
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input @error('password') input-error @enderror"
                    required
                    autocomplete="new-password"
                >
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-input"
                    required
                    autocomplete="new-password"
                >
            </div>

            <button type="submit" class="btn btn-primary btn-full">Create Account</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="{{ route('login') }}">Log in here</a>
        </div>

        <div class="learning-note">
            <strong>📚 What's happening here?</strong>
            <ul>
                <li><code>Rules\Password::defaults()</code> — enforces strong password rules (min 8 chars, mixed case, etc.).</li>
                <li><code>Hash::make($request->password)</code> — bcrypt-hashes the password before storing in the DB. Never plain text.</li>
                <li><code>unique:users</code> — validation rule that queries the DB to ensure the email doesn't already exist.</li>
                <li>The <code>confirmed</code> rule checks that <code>password</code> matches <code>password_confirmation</code> automatically.</li>
                <li><code>event(new Registered($user))</code> — fires Laravel's built-in Registered event (enables email verification if configured).</li>
            </ul>
        </div>
    </div>
</div>

<style>
.auth-container { display:flex; justify-content:center; padding:3rem 1rem; min-height:70vh; }
.auth-card { background:#fff; border:1px solid #e5e7eb; border-radius:.75rem; padding:2.5rem; width:100%; max-width:460px; box-shadow:0 4px 16px rgba(0,0,0,.06); }
.auth-title { font-size:1.6rem; font-weight:700; color:#1f2937; margin:0 0 .25rem; }
.auth-subtitle { color:#6b7280; margin:0 0 2rem; }
.form-group { margin-bottom:1.25rem; }
.form-label { display:block; font-size:.875rem; font-weight:600; color:#374151; margin-bottom:.4rem; }
.form-input { width:100%; padding:.6rem .75rem; border:1px solid #d1d5db; border-radius:.375rem; font-size:1rem; color:#1f2937; box-sizing:border-box; transition:border-color .15s; }
.form-input:focus { outline:none; border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.1); }
.input-error { border-color:#ef4444; }
.form-error { color:#ef4444; font-size:.8rem; margin:.3rem 0 0; }
.btn-full { width:100%; }
.auth-footer { text-align:center; margin-top:1.5rem; font-size:.875rem; color:#6b7280; }
.auth-footer a { color:#6366f1; font-weight:500; }
.learning-note { background:#eff6ff; border:1px solid #bfdbfe; border-radius:.5rem; padding:1rem 1.25rem; margin-top:2rem; font-size:.82rem; color:#1e40af; }
.learning-note ul { margin:.5rem 0 0; padding-left:1.2rem; line-height:1.8; }
.learning-note code { background:#dbeafe; padding:.1rem .3rem; border-radius:3px; font-family:monospace; }
</style>
@endsection
