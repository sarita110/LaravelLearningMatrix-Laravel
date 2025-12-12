<?php

namespace App\Http\Controllers;

use App\Events\ConceptPublished;
use App\Http\Requests\StoreConceptRequest;
use App\Http\Requests\UpdateConceptRequest;
use App\Models\Category;
use App\Models\Concept;
use App\Repositories\ConceptRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ConceptController extends Controller
{
    /**
     * Constructor — the Service Container automatically injects the
     * repository implementation bound in LearningMatrixServiceProvider.
     */
    public function __construct(private ConceptRepositoryInterface $repo) {}

    // ─── INDEX ────────────────────────────────────────────────────

    /** GET /concepts — list all published concepts, optionally filtered by phase. */
    public function index(Request $request): View
    {
        $phase  = (int) $request->get('phase', 0);
        $search = trim((string) $request->get('search', ''));

        $query = Concept::with('category')->published();

        if ($phase > 0) {
            $query->where('phase', $phase);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $concepts = $query
            ->orderBy('phase')
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString(); // Preserve query params in pagination links

        $phases = range(1, 7);

        $phaseNames = [
            1 => 'Foundation',
            2 => 'Data Layer',
            3 => 'CRUD & Forms',
            4 => 'Auth & Authorization',
            5 => 'Mail',
            6 => 'REST API Basics',
            7 => 'Advanced',
        ];

        $phaseName = $phase > 0 ? ($phaseNames[$phase] ?? '') : '';

        return view('concepts.index', compact('concepts', 'phases', 'phaseNames', 'phase', 'phaseName', 'search'));
    }

    // ─── SHOW ─────────────────────────────────────────────────────

    /** GET /concepts/{slug} — display a single concept (Route Model Binding by slug). */
    public function show(Concept $concept): View
    {
        // Eager-load all relations needed by the view
        $concept->load(['category', 'examples', 'tags', 'author']);

        // Increment view count atomically (safe for concurrent requests)
        $concept->incrementViews();

        $related = Concept::with('category')
            ->published()
            ->where('phase', $concept->phase)
            ->where('id', '!=', $concept->id)
            ->limit(4)
            ->get();

        return view('concepts.show', compact('concept', 'related'));
    }

    // ─── CREATE ───────────────────────────────────────────────────

    /** GET /concepts/create — show the create form. */
    public function create(): View
    {
        $categories = Category::ordered()->get();
        $phases     = range(1, 7);

        return view('concepts.create', compact('categories', 'phases'));
    }

    // ─── STORE ────────────────────────────────────────────────────

    /** POST /concepts — validate and persist a new concept. */
    public function store(StoreConceptRequest $request): RedirectResponse
    {
        $concept = Concept::create([
            ...$request->validated(),
            'slug'       => Str::slug($request->title),
            'created_by' => auth()->id(),
        ]);

        // If created as published, fire the event
        if ($concept->is_published) {
            ConceptPublished::dispatch($concept);
        }

        return redirect()
            ->route('concepts.show', $concept)
            ->with('success', 'Concept "' . $concept->title . '" created successfully!');
    }

    // ─── EDIT ─────────────────────────────────────────────────────

    /** GET /concepts/{slug}/edit — show the edit form. */
    public function edit(Concept $concept): View
    {
        $this->authorize('update', $concept);

        $categories = Category::ordered()->get();
        $phases     = range(1, 7);

        return view('concepts.edit', compact('concept', 'categories', 'phases'));
    }

    // ─── UPDATE ───────────────────────────────────────────────────

    /** PUT /concepts/{slug} — validate and persist edits. */
    public function update(UpdateConceptRequest $request, Concept $concept): RedirectResponse
    {
        $this->authorize('update', $concept);

        $wasPublished = $concept->is_published;

        $concept->update([
            ...$request->validated(),
            'slug' => Str::slug($request->title),
        ]);

        // Fire event if concept was just published for the first time
        if (!$wasPublished && $concept->is_published) {
            ConceptPublished::dispatch($concept->fresh());
        }

        return redirect()
            ->route('concepts.show', $concept)
            ->with('success', 'Concept updated successfully!');
    }

    // ─── DESTROY ──────────────────────────────────────────────────

    /** DELETE /concepts/{slug} — soft-delete the concept. */
    public function destroy(Concept $concept): RedirectResponse
    {
        $this->authorize('delete', $concept);

        $title = $concept->title;
        $concept->delete();

        return redirect()
            ->route('concepts.index')
            ->with('success', '"' . $title . '" has been deleted.');
    }
}
