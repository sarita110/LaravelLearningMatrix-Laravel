<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Example extends Model
{
    use HasFactory;

    protected $fillable = [
        'concept_id',
        'title',
        'description',
        'code',
        'language',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    /** An example belongs to one concept. */
    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }
}
