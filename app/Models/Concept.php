<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Concept extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'explanation',
        'code_example',
        'code_language',
        'phase',
        'is_published',
        'category_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'phase'        => 'integer',
            'view_count'   => 'integer',
        ];
    }

    // ─── RELATIONSHIPS ────────────────────────────────────────────

    /** A concept belongs to one category. */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** A concept has many code examples, ordered by their sort order. */
    public function examples(): HasMany
    {
        return $this->hasMany(Example::class)->orderBy('order');
    }

    /** A concept belongs to many tags (through the concept_tag pivot table). */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /** The user who created this concept. */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── LOCAL SCOPES ─────────────────────────────────────────────

    /** Filter to published concepts only. Usage: Concept::published()->get() */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /** Filter by phase number. Usage: Concept::forPhase(1)->get() */
    public function scopeForPhase(Builder $query, int $phase): void
    {
        $query->where('phase', $phase);
    }

    /** Filter by category slug. Usage: Concept::inCategory('routing')->get() */
    public function scopeInCategory(Builder $query, string $slug): void
    {
        $query->whereHas('category', fn ($q) => $q->where('slug', $slug));
    }

    // ─── HELPERS ──────────────────────────────────────────────────

    /** Increment the view counter atomically (safe for concurrent requests). */
    public function incrementViews(): void
    {
        $this->increment('view_count');
    }

    /** Return the route key by slug instead of numeric ID. */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
