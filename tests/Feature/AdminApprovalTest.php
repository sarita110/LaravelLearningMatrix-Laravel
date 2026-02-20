<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Integration tests for the admin approval workflow.
 *
 * These tests exercise the full stack: HTTP request → middleware → controller
 * → database → HTTP response. They verify the approval gate that sits between
 * registration and first login.
 *
 * Run just these tests:
 *   php artisan test tests/Feature/AdminApprovalTest.php
 */
class AdminApprovalTest extends TestCase
{
    use RefreshDatabase;

    // ── Login gate ────────────────────────────────────────────────────────────

    public function test_unapproved_user_cannot_log_in(): void
    {
        $user = User::factory()->create([
            'password'    => bcrypt('Password1!'),
            'is_approved' => false,
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'Password1!',
        ]);

        // Should not be authenticated
        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_approved_user_can_log_in(): void
    {
        $user = User::factory()->create([
            'password'    => bcrypt('Password1!'),
            'is_approved' => true,
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'Password1!',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }

    // ── Admin panel ───────────────────────────────────────────────────────────

    public function test_admin_can_view_pending_users_page(): void
    {
        $admin = User::factory()->create([
            'is_admin'    => true,
            'is_approved' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_panel(): void
    {
        $regularUser = User::factory()->create([
            'is_admin'    => false,
            'is_approved' => true,
        ]);

        $response = $this->actingAs($regularUser)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_guest_is_redirected_away_from_admin_panel(): void
    {
        $response = $this->get('/admin/users');

        $response->assertRedirect('/login');
    }

    // ── Approval action ───────────────────────────────────────────────────────

    public function test_admin_can_approve_a_pending_user(): void
    {
        $admin = User::factory()->create([
            'is_admin'    => true,
            'is_approved' => true,
        ]);

        $pendingUser = User::factory()->create(['is_approved' => false]);

        $this->actingAs($admin)
             ->post("/admin/users/{$pendingUser->id}/approve");

        $this->assertDatabaseHas('users', [
            'id'          => $pendingUser->id,
            'is_approved' => true,
        ]);
    }

    public function test_after_approval_user_can_log_in(): void
    {
        $admin = User::factory()->create([
            'is_admin'    => true,
            'is_approved' => true,
        ]);

        $pendingUser = User::factory()->create([
            'password'    => bcrypt('Password1!'),
            'is_approved' => false,
        ]);

        // Admin approves the user
        $this->actingAs($admin)
             ->post("/admin/users/{$pendingUser->id}/approve");

        // End the admin session before testing the pending user's login
        auth()->logout();

        // Now the user can log in
        $this->post('/login', [
            'email'    => $pendingUser->email,
            'password' => 'Password1!',
        ]);

        $this->assertAuthenticatedAs($pendingUser);
    }
}
