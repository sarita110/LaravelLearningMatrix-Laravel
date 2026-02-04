<?php

namespace App\Jobs;

use App\Mail\NewConceptMail;
use App\Models\Concept;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * Sends a notification email to all admin users when a concept is published.
 *
 * ShouldQueue → this job runs in the background (queue worker), not inline.
 * SerializesModels → the Concept is serialized by ID and re-fetched when the job runs.
 *
 * Run the worker: php artisan queue:work
 * Watch the queue: php artisan queue:listen --queue=default
 */
class SendConceptNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Number of times the job may be attempted before failing. */
    public int $tries = 3;

    /** Number of seconds to wait before retrying after failure. */
    public int $backoff = 60;

    public function __construct(public readonly Concept $concept) {}

    /**
     * Execute the job.
     * This method runs inside the queue worker process — not during an HTTP request.
     */
    public function handle(): void
    {
        $admins = User::where('is_admin', true)->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)
                ->send(new NewConceptMail($this->concept));
        }
    }

    /** Called when all retries are exhausted. */
    public function failed(\Throwable $exception): void
    {
        \Log::error('SendConceptNotificationEmail job failed', [
            'concept_id' => $this->concept->id,
            'error'      => $exception->getMessage(),
        ]);
    }
}
