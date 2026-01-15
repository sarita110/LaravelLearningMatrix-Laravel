<?php

namespace App\Repositories;

use App\Models\Concept;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * The Eloquent (database) implementation of ConceptRepositoryInterface.
 * All raw Eloquent queries live here — controllers stay query-free.
 */
class EloquentConceptRepository implements ConceptRepositoryInterface
{
    public function allPublished(int $perPage = 12): LengthAwarePaginator
    {
        return Concept::with('category')
            ->published()
            ->orderBy('phase')
            ->orderBy('title')
            ->paginate($perPage);
    }

    public function findBySlug(string $slug): Concept
    {
        return Concept::with(['category', 'examples', 'tags', 'author'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function forPhase(int $phase, int $perPage = 12): LengthAwarePaginator
    {
        return Concept::with('category')
            ->published()
            ->forPhase($phase)
            ->orderBy('title')
            ->paginate($perPage);
    }

    public function create(array $data): Concept
    {
        return Concept::create($data);
    }

    public function update(Concept $concept, array $data): bool
    {
        return $concept->update($data);
    }

    public function delete(Concept $concept): bool
    {
        return (bool) $concept->delete();
    }
}
