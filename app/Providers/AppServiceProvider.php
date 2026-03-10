<?php

namespace App\Providers;

use App\Events\ConceptPublished;
use App\Listeners\LogConceptPublished;
use App\Listeners\NotifyAdminOfNewUser;
use App\Listeners\NotifySubscribers;
use App\Models\Concept;
use App\Policies\ConceptPolicy;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * Runs early — do not resolve other services here.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * All providers are registered by now — safe to use any service.
     */
    public function boot(): void
    {
        // 2. ADDED THIS CHECK: Force HTTPS in production!
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // ── Event → Listener registrations ───────────────────────
        Event::listen(ConceptPublished::class, NotifySubscribers::class);
        Event::listen(ConceptPublished::class, LogConceptPublished::class);

        // Notify admins by email whenever a new user registers
        Event::listen(Registered::class, NotifyAdminOfNewUser::class);

        // ── Gates ─────────────────────────────────────────────────
        Gate::define('admin', fn ($user) => $user->isAdmin());

        // ── Policies ──────────────────────────────────────────────
        // Laravel auto-discovers policies by convention (Concept → ConceptPolicy)
        // but we can also register them explicitly:
        Gate::policy(Concept::class, ConceptPolicy::class);
    }
}
