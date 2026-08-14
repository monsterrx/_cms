<?php

namespace App\Providers;

use App\Support\Inertia\ApplicationResponseFactory;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Inertia\ResponseFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Inertia 0.6 duplicates Laravel's base URL when hosted in a subdirectory.
        $this->app->singleton(ResponseFactory::class, ApplicationResponseFactory::class);
        Inertia::clearResolvedInstance(ResponseFactory::class);
    }
}
