<?php

use App\Http\Controllers\Api\ConceptApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// ─── TOKEN ISSUANCE ──────────────────────────────────────────────────────────
// POST /api/tokens/create
// Body: { "email": "...", "password": "..." }
// Returns: { "token": "1|abc123..." }
Route::post('/tokens/create', function (Request $request) {
    $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials.'], 401);
    }

    // Revoke old tokens for this device name (optional clean-up)
    $user->tokens()->where('name', 'api-token')->delete();

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token, 'user' => $user->name]);
});

// ─── TOKEN REVOCATION ────────────────────────────────────────────────────────
// DELETE /api/tokens/revoke   [auth:sanctum]
Route::middleware('auth:sanctum')->delete('/tokens/revoke', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Token revoked.']);
});

// ─── PUBLIC CONCEPT ENDPOINTS ────────────────────────────────────────────────
// GET /api/concepts              — list (supports ?phase=2 ?search=x ?category=slug)
// GET /api/concepts/{slug}       — single concept with all relationships
Route::get('/concepts',           [ConceptApiController::class, 'index']);
Route::get('/concepts/{concept}', [ConceptApiController::class, 'show']);

// ─── PROTECTED CONCEPT ENDPOINTS ─────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/concepts',             [ConceptApiController::class, 'store']);
    Route::put('/concepts/{concept}',    [ConceptApiController::class, 'update']);
    Route::delete('/concepts/{concept}', [ConceptApiController::class, 'destroy']);
});

// ─── CURRENT USER ─────────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'id'       => $request->user()->id,
        'name'     => $request->user()->name,
        'email'    => $request->user()->email,
        'is_admin' => $request->user()->is_admin,
    ]);
});
