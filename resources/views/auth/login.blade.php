@extends('layouts.app')

@section('title', 'Log In')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1 class="auth-title">Welcome Back</h1>
        <p class="auth-subtitle">Log in to create and manage Laravel concepts.</p>

        @if(session('status'))
            <div style="background:#D4EDDA; color:#155724; border:1px solid #C3E6CB; border-radius:6px; padding:12px 14px; margin-bottom:1rem; font-size:.9rem;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

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
                    autofocus
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
                    autocomplete="current-password"
                >
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="form-group form-check-group">
                <label class="form-check-label">
                    <input type="checkbox" name="remember" id="remember">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Log In</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="{{ route('register') }}">Register here</a>
        </div>

        <div class="learning-note">
            <strong>📚 What's happening here?</strong>
            <ul>
                <li><code>@csrf</code> — injects a hidden token to prevent cross-site request forgery attacks.</li>
                <li><code>old('email')</code> — repopulates the field after a failed validation attempt.</li>
                <li><code>@@error('email')</code> — displays the validation error message for this specific field.</li>
                <li><code>Auth::attempt()</code> — in the controller, checks credentials against the bcrypt hash in the DB.</li>
                <li><code>$request->session()->regenerate()</code> — prevents session-fixation attacks after login.</li>
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
.form-check-group { margin-bottom:1.5rem; }
.form-check-label { display:flex; align-items:center; gap:.5rem; font-size:.875rem; color:#374151; cursor:pointer; }
.btn-full { width:100%; }
.auth-footer { text-align:center; margin-top:1.5rem; font-size:.875rem; color:#6b7280; }
.auth-footer a { color:#6366f1; font-weight:500; }
.learning-note { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:.5rem; padding:1rem 1.25rem; margin-top:2rem; font-size:.82rem; color:#166534; }
.learning-note ul { margin:.5rem 0 0; padding-left:1.2rem; line-height:1.8; }
.learning-note code { background:#dcfce7; padding:.1rem .3rem; border-radius:3px; font-family:monospace; }
</style>
@endsection
