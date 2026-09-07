<?php

use App\Support\DesignationNavigation;
use App\Support\WorkspaceVisitRecorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/workspace/digital-content-programs/shows/{record}', static function (Request $request, DesignationNavigation $navigation, WorkspaceVisitRecorder $visits, int $record) {
    abort_unless($navigation->canView($request->user(), 'digital-content-programs', 'shows'), 403);
    $visits->record($request->user(), 'digital-content-programs', 'shows');

    return Inertia::render('Shows/Edit', ['recordId' => $record]);
})->whereNumber('record')->name('shows.edit');

Route::get('/workspace/digital-content-programs/articles/{record}', static function (Request $request, DesignationNavigation $navigation, WorkspaceVisitRecorder $visits, int $record) {
    abort_unless($navigation->canView($request->user(), 'digital-content-programs', 'articles'), 403);
    $visits->record($request->user(), 'digital-content-programs', 'articles');

    return Inertia::render('Articles/Edit', ['recordId' => $record]);
})->whereNumber('record')->name('articles.edit');
