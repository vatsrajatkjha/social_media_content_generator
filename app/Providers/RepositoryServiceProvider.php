<?php

namespace App\Providers;

use App\Repositories\ContentRepository;
use App\Repositories\Interfaces\ContentRepositoryInterface;
use App\Services\ContentGeneratorService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ContentRepositoryInterface::class, ContentRepository::class);
        $this->app->singleton(ContentGeneratorService::class, function ($app) {
            return new ContentGeneratorService();
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
