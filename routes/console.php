<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Example scheduled job: send weekly digest every Monday at 8am
// Schedule::job(new \App\Jobs\SendWeeklyDigest)->weeklyOn(1, '8:00');
