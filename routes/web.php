<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConceptController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── HOME ─────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    $stats = [
        'total'     => \App\Models\Concept::published()->count(),
        'phases'    => 7,
        'categories'=> \App\Models\Category::count(),
    ];
    return view('home', compact('stats'));
})->name('home');

// ─── PUBLIC CONCEPT ROUTES ────────────────────────────────────────────────────
Route::get('/concepts', [ConceptController::class, 'index'])->name('concepts.index');

// ─── PROTECTED CONCEPT ROUTES (must be logged in) ─────────────────────────────
// 📚 Learning note: /concepts/create MUST be defined BEFORE /concepts/{concept}
// Otherwise the wildcard {concept} captures "create" as a slug → 404 error.
Route::middleware('auth')->group(function () {
    Route::get('/concepts/create', [ConceptController::class, 'create'])
        ->name('concepts.create');

    Route::post('/concepts', [ConceptController::class, 'store'])
        ->name('concepts.store');

    Route::get('/concepts/{concept}/edit', [ConceptController::class, 'edit'])
        ->name('concepts.edit');

    Route::put('/concepts/{concept}', [ConceptController::class, 'update'])
        ->name('concepts.update');

    Route::delete('/concepts/{concept}', [ConceptController::class, 'destroy'])
        ->name('concepts.destroy');
});

// The wildcard {concept} route goes LAST so it never shadows static paths above.
Route::get('/concepts/{concept}', [ConceptController::class, 'show'])
    ->name('concepts.show')
    ->middleware('track.concept.view'); // Custom middleware — logs each view

// ─── PROFILE ROUTES ───────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/',             [ProfileController::class, 'show'])          ->name('show');
    Route::put('/',             [ProfileController::class, 'update'])        ->name('update');
    Route::post('/avatar',      [ProfileController::class, 'updateAvatar'])  ->name('avatar');
    Route::put('/password',     [ProfileController::class, 'updatePassword'])->name('password');
});

// ─── ADMIN ROUTES ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/approve', [AdminController::class, 'approve'])->name('users.approve');
});

// ─── AUTH ROUTES (provided by Laravel Breeze) ─────────────────────────────────
require __DIR__.'/auth.php';
