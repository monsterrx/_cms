<?php

namespace App\Http\Middleware;

use App\Support\StationContext;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $stations = app(StationContext::class);

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'navigation' => config('workspace.navigation'),
            'station' => [
                'current' => $stations->current($request),
                'can_switch' => $stations->canSwitch($request->user()),
                'options' => $stations->options(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }
}
