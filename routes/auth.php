<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| These routes handle guest actions (login/register) and auth actions
| (logout). They are included from routes/web.php.
|
| 📚 Learning note: Route::middleware('guest') prevents logged-in users
| from visiting the login/register pages — they get redirected to home.
*/

// Guest-only routes (redirect to home if already logged in)
Route::middleware('guest')->group(function () {
    Route::get('register',  [RegisteredUserController::class,      'create'])->name('register');
    Route::post('register', [RegisteredUserController::class,      'store']);

    Route::get('login',     [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login',    [AuthenticatedSessionController::class, 'store']);
});

// Auth-only routes (must be logged in)
Route::middleware('auth')->group(function () {
    Route::post('logout',   [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
