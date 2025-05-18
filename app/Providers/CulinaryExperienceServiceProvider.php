<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CulinaryExperienceService;

class CulinaryExperienceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CulinaryExperienceService::class, function ($app) {
            return new CulinaryExperienceService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 