<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use App\Jobs\GenerateSalesReportsJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new GenerateSalesReportsJob())
    ->everyTwoMinutes()
    ->withoutOverlapping(2);
