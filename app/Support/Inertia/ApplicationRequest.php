<?php

namespace App\Support\Inertia;

use Illuminate\Http\Request;

/**
 * Presents the browser URI to Inertia without re-appending Laravel's base URL.
 */
class ApplicationRequest extends Request
{
    public function getBaseUrl(): string
    {
        return '';
    }
}
