# The Laravel Learning Matrix

> A meta full-stack Laravel application — every concept you learn is documented **inside** the very app that uses it.

---

## Admin Login

| Field | Value |
|-------|-------|
| Email | `sarita@learningmatrix.test` |
| Password | `password` |
| Is Admin | Yes (can delete any concept) |

---

## What Is This?

The **Laravel Learning Matrix** is a web application for documenting and browsing PHP/Laravel concepts, built as a hands-on learning project. The twist: the app *is* the curriculum.

- Browse the **Routing** concept → the page was served by a named route.
- Read about **Eloquent Relationships** → the data was fetched using `->with('category')`.
- Submit a new concept → a **Form Request** validates it and a **ConceptPublished** event fires.
- See the **Service Providers** page → the repository behind it was injected by the Service Container.

---

## Project Structure (Key Files)

```
learning-matrix/
├── app/
│   ├── Events/ConceptPublished.php          ← Phase 6: Event class
│   ├── Http/
│   │   ├── Controllers/ConceptController.php      ← Phase 3: Resource Controller
│   │   ├── Controllers/Api/ConceptApiController.php ← Phase 7: API Controller
│   │   ├── Middleware/TrackConceptView.php         ← Phase 4: Custom Middleware
│   │   ├── Requests/StoreConceptRequest.php        ← Phase 3: Form Request
│   │   └── Resources/ConceptResource.php           ← Phase 7: API Resource
│   ├── Jobs/SendConceptNotificationEmail.php  ← Phase 6: Queued Job
│   ├── Listeners/NotifySubscribers.php       ← Phase 6: Event Listener
│   ├── Mail/NewConceptMail.php               ← Phase 6: Mailable
│   ├── Models/
│   │   ├── Concept.php                       ← Phase 2: Eloquent model + scopes
│   │   ├── Category.php                      ← Phase 2: hasMany relationship
│   │   ├── Example.php                       ← Phase 2: belongsTo
│   │   └── Tag.php                           ← Phase 2: belongsToMany
│   ├── Policies/ConceptPolicy.php            ← Phase 4: Authorization Policy
│   ├── Providers/
│   │   ├── AppServiceProvider.php            ← Phase 4-6: Events, Gates registered here
│   │   └── LearningMatrixServiceProvider.php ← Phase 5: Service Container bindings
│   └── Repositories/
│       ├── ConceptRepositoryInterface.php    ← Phase 5: The interface (contract)
│       └── EloquentConceptRepository.php     ← Phase 5: Concrete implementation
├── bootstrap/
│   ├── app.php                               ← Laravel 11 application bootstrap
│   └── providers.php                         ← Registered service providers
├── database/
│   ├── factories/ConceptFactory.php          ← Phase 2: Fake data generator
│   ├── migrations/                           ← Phase 2: DB schema version control
│   └── seeders/DatabaseSeeder.php            ← Phase 2: Real concept data seeder
├── resources/views/
│   ├── layouts/app.blade.php                 ← Phase 1: Master layout
│   ├── partials/nav.blade.php                ← Phase 1: @include partial
│   ├── home.blade.php                        ← Phase 1: Homepage
│   └── concepts/
│       ├── index.blade.php                   ← Phase 3: List + filter view
│       ├── show.blade.php                    ← Phase 3: Detail view
│       ├── create.blade.php                  ← Phase 3: Create form
│       └── edit.blade.php                    ← Phase 3: Edit form
└── routes/
    ├── web.php                               ← Phase 1 + 4: Web routes + auth middleware
    └── api.php                               ← Phase 7: JSON API routes + Sanctum
```

---

## Quick Setup (5 steps)

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+

### 1. Install PHP dependencies

```bash
composer install
```

### 2. Set up environment

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Set up the SQLite database

```bash
touch database/database.sqlite
php artisan migrate
```

### 4. Seed with real Laravel concept data

```bash
php artisan db:seed
```

This creates:
- Admin user: `sarita@learningmatrix.test` / password: `password`
- 7 categories (one per phase)
- 16 real Laravel concepts with full code examples
- Tags, linked concepts, view counts

### 5. Install frontend assets and start the server

```bash
npm install && npm run dev
# In a second terminal:
php artisan serve
```

Visit: **http://127.0.0.1:8000**

---

## Phase-by-Phase What to Explore

### Phase 1 — Foundation
- Hit `/concepts` in the browser → trace the request: `routes/web.php` → `ConceptController::index()` → `concepts/index.blade.php`
- Look at `resources/views/layouts/app.blade.php` — find `@yield('content')` and see how child views extend it

### Phase 2 — Data Layer
```bash
php artisan tinker
> Concept::with('category', 'tags')->first()         # Eager-loading
> Category::with('concepts')->find(1)->concepts->count()
> Concept::published()->forPhase(2)->get()            # Local scopes
> Concept::factory()->count(5)->create()              # Factory
```

### Phase 3 — CRUD
- Log in at `/login` (use `sarita@learningmatrix.test` / `password`)
- Create a concept at `/concepts/create`
- Submit the empty form — observe validation errors and old input
- Submit valid data — observe the redirect + flash message

### Phase 4 — Auth & Authorization
- Log out, then try accessing `/concepts/create` → should redirect to login
- Log in as non-admin, try to edit another user's concept → 403 Forbidden
- Check `ConceptPolicy.php` and `AppServiceProvider.php` to see how this is wired

### Phase 5 — Architecture
- Open `LearningMatrixServiceProvider.php` and read the comments
- In `ConceptController`, the constructor receives `ConceptRepositoryInterface` — trace how the container resolves it
- In tinker: `app(App\Repositories\ConceptRepositoryInterface::class)` — see what comes back

### Phase 6 — Events, Queues & Mail
```bash
# Enable database queues
# In .env: QUEUE_CONNECTION=database
# Then:
php artisan migrate  # creates jobs table

# Start the worker in a separate terminal
php artisan queue:work

# Now edit a draft concept and publish it — watch the worker output
# Check storage/logs/laravel.log for the LogConceptPublished listener entry
```

### Phase 7 — REST API
```bash
# Get a token
curl -X POST http://127.0.0.1:8000/api/tokens/create \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"sarita@learningmatrix.test","password":"password"}'

# List concepts
curl http://127.0.0.1:8000/api/concepts \
  -H "Accept: application/json"

# Filter by phase
curl "http://127.0.0.1:8000/api/concepts?phase=2" \
  -H "Accept: application/json"

# Create a concept (use token from step 1)
curl -X POST http://127.0.0.1:8000/api/concepts \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{"title":"My New Concept","description":"Description here (min 20 chars)","phase":3,"category_id":1}'
```

---

## Artisan Commands Reference

| Command | Purpose |
|---------|---------|
| `php artisan serve` | Start dev server at http://127.0.0.1:8000 |
| `php artisan route:list` | List all registered routes |
| `php artisan tinker` | Interactive REPL |
| `php artisan migrate:fresh --seed` | Reset DB + reseed |
| `php artisan queue:work` | Process queued jobs |
| `php artisan make:model X -mfsc` | Model + Migration + Factory + Seeder + Controller |
| `php artisan make:request X` | Form Request |
| `php artisan make:event X` | Event class |
| `php artisan make:job X` | Job class |
| `php artisan make:policy X --model=Y` | Policy scoped to a model |



*Built as a learning project for Laravel development.*
*See `laravel-learning-matrix-curriculum.docx` for the full 7-phase curriculum.*
