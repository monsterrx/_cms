<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Http\Controllers\BugReportReviewController;

Route::get('/', function () {
    return redirect(route('dashboard', [], false));
});

Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->middleware('guest')->name('login');

Route::middleware('auth')->group(function (): void {
    Route::get('/bug-reports/{bugReport}', [BugReportReviewController::class, 'show'])
        ->whereNumber('bugReport')
        ->name('bug-reports.show');
    Route::get('/bug-reports/{bugReport}/attachments/{attachment}', [BugReportReviewController::class, 'attachment'])
        ->whereNumber(['bugReport', 'attachment'])
        ->name('bug-reports.attachments.show');

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/under-construction/{section?}', function (?string $section = null) {
        return Inertia::render('Status/UnderConstruction', [
            'section' => Str::headline($section ?? 'This section'),
        ]);
    })->where('section', '[a-z0-9-]+')->name('under-construction');

    Route::get('/workspace/digital-content-programs/shows/{record}', function (int $record) {
        return Inertia::render('Shows/Edit', ['recordId' => $record]);
    })->whereNumber('record')->name('shows.edit');

    Route::get('/workspace/digital-content-programs/articles/{record}', function (int $record) {
        return Inertia::render('Articles/Edit', ['recordId' => $record]);
    })->whereNumber('record')->name('articles.edit');

    Route::get('/workspace/{section}/{item?}', function (string $section, ?string $item = null) {
        $selectedSection = collect(config('workspace.navigation'))
            ->firstWhere('slug', $section);

        abort_if($selectedSection === null, 404);

        if ($item !== null) {
            $selectedItem = collect($selectedSection['groups'])
                ->flatMap(fn (array $group) => $group['items'])
                ->firstWhere('slug', $item);

            abort_if($selectedItem === null, 404);
        }

        return Inertia::render('Workspace', [
            'sectionSlug' => $section,
            'itemSlug' => $item,
        ]);
    })->where([
        'section' => '[a-z0-9-]+',
        'item' => '[a-z0-9-]+',
    ])->name('workspace');
});

Route::get('/status/{status}', function (int $status) {
    abort($status);
})->whereIn('status', [400, 401, 403, 404, 405, 408, 409, 419, 422, 429, 500, 502, 503, 504]);

require __DIR__.'/auth.php';
