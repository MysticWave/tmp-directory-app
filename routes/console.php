<?php

use App\Console\Commands\AI\Process;
use App\Console\Commands\Lobstrio\CheckRuns;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(CheckRuns::class)
    ->everyMinute()
    ->withoutOverlapping()
    ->onOneServer();

Schedule::command(Process::class)
    ->everyMinute()
    ->withoutOverlapping()
    ->onOneServer();
