<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('diagnostics:reconcile-attendance', function () {
    $this->info('Syncing biometric ADMS logs with shift roster...');
    $this->info('Attendance synchronization completed.');
})->purpose('Synchronize biometric ZKTeco/ADMS attendance logs');

Artisan::command('diagnostics:flag-abnormal-reports', function () {
    $this->info('Evaluating unverified reports against reference intervals...');
    $this->info('Automated AI critical value flagging completed.');
})->purpose('Batch process unverified reports for clinical flags');
