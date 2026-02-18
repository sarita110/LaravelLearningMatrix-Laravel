<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the User model's helper methods.
 *
 * These tests do NOT touch the database — they instantiate plain User objects
 * and assert that the methods return the correct value based on attribute state.
 *
 * Run just these tests:
 *   php artisan test --testsuite=Unit
 *   php artisan test tests/Unit/UserTest.php
 */
class UserTest extends TestCase
{
    // ── isAdmin() ─────────────────────────────────────────────────────────────

    public function test_is_admin_returns_true_when_flag_is_set(): void
    {
        $user = new User(['is_admin' => true]);

        $this->assertTrue($user->isAdmin());
    }

    public function test_is_admin_returns_false_by_default(): void
    {
        $user = new User(['is_admin' => false]);

        $this->assertFalse($user->isAdmin());
    }

    // ── isApproved() ──────────────────────────────────────────────────────────

    public function test_is_approved_returns_true_when_approved(): void
    {
        $user = new User(['is_approved' => true]);

        $this->assertTrue($user->isApproved());
    }

    public function test_is_approved_returns_false_for_pending_accounts(): void
    {
        $user = new User(['is_approved' => false]);

        $this->assertFalse($user->isApproved());
    }

    // ── A regular user is neither admin nor approved by default ───────────────

    public function test_new_user_is_not_admin_and_not_approved(): void
    {
        $user = new User([
            'name'        => 'Jane Doe',
            'email'       => 'jane@example.com',
            'is_admin'    => false,
            'is_approved' => false,
        ]);

        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isApproved());
    }
}
