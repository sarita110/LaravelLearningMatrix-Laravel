<?php

namespace App\Listeners;

use App\Events\ConceptPublished;
use App\Jobs\SendConceptNotificationEmail;

/**
 * Reacts to ConceptPublished by dispatching an email job to the queue.
 * The HTTP response is not delayed — the job runs in the background.
 */
class NotifySubscribers
{
    public function handle(ConceptPublished $event): void
    {
        SendConceptNotificationEmail::dispatch($event->concept)
            ->delay(now()->addMinutes(1)); // Brief delay in case of rapid publish/unpublish
    }
}
