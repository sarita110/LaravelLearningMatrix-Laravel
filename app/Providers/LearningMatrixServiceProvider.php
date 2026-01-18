<?php

namespace App\Providers;

use App\Repositories\ConceptRepositoryInterface;
use App\Repositories\EloquentConceptRepository;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * LearningMatrixServiceProvider
 *
 * Demonstrates Phase 5 architecture concepts:
 *   - register()  → bind the repository interface to its implementation
 *   - boot()      → register a custom Blade directive
 *
 * Registered in bootstrap/providers.php
 */
class LearningMatrixServiceProvider extends ServiceProvider
{
    /**
     * register() — bind services into the container.
     *
     * When any class type-hints ConceptRepositoryInterface,
     * the container will automatically build and inject EloquentConceptRepository.
     *
     * To swap to a cache-backed repo: just change EloquentConceptRepository here.
     * No controller changes needed.
     */
    public function register(): void
    {
        $this->app->bind(
            ConceptRepositoryInterface::class,
            EloquentConceptRepository::class
        );
    }

    /**
     * boot() — safe to interact with other services.
     *
     * Registers @phaseBadge(1) as a Blade directive that renders a phase badge.
     */
    public function boot(): void
    {
        Blade::directive('phaseBadge', function (string $expression) {
            return "<?php
                \$__phase = {$expression};
                \$__colors = ['','#1B2D50','#154360','#0D3D0D','#3B0F52','#7A3100','#6B1728','#1A3A50'];
                \$__color = \$__colors[\$__phase] ?? '#333';
                echo '<span style=\"background:'.\$__color.';color:#fff;padding:2px 8px;border-radius:4px;font-size:12px;font-weight:600;\">Phase '.\$__phase.'</span>';
            ?>";
        });
    }
}
