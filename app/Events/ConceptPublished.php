<?php

namespace App\Events;

use App\Models\Concept;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired when a Concept is published for the first time.
 *
 * Listeners attached to this event:
 *   - NotifySubscribers  → dispatches SendConceptNotificationEmail job to the queue
 *   - LogConceptPublished → writes an info log entry
 *
 * Because SerializesModels is used, the Concept is serialized by ID
 * and re-fetched when the event is unserialized (safe for queued listeners).
 */
class ConceptPublished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly Concept $concept) {}
}
