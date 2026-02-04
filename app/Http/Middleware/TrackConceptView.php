<?php

namespace App\Http\Middleware;

use App\Models\Concept;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: TrackConceptView
 *
 * Runs AFTER the controller (post-response middleware pattern).
 * Logs details about which concept was viewed, by whom, and from where.
 *
 * Applied to the concepts.show route in routes/web.php.
 * Alias registered as 'track.concept.view' in bootstrap/app.php.
 */
class TrackConceptView
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Pass the request down the pipeline to the controller
        $response = $next($request);

        // 2. After the response is built, run our tracking logic
        $concept = $request->route('concept');

        if ($concept instanceof Concept) {
            Log::channel('daily')->info('Concept page viewed', [
                'concept_id'    => $concept->id,
                'concept_title' => $concept->title,
                'concept_phase' => $concept->phase,
                'user_id'       => auth()->id() ?? 'guest',
                'user_name'     => auth()->user()?->name ?? 'Guest',
                'ip'            => $request->ip(),
                'user_agent'    => $request->userAgent(),
            ]);
        }

        return $response;
    }
}
