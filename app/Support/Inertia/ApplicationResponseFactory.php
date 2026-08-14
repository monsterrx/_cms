<?php

namespace App\Support\Inertia;

use Illuminate\Contracts\Support\Arrayable;
use Inertia\ResponseFactory;

class ApplicationResponseFactory extends ResponseFactory
{
    /**
     * @param  array<string, mixed>|Arrayable  $props
     */
    public function render(string $component, $props = []): ApplicationResponse
    {
        if ($props instanceof Arrayable) {
            $props = $props->toArray();
        }

        return new ApplicationResponse(
            $component,
            array_merge($this->sharedProps, $props),
            $this->rootView,
            $this->getVersion()
        );
    }
}
