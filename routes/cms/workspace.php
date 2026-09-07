<?php

use App\Support\DesignationNavigation;
use App\Support\WorkspaceVisitRecorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/workspace/{section}/{item?}', static function (Request $request, DesignationNavigation $navigation, WorkspaceVisitRecorder $visits, string $section, ?string $item = null) {
    $selectedSection = collect(config('workspace.navigation'))->firstWhere('slug', $section);

    abort_if($selectedSection === null, 404);

    if ($item !== null) {
        $selectedItem = collect($selectedSection['groups'])
            ->flatMap(static fn (array $group) => $group['items'])
            ->firstWhere('slug', $item);

        abort_if($selectedItem === null, 404);
    }

    abort_unless($navigation->canView($request->user(), $section, $item), 403);
    $visits->record($request->user(), $section, $item);

    return Inertia::render('Workspace', [
        'sectionSlug' => $section,
        'itemSlug' => $item,
    ]);
})->where([
    'section' => '[a-z0-9-]+',
    'item' => '[a-z0-9-]+',
])->name('workspace');
