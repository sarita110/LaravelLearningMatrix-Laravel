<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_approved',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
            'is_approved'       => 'boolean',
        ];
    }

    // ─── RELATIONSHIPS ────────────────────────────────────────────

    public function concepts(): HasMany
    {
        return $this->hasMany(Concept::class, 'created_by');
    }

    // ─── HELPERS ──────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    public function isApproved(): bool
    {
        return $this->is_approved === true;
    }

    /** Returns the public URL for the user's avatar, or a UI-Avatars fallback. */
    public function avatarUrl(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        $initials = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$initials}&size=200&background=1B2D50&color=fff&bold=true";
    }
}
