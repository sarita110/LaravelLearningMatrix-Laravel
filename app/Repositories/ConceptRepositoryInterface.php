<?php

namespace App\Repositories;

use App\Models\Concept;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * The contract for all Concept repositories.
 * Binding an interface means we can swap EloquentConceptRepository
 * for a CacheConceptRepository or an InMemoryConceptRepository
 * (for tests) without touching any controller.
 */
interface ConceptRepositoryInterface
{
    public function allPublished(int $perPage = 12): LengthAwarePaginator;

    public function findBySlug(string $slug): Concept;

    public function forPhase(int $phase, int $perPage = 12): LengthAwarePaginator;

    public function create(array $data): Concept;

    public function update(Concept $concept, array $data): bool;

    public function delete(Concept $concept): bool;
}
