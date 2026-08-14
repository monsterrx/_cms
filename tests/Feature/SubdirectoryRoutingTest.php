<?php

namespace Tests\Feature;

use App\Support\Inertia\ApplicationResponse;
use App\Support\Inertia\ApplicationResponseFactory;
use Illuminate\Http\Request;
use Inertia\ResponseFactory;
use Tests\TestCase;

class SubdirectoryRoutingTest extends TestCase
{
    public function test_application_uses_the_subdirectory_safe_response_factory(): void
    {
        $this->assertInstanceOf(
            ApplicationResponseFactory::class,
            $this->app->make(ResponseFactory::class)
        );
    }

    public function test_inertia_page_url_does_not_duplicate_the_application_subdirectory(): void
    {
        $request = Request::create(
            'https://example.test/rxcms_v3/login',
            'GET',
            [],
            [],
            [],
            [
                'SCRIPT_NAME' => '/rxcms_v3/index.php',
                'SCRIPT_FILENAME' => public_path('index.php'),
                'PHP_SELF' => '/rxcms_v3/index.php',
                'REQUEST_URI' => '/rxcms_v3/login',
                'HTTP_X_INERTIA' => 'true',
            ]
        );

        $this->assertSame('/rxcms_v3', $request->getBaseUrl());

        $response = (new ApplicationResponse('Auth/Login', []))->toResponse($request);

        $this->assertSame('/rxcms_v3/login', $response->getData(true)['url']);
    }
}
