<?php

namespace App\Listeners;

use App\Events\ConceptPublished;
use Illuminate\Support\Facades\Log;

/**
 * Logs when a concept is published.
 * Demonstrates multiple listeners responding to a single event.
 */
class LogConceptPublished
{
    public function handle(ConceptPublished $event): void
    {
        Log::info('Concept published', [
            'concept_id'    => $event->concept->id,
            'concept_title' => $event->concept->title,
            'concept_phase' => $event->concept->phase,
            'published_at'  => now()->toISOString(),
        ]);
    }
}
