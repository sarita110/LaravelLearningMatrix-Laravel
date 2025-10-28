<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'sort_order',
    ];

    // ─── RELATIONSHIPS ────────────────────────────────────────────

    /** A category has many concepts. */
    public function concepts(): HasMany
    {
        return $this->hasMany(Concept::class);
    }

    // ─── SCOPES ───────────────────────────────────────────────────

    /** Order by sort_order, then name. */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
