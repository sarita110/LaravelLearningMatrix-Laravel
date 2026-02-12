<?php

namespace App\Http\Controllers\Api;

use App\Events\ConceptPublished;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConceptRequest;
use App\Http\Requests\UpdateConceptRequest;
use App\Http\Resources\ConceptResource;
use App\Models\Concept;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class ConceptApiController extends Controller
{
    /**
     * GET /api/concepts
     * List published concepts with optional filtering.
     * Supports: ?phase=2  ?search=eloquent  ?category=data-layer
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $concepts = Concept::with(['category', 'tags'])
            ->published()
            ->when($request->integer('phase'), fn ($q, $p) => $q->forPhase($p))
            ->when($request->string('search')->trim(), fn ($q, $s) =>
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%"))
            ->when($request->string('category')->trim(), fn ($q, $c) =>
                $q->inCategory($c))
            ->orderBy('phase')
            ->orderBy('title')
            ->paginate($request->integer('per_page', 15));

        return ConceptResource::collection($concepts);
    }

    /**
     * GET /api/concepts/{slug}
     * Return a single concept with all relationships.
     */
    public function show(Concept $concept): ConceptResource
    {
        $concept->load(['category', 'examples', 'tags', 'author']);
        $concept->incrementViews();

        return new ConceptResource($concept);
    }

    /**
     * POST /api/concepts  [auth:sanctum]
     * Create a new concept.
     */
    public function store(StoreConceptRequest $request): ConceptResource
    {
        $concept = Concept::create([
            ...$request->validated(),
            'slug'       => Str::slug($request->title),
            'created_by' => $request->user()->id,
        ]);

        if ($concept->is_published) {
            ConceptPublished::dispatch($concept);
        }

        return new ConceptResource($concept->load('category'));
    }

    /**
     * PUT /api/concepts/{slug}  [auth:sanctum]
     * Update a concept.
     */
    public function update(UpdateConceptRequest $request, Concept $concept): ConceptResource
    {
        $this->authorize('update', $concept);

        $wasPublished = $concept->is_published;
        $concept->update([
            ...$request->validated(),
            'slug' => Str::slug($request->title),
        ]);

        if (!$wasPublished && $concept->fresh()->is_published) {
            ConceptPublished::dispatch($concept->fresh());
        }

        return new ConceptResource($concept->fresh()->load(['category', 'tags']));
    }

    /**
     * DELETE /api/concepts/{slug}  [auth:sanctum]
     * Delete a concept.
     */
    public function destroy(Concept $concept): JsonResponse
    {
        $this->authorize('delete', $concept);
        $concept->delete();

        return response()->json(['message' => 'Concept deleted successfully.']);
    }
}
