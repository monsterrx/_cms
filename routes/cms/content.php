<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/workspace/digital-content-programs/shows/{record}', static fn (int $record) => Inertia::render(
    'Shows/Edit',
    ['recordId' => $record],
))->whereNumber('record')->name('shows.edit');

Route::get('/workspace/digital-content-programs/articles/{record}', static fn (int $record) => Inertia::render(
    'Articles/Edit',
    ['recordId' => $record],
))->whereNumber('record')->name('articles.edit');
