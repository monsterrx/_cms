<?php

use App\Http\Controllers\BugReportReviewController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;

Route::get('/bug-reports/{bugReport}', [BugReportReviewController::class, 'show'])
    ->whereNumber('bugReport')
    ->name('bug-reports.show');

Route::get('/bug-reports/{bugReport}/attachments/{attachment}', [BugReportReviewController::class, 'attachment'])
    ->whereNumber(['bugReport', 'attachment'])
    ->name('bug-reports.attachments.show');

Route::get('/dashboard', static fn () => Inertia::render('Dashboard'))
    ->name('dashboard');

Route::get('/under-construction/{section?}', static fn (?string $section = null) => Inertia::render(
    'Status/UnderConstruction',
    ['section' => Str::headline($section ?? 'This section')],
))->where('section', '[a-z0-9-]+')->name('under-construction');
