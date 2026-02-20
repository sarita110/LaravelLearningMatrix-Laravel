<?php

namespace Tests\Feature;

use App\Mail\NewUserRegisteredMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Feature tests for the user registration flow.
 *
 * These tests make real HTTP requests against the application, run migrations
 * on a fresh in-memory SQLite database (via RefreshDatabase), and assert on
 * the response and the database state.
 *
 * Run just these tests:
 *   php artisan test tests/Feature/RegistrationTest.php
 */
class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    // ── Registration form ─────────────────────────────────────────────────────

    public function test_registration_page_is_accessible(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    // ── Successful registration ───────────────────────────────────────────────

    public function test_new_user_can_register_and_is_redirected_to_login(): void
    {
        Mail::fake(); // Intercept emails so nothing is actually sent

        $response = $this->post('/register', [
            'name'                  => 'Alice Smith',
            'email'                 => 'alice@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_registered_user_is_saved_to_the_database(): void
    {
        Mail::fake();

        $this->post('/register', [
            'name'                  => 'Bob Jones',
            'email'                 => 'bob@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'bob@example.com']);
    }

    public function test_newly_registered_user_starts_as_unapproved(): void
    {
        Mail::fake();

        $this->post('/register', [
            'name'                  => 'Carol White',
            'email'                 => 'carol@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $this->assertDatabaseHas('users', [
            'email'       => 'carol@example.com',
            'is_approved' => false,
        ]);
    }

    // ── Admin email notification on registration ──────────────────────────────

    public function test_admin_receives_email_when_new_user_registers(): void
    {
        Mail::fake();

        // Create an admin who should be notified
        User::factory()->create([
            'email'       => 'admin@example.com',
            'is_admin'    => true,
            'is_approved' => true,
        ]);

        $this->post('/register', [
            'name'                  => 'Dave New',
            'email'                 => 'dave@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        Mail::assertSent(NewUserRegisteredMail::class, function ($mail) {
            return $mail->hasTo('admin@example.com');
        });
    }

    public function test_no_email_is_sent_when_there_are_no_admins(): void
    {
        Mail::fake();

        // No admin users exist in the DB

        $this->post('/register', [
            'name'                  => 'Eve Solo',
            'email'                 => 'eve@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        Mail::assertNotSent(NewUserRegisteredMail::class);
    }

    // ── Validation ────────────────────────────────────────────────────────────

    public function test_registration_fails_when_email_is_already_taken(): void
    {
        Mail::fake();

        User::factory()->create(['email' => 'taken@example.com', 'is_approved' => true]);

        $response = $this->post('/register', [
            'name'                  => 'Frank Copy',
            'email'                 => 'taken@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
