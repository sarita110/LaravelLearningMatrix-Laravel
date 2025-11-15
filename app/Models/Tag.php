<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    /** A tag belongs to many concepts (through concept_tag pivot). */
    public function concepts(): BelongsToMany
    {
        return $this->belongsToMany(Concept::class);
    }
}
