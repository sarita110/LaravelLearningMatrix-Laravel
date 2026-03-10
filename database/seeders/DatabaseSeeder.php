<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Concept;
use App\Models\Example;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Create the admin user ───────────────────────────────
        $admin = User::factory()->admin()->create([
            'name'  => 'Sarita Admin',
            'email' => 'sarita@learningmatrix.test',
            'is_approved' => true,
        ]);

        // ── 2. Create realistic categories ────────────────────────
        $categories = $this->createCategories();

        // ── 3. Seed all 7 phases with real concept data ───────────
        $this->seedRealConcepts($admin, $categories);

        // ── 4. Create extra fake concepts to fill out the app ─────
        // $concepts = Concept::factory()
        //     ->count(15)
        //     ->published()
        //     ->recycle($categories)
        //     ->create(['created_by' => $admin->id]);

        // ── 5. Create and attach tags ──────────────────────────────
        $tags = $this->createTags();
        Concept::all()->each(function ($concept) use ($tags) {
            $concept->tags()->attach(
                $tags->random(rand(1, 4))->pluck('id')
            );
        });

        $this->command->info('Database seeded with real Laravel concept data!');
    }

    // ─────────────────────────────────────────────────────────────

    private function createCategories(): \Illuminate\Database\Eloquent\Collection
    {
        $data = [
            ['name' => 'Foundation',      'slug' => 'foundation',      'color' => '#1B2D50', 'sort_order' => 1, 'description' => 'Setup, routing, and templating basics.'],
            ['name' => 'Data Layer',       'slug' => 'data-layer',      'color' => '#154360', 'sort_order' => 2, 'description' => 'Migrations, Eloquent ORM, and relationships.'],
            ['name' => 'CRUD & Forms',     'slug' => 'crud-forms',      'color' => '#0D3D0D', 'sort_order' => 3, 'description' => 'Controllers, validation, and form handling.'],
            ['name' => 'Authentication',   'slug' => 'authentication',  'color' => '#3B0F52', 'sort_order' => 4, 'description' => 'Auth, middleware, and authorization.'],
            ['name' => 'Architecture',     'slug' => 'architecture',    'color' => '#7A3100', 'sort_order' => 5, 'description' => 'Service providers, DI, and design patterns.'],
            ['name' => 'Events & Queues',  'slug' => 'events-queues',   'color' => '#6B1728', 'sort_order' => 6, 'description' => 'Events, listeners, jobs, and mail.'],
            ['name' => 'REST API',         'slug' => 'rest-api',        'color' => '#1A3A50', 'sort_order' => 7, 'description' => 'API routes, resources, and Sanctum.'],
        ];

        foreach ($data as $row) {
            Category::create($row);
        }

        return Category::all();
    }

    private function createTags(): \Illuminate\Database\Eloquent\Collection
    {
        $tags = [
            ['name' => 'Eloquent',       'slug' => 'eloquent',        'color' => '#E74C3C'],
            ['name' => 'Routing',        'slug' => 'routing',         'color' => '#3498DB'],
            ['name' => 'Blade',          'slug' => 'blade',           'color' => '#2ECC71'],
            ['name' => 'Artisan',        'slug' => 'artisan',         'color' => '#9B59B6'],
            ['name' => 'Migrations',     'slug' => 'migrations',      'color' => '#E67E22'],
            ['name' => 'Authentication', 'slug' => 'authentication',  'color' => '#1ABC9C'],
            ['name' => 'API',            'slug' => 'api',             'color' => '#34495E'],
            ['name' => 'Queue',          'slug' => 'queue',           'color' => '#F39C12'],
            ['name' => 'Events',         'slug' => 'events',          'color' => '#D35400'],
            ['name' => 'Validation',     'slug' => 'validation',      'color' => '#27AE60'],
            ['name' => 'Middleware',     'slug' => 'middleware',      'color' => '#2980B9'],
            ['name' => 'Sanctum',        'slug' => 'sanctum',         'color' => '#8E44AD'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }

        return Tag::all();
    }

    private function seedRealConcepts(User $admin, $categories): void
    {
        $bySlug = $categories->keyBy('slug');

        $concepts = [
            // ── Phase 1: Foundation ────────────────────────────
            [
                'title'         => 'The Request Lifecycle',
                'slug'          => 'request-lifecycle',
                'description'   => 'How Laravel processes every HTTP request from entry point to response.',
                'explanation'   => "Every request to a Laravel application follows a defined pipeline:\n\n1. **public/index.php** — The single entry point. Boots the framework.\n2. **HTTP Kernel** — Runs global middleware (CSRF, session, auth).\n3. **Router** — Matches the URL to a route in routes/web.php.\n4. **Controller** — Runs the matched method, fetches data, returns a view.\n5. **Response** — The view is rendered and sent back to the browser.\n\nUnderstanding this pipeline is critical — every other concept touches one of these steps.",
                'code_example'  => "// public/index.php — the single entry point\ndefine('LARAVEL_START', microtime(true));\nrequire __DIR__.'/../vendor/autoload.php';\n(require_once __DIR__.'/../bootstrap/app.php')\n    ->handleRequest(Request::capture());",
                'code_language' => 'php',
                'phase'         => 1,
                'is_published'  => true,
                'category_id'   => $bySlug['foundation']->id,
                'created_by'    => $admin->id,
            ],
            [
                'title'         => 'Routing',
                'slug'          => 'routing',
                'description'   => 'Mapping URLs and HTTP verbs to controller methods or closures.',
                'explanation'   => "Routes live in **routes/web.php**. They are matched top-to-bottom — the first match wins.\n\n**Named routes** (`->name('concepts.index')`) let you generate URLs with `route('concepts.index')` instead of hard-coding `/concepts`. This decouples your views from your URL structure.\n\n**Route Model Binding**: when a parameter is type-hinted with a model, Laravel auto-resolves it from the database and throws 404 if not found.",
                'code_example'  => "// routes/web.php\nuse App\\Http\\Controllers\\ConceptController;\n\n// Simple route\nRoute::get('/', fn () => view('home'))->name('home');\n\n// Resource routes (7 RESTful actions in one line)\nRoute::resource('concepts', ConceptController::class);\n\n// Protected group\nRoute::middleware('auth')->group(function () {\n    Route::resource('concepts', ConceptController::class)\n         ->except(['index', 'show']);\n});",
                'code_language' => 'php',
                'phase'         => 1,
                'is_published'  => true,
                'category_id'   => $bySlug['foundation']->id,
                'created_by'    => $admin->id,
            ],
            [
                'title'         => 'Blade Templating',
                'slug'          => 'blade-templating',
                'description'   => 'Laravel\'s compile-to-PHP templating engine with layouts, directives, and components.',
                'explanation'   => "Blade files end in `.blade.php` and compile to plain PHP, cached for performance.\n\n**Key directives:**\n- `@extends('layouts.app')` — inherit a parent layout\n- `@section('content')` — define a named block\n- `@yield('content')` — in the layout, renders the block\n- `@include('partials.nav')` — inline another file\n- `{{ \$var }}` — echo with HTML escaping\n- `{!! \$html !!}` — echo without escaping (use carefully)\n- `@foreach / @forelse / @if / @can` — control flow",
                'code_example'  => "{{-- resources/views/layouts/app.blade.php --}}\n<!DOCTYPE html>\n<html>\n<head><title>@yield('title', 'Learning Matrix')</title></head>\n<body>\n    @include('partials.nav')\n    <main>@yield('content')</main>\n</body>\n</html>\n\n{{-- resources/views/concepts/index.blade.php --}}\n@extends('layouts.app')\n@section('title', 'Browse Concepts')\n@section('content')\n    @forelse(\$concepts as \$concept)\n        <h2>{{ \$concept->title }}</h2>\n    @empty\n        <p>No concepts yet.</p>\n    @endforelse\n@endsection",
                'code_language' => 'blade',
                'phase'         => 1,
                'is_published'  => true,
                'category_id'   => $bySlug['foundation']->id,
                'created_by'    => $admin->id,
            ],
            // ── Phase 2: Data Layer ────────────────────────────
            [
                'title'         => 'Migrations',
                'slug'          => 'migrations',
                'description'   => 'Version control for your database schema — define tables in PHP, never raw SQL.',
                'explanation'   => "Migrations are PHP classes with two methods:\n- `up()` — runs when you migrate (creates/alters tables)\n- `down()` — runs when you rollback (reverses the change)\n\n**Key commands:**\n- `php artisan migrate` — run pending migrations\n- `php artisan migrate:rollback` — undo last batch\n- `php artisan migrate:fresh --seed` — drop all, re-run all, then seed\n\nThe Schema Builder provides fluent methods: `\$table->string()`, `\$table->foreignId()->constrained()`, `\$table->timestamps()`, etc.",
                'code_example'  => "Schema::create('concepts', function (Blueprint \$table) {\n    \$table->id();                          // BIGINT auto-increment PK\n    \$table->string('title');\n    \$table->string('slug')->unique();       // URL-friendly, must be unique\n    \$table->text('description');\n    \$table->tinyInteger('phase')->unsigned(); // 1–7\n    \$table->boolean('is_published')->default(false);\n    \$table->foreignId('category_id')       // Creates category_id column\n          ->constrained()                   // FK → categories.id\n          ->onDelete('cascade');            // Delete concept if category deleted\n    \$table->timestamps();                  // created_at + updated_at\n});",
                'code_language' => 'php',
                'phase'         => 2,
                'is_published'  => true,
                'category_id'   => $bySlug['data-layer']->id,
                'created_by'    => $admin->id,
            ],
            [
                'title'         => 'Eloquent ORM',
                'slug'          => 'eloquent-orm',
                'description'   => 'Laravel\'s ActiveRecord ORM — PHP objects that represent and persist database rows.',
                'explanation'   => "**Eloquent** maps a Model class to a database table (e.g. `Concept` → `concepts`).\n\n**Key concepts:**\n- `\$fillable` — columns safe for mass-assignment via `Concept::create(\$request->validated())`\n- `\$casts` — auto-converts column values (`'is_published' => 'boolean'`)\n- **Mass Assignment Protection** — Eloquent refuses to fill columns not in `\$fillable` from raw arrays\n\n**Common queries:**\n- `Concept::all()` — fetch every row\n- `Concept::find(1)` — find by PK, returns null if missing\n- `Concept::findOrFail(1)` — throws 404 if missing\n- `Concept::where('phase', 1)->get()` — filtered collection",
                'code_example'  => "class Concept extends Model\n{\n    protected \$fillable = [\n        'title', 'slug', 'description', 'phase', 'is_published', 'category_id',\n    ];\n\n    protected function casts(): array\n    {\n        return [\n            'is_published' => 'boolean',\n            'phase'        => 'integer',\n        ];\n    }\n\n    // Usage in controller:\n    // Concept::with('category')->published()->paginate(12)\n}",
                'code_language' => 'php',
                'phase'         => 2,
                'is_published'  => true,
                'category_id'   => $bySlug['data-layer']->id,
                'created_by'    => $admin->id,
            ],
            [
                'title'         => 'Eloquent Relationships',
                'slug'          => 'eloquent-relationships',
                'description'   => 'hasMany, belongsTo, and belongsToMany — defining how models connect to each other.',
                'explanation'   => "**One-to-Many (hasMany / belongsTo):**\nA Category has many Concepts. A Concept belongs to one Category.\nThe FK (`category_id`) lives on the **many** side (concepts table).\n\n**Many-to-Many (belongsToMany):**\nA Concept has many Tags. A Tag belongs to many Concepts.\nRequires a **pivot table**: `concept_tag` with `concept_id` and `tag_id`.\n\n**Eager Loading** — prevents the N+1 query problem:\n```php\n// BAD: N+1 — fires 1 query for concepts + 1 per concept for category\nConcept::all()->each(fn (\$c) => \$c->category->name);\n\n// GOOD: 2 queries total\nConcept::with('category')->get();\n```",
                'code_example'  => "class Concept extends Model\n{\n    // A concept belongs to ONE category\n    public function category(): BelongsTo\n    {\n        return \$this->belongsTo(Category::class);\n    }\n\n    // A concept has MANY code examples\n    public function examples(): HasMany\n    {\n        return \$this->hasMany(Example::class)->orderBy('order');\n    }\n\n    // A concept belongs to MANY tags (pivot: concept_tag)\n    public function tags(): BelongsToMany\n    {\n        return \$this->belongsToMany(Tag::class);\n    }\n}",
                'code_language' => 'php',
                'phase'         => 2,
                'is_published'  => true,
                'category_id'   => $bySlug['data-layer']->id,
                'created_by'    => $admin->id,
            ],
            // ── Phase 3: CRUD & Forms ──────────────────────────
            [
                'title'         => 'Resource Controllers',
                'slug'          => 'resource-controllers',
                'description'   => 'Seven RESTful actions in one class — the standard Laravel CRUD scaffold.',
                'explanation'   => "A resource controller maps 7 standard HTTP actions:\n\n| Method | URL | Action | Description |\n|--------|-----|--------|-------------|\n| GET | /concepts | index | List all |\n| GET | /concepts/create | create | Show form |\n| POST | /concepts | store | Save new |\n| GET | /concepts/{slug} | show | Display one |\n| GET | /concepts/{slug}/edit | edit | Show edit form |\n| PUT | /concepts/{slug} | update | Save edits |\n| DELETE | /concepts/{slug} | destroy | Delete |\n\nThis naming convention is universal — any Laravel developer can read your code immediately.",
                'code_example'  => "// routes/web.php\nRoute::resource('concepts', ConceptController::class);\n\n// php artisan route:list → shows all 7 routes\n\n// In the controller:\npublic function store(StoreConceptRequest \$request): RedirectResponse\n{\n    \$concept = Concept::create([\n        ...\$request->validated(),\n        'slug'       => Str::slug(\$request->title),\n        'created_by' => auth()->id(),\n    ]);\n\n    return redirect()\n        ->route('concepts.show', \$concept)\n        ->with('success', 'Concept created!');\n}",
                'code_language' => 'php',
                'phase'         => 3,
                'is_published'  => true,
                'category_id'   => $bySlug['crud-forms']->id,
                'created_by'    => $admin->id,
            ],
            [
                'title'         => 'Form Requests & Validation',
                'slug'          => 'form-requests-validation',
                'description'   => 'Dedicated validation classes with authorize(), rules(), and automatic redirect-on-failure.',
                'explanation'   => "A **Form Request** is a class with two methods:\n- `authorize()` — `true` = allow, `false` = 403 Forbidden\n- `rules()` — the validation array\n\nIf validation fails, Laravel **automatically** redirects back with `\$errors` and `old()` input — no try/catch needed.\n\n**In Blade:** use `\$errors->has('title')`, `\$errors->first('title')`, `@error('title')`, and `old('title')`.\n\n**CSRF Protection:** every form must have `@csrf`. The `VerifyCsrfToken` middleware checks every POST/PUT/DELETE request for a matching token.",
                'code_example'  => "class StoreConceptRequest extends FormRequest\n{\n    public function authorize(): bool\n    {\n        return auth()->check();\n    }\n\n    public function rules(): array\n    {\n        return [\n            'title'       => ['required', 'string', 'max:255', 'unique:concepts,title'],\n            'description' => ['required', 'string', 'min:50'],\n            'phase'       => ['required', 'integer', 'between:1,7'],\n            'category_id' => ['required', 'integer', 'exists:categories,id'],\n        ];\n    }\n}",
                'code_language' => 'php',
                'phase'         => 3,
                'is_published'  => true,
                'category_id'   => $bySlug['crud-forms']->id,
                'created_by'    => $admin->id,
            ],
            // ── Phase 4: Authentication ────────────────────────
            [
                'title'         => 'Laravel Breeze & Auth Middleware',
                'slug'          => 'breeze-auth-middleware',
                'description'   => 'Breeze provides ready-made auth UI. The auth middleware protects routes from unauthenticated users.',
                'explanation'   => "**Breeze** generates controllers, views, and routes for login, register, email verification, and password reset.\n\n**Auth Middleware** (`auth`) checks `auth()->check()`. If the user is not logged in, it redirects to `/login`.\n\nApply to route groups:\n```php\nRoute::middleware('auth')->group(function () {\n    // Only authenticated users can reach these routes\n});\n```\n\n**In Blade:**\n```blade\n@auth — show content only to logged-in users\n@guest — show content only to guests\n{{ auth()->user()->name }} — current user's name\n```",
                'code_example'  => "// Install Breeze\ncomposer require laravel/breeze --dev\nphp artisan breeze:install blade\nnpm install && npm run dev\nphp artisan migrate\n\n// Protect write routes\nRoute::middleware('auth')->group(function () {\n    Route::post('/concepts', [ConceptController::class, 'store']);\n    Route::put('/concepts/{concept}', [ConceptController::class, 'update']);\n    Route::delete('/concepts/{concept}', [ConceptController::class, 'destroy']);\n});",
                'code_language' => 'bash',
                'phase'         => 4,
                'is_published'  => true,
                'category_id'   => $bySlug['authentication']->id,
                'created_by'    => $admin->id,
            ],
            [
                'title'         => 'Gates & Policies',
                'slug'          => 'gates-policies',
                'description'   => 'Two flavours of authorization — Gates for simple checks, Policies for model-level permissions.',
                'explanation'   => "**Gates** are closures registered in a Service Provider. Best for model-agnostic checks:\n```php\nGate::define('admin', fn (\$user) => \$user->is_admin);\n// Usage: Gate::allows('admin') or @can('admin') in Blade\n```\n\n**Policies** are classes that group authorization for a single model. Best for CRUD-level checks:\n```php\nclass ConceptPolicy {\n    public function update(User \$user, Concept \$concept): bool {\n        return \$user->id === \$concept->created_by || \$user->is_admin;\n    }\n}\n```\n\nIn controllers: `\$this->authorize('update', \$concept)` — throws 403 if the policy returns false.",
                'code_example'  => "// In controller\npublic function update(UpdateConceptRequest \$request, Concept \$concept)\n{\n    \$this->authorize('update', \$concept); // Throws 403 if denied\n    \$concept->update(\$request->validated());\n    return redirect()->route('concepts.show', \$concept);\n}\n\n// In Blade\n@can('update', \$concept)\n    <a href=\"{{ route('concepts.edit', \$concept) }}\">Edit</a>\n@endcan\n@can('delete', \$concept)\n    <form method=\"POST\" action=\"{{ route('concepts.destroy', \$concept) }}\">\n        @csrf @method('DELETE')\n        <button>Delete</button>\n    </form>\n@endcan",
                'code_language' => 'php',
                'phase'         => 4,
                'is_published'  => true,
                'category_id'   => $bySlug['authentication']->id,
                'created_by'    => $admin->id,
            ],
            // ── Phase 5: Architecture ──────────────────────────
            [
                'title'         => 'Service Providers',
                'slug'          => 'service-providers',
                'description'   => 'The bootstrap backbone — where services are registered and booted into the application.',
                'explanation'   => "A Service Provider has two methods:\n\n**`register()`** — bind things into the Service Container. Runs early. Do NOT call other services here.\n\n**`boot()`** — safe to use other services. Register Blade directives, view composers, model observers, event listeners.\n\nAll providers are listed in `bootstrap/providers.php`. Package providers are auto-discovered via `composer.json`.\n\nThis is the integration hub: when you install a Laravel package (Telescope, Horizon, Sanctum), it registers its own Service Provider.",
                'code_example'  => "class LearningMatrixServiceProvider extends ServiceProvider\n{\n    public function register(): void\n    {\n        // Bind an interface to a concrete implementation\n        \$this->app->bind(\n            ConceptRepositoryInterface::class,\n            EloquentConceptRepository::class\n        );\n    }\n\n    public function boot(): void\n    {\n        // Register a custom Blade directive\n        Blade::directive('phaseBadge', function (string \$expr) {\n            return \"<?php echo '<span class=\\\"badge phase-'.{\$expr}.'\\\">'.'Phase '.{\$expr}.'</span>'; ?>\";\n        });\n    }\n}",
                'code_language' => 'php',
                'phase'         => 5,
                'is_published'  => true,
                'category_id'   => $bySlug['architecture']->id,
                'created_by'    => $admin->id,
            ],
            [
                'title'         => 'Service Container & Dependency Injection',
                'slug'          => 'service-container-di',
                'description'   => 'Laravel\'s IoC container — automatically builds and injects class dependencies.',
                'explanation'   => "The **Service Container** is a registry that knows how to build any class. When you type-hint a class in a constructor, the container resolves it (and all its dependencies recursively) and injects it.\n\nThis is **Dependency Injection** — classes declare what they need, the container provides it.\n\n**Binding:**\n```php\n\$this->app->bind(Interface::class, Implementation::class);\n\$this->app->singleton(Interface::class, Implementation::class); // one instance per app\n```\n\n**Benefits:**\n- Testability — swap the real DB with a mock in tests\n- Flexibility — change implementations without touching dependent classes\n- Clarity — dependencies are explicit in the constructor",
                'code_example'  => "// Define an interface\ninterface ConceptRepositoryInterface {\n    public function allPublished(): LengthAwarePaginator;\n    public function findBySlug(string \$slug): Concept;\n}\n\n// Implement it\nclass EloquentConceptRepository implements ConceptRepositoryInterface {\n    public function allPublished(): LengthAwarePaginator {\n        return Concept::with('category')->published()->paginate(12);\n    }\n}\n\n// In a controller — container injects EloquentConceptRepository\nclass ConceptController extends Controller {\n    public function __construct(\n        private ConceptRepositoryInterface \$repo\n    ) {}\n\n    public function index(): View {\n        return view('concepts.index', [\n            'concepts' => \$this->repo->allPublished(),\n        ]);\n    }\n}",
                'code_language' => 'php',
                'phase'         => 5,
                'is_published'  => true,
                'category_id'   => $bySlug['architecture']->id,
                'created_by'    => $admin->id,
            ],
            // ── Phase 6: Events & Queues ───────────────────────
            [
                'title'         => 'Events & Listeners',
                'slug'          => 'events-listeners',
                'description'   => 'Decouple application logic — fire an event, react to it with unlimited listeners.',
                'explanation'   => "An **Event** is a plain PHP class representing something that happened.\nA **Listener** reacts to an event. Multiple listeners can respond to one event.\n\nBenefits: the code that publishes a concept doesn't know about email logic, analytics, or audit logging — it just fires the event. Listeners handle the rest. This is the **Open/Closed Principle**: add behaviour without modifying existing code.\n\nListeners can be **queued** (`implements ShouldQueue`) to run in the background.",
                'code_example'  => "// Fire the event\nConceptPublished::dispatch(\$concept);\n\n// The event class\nclass ConceptPublished {\n    use Dispatchable, SerializesModels;\n    public function __construct(public readonly Concept \$concept) {}\n}\n\n// A listener that reacts\nclass NotifySubscribers {\n    public function handle(ConceptPublished \$event): void {\n        SendConceptNotificationEmail::dispatch(\$event->concept)\n            ->delay(now()->addMinutes(1));\n    }\n}\n\n// Register in AppServiceProvider::boot()\nEvent::listen(ConceptPublished::class, NotifySubscribers::class);",
                'code_language' => 'php',
                'phase'         => 6,
                'is_published'  => true,
                'category_id'   => $bySlug['events-queues']->id,
                'created_by'    => $admin->id,
            ],
            [
                'title'         => 'Queues & Jobs',
                'slug'          => 'queues-jobs',
                'description'   => 'Defer slow work (emails, reports, image processing) to a background worker process.',
                'explanation'   => "Without queues, slow operations (sending email, generating PDFs) block the HTTP response — users wait.\n\nWith queues:\n1. HTTP Request comes in\n2. Slow work is **pushed to a queue** (a database table)\n3. Response returns immediately (<100ms)\n4. A separate **queue worker** (`php artisan queue:work`) processes jobs in the background\n\nA **Job** implements `ShouldQueue` and uses the `Queueable` trait. The `handle()` method runs in the worker.\n\nSetup: `QUEUE_CONNECTION=database` in .env, then `php artisan queue:table && php artisan migrate`.",
                'code_example'  => "class SendConceptNotificationEmail implements ShouldQueue\n{\n    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;\n\n    public function __construct(public readonly Concept \$concept) {}\n\n    // This method runs in the background worker — not in the web request\n    public function handle(): void\n    {\n        \$admins = User::where('is_admin', true)->get();\n        foreach (\$admins as \$admin) {\n            Mail::to(\$admin)->send(new NewConceptMail(\$this->concept));\n        }\n    }\n}\n\n// Dispatch with delay\nSendConceptNotificationEmail::dispatch(\$concept)\n    ->delay(now()->addMinutes(2));",
                'code_language' => 'php',
                'phase'         => 6,
                'is_published'  => true,
                'category_id'   => $bySlug['events-queues']->id,
                'created_by'    => $admin->id,
            ],
            // ── Phase 7: REST API ──────────────────────────────
            [
                'title'         => 'API Routes & Controllers',
                'slug'          => 'api-routes-controllers',
                'description'   => 'routes/api.php for JSON endpoints — stateless, token-auth, no sessions or CSRF.',
                'explanation'   => "**routes/api.php** handles JSON API requests. Differences from web routes:\n- **No sessions** — stateless by design\n- **No CSRF** — not needed because requests are authenticated via tokens, not cookies\n- **Auto-prefixed** — all routes are prefixed with `/api/`\n- **Uses api middleware group** — rate throttling, JSON Accept header enforcement\n\nAPI responses should return `JsonResource` (or `response()->json()`) — never raw models.",
                'code_example'  => "// routes/api.php\nRoute::get('/concepts', [ConceptApiController::class, 'index']);\nRoute::get('/concepts/{concept}', [ConceptApiController::class, 'show']);\n\nRoute::middleware('auth:sanctum')->group(function () {\n    Route::post('/concepts', [ConceptApiController::class, 'store']);\n    Route::put('/concepts/{concept}', [ConceptApiController::class, 'update']);\n    Route::delete('/concepts/{concept}', [ConceptApiController::class, 'destroy']);\n});\n\n// Controller returns a Resource, not a view\npublic function index(Request \$request): AnonymousResourceCollection\n{\n    \$concepts = Concept::with(['category', 'tags'])\n        ->published()\n        ->when(\$request->phase, fn(\$q, \$p) => \$q->forPhase((int) \$p))\n        ->paginate(15);\n    return ConceptResource::collection(\$concepts);\n}",
                'code_language' => 'php',
                'phase'         => 7,
                'is_published'  => true,
                'category_id'   => $bySlug['rest-api']->id,
                'created_by'    => $admin->id,
            ],
            [
                'title'         => 'Laravel Sanctum',
                'slug'          => 'laravel-sanctum',
                'description'   => 'Lightweight API token authentication for SPAs and mobile apps.',
                'explanation'   => "**Sanctum** provides two auth systems:\n1. **SPA cookie-based auth** — for same-domain frontend apps (e.g. Vue/React)\n2. **API token auth** — for mobile apps, third-party clients, and public APIs\n\nFor this project, we use **API tokens**:\n1. Client POSTs email + password to `/api/tokens/create`\n2. Server returns a plain-text token\n3. Client includes it in every request: `Authorization: Bearer <token>`\n\nThe token is hashed and stored in `personal_access_tokens`. Protected routes use `auth:sanctum` middleware.",
                'code_example'  => "// Install\ncomposer require laravel/sanctum\nphp artisan vendor:publish --provider=\"Laravel\\Sanctum\\SanctumServiceProvider\"\nphp artisan migrate\n\n// Add HasApiTokens to User model\nuse Laravel\\Sanctum\\HasApiTokens;\nclass User extends Authenticatable { use HasApiTokens; }\n\n// Issue a token\nRoute::post('/tokens/create', function (Request \$request) {\n    \$user = User::where('email', \$request->email)->firstOrFail();\n    if (!Hash::check(\$request->password, \$user->password)) {\n        return response()->json(['message' => 'Invalid credentials'], 401);\n    }\n    return response()->json(['token' => \$user->createToken('api')->plainTextToken]);\n});",
                'code_language' => 'php',
                'phase'         => 7,
                'is_published'  => true,
                'category_id'   => $bySlug['rest-api']->id,
                'created_by'    => $admin->id,
            ],
        ];

        foreach ($concepts as $data) {
            Concept::create($data);
        }
    }
}
