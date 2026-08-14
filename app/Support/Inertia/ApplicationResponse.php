<?php

namespace App\Support\Inertia;

use Illuminate\Http\Request;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ApplicationResponse extends Response
{
    /**
     * Prevent Inertia 0.6 from duplicating a subdirectory in the page URL.
     */
    public function toResponse($request): SymfonyResponse
    {
        if (! $request instanceof Request) {
            return parent::toResponse($request);
        }

        return parent::toResponse(ApplicationRequest::createFrom($request));
    }
}
