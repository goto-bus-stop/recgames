<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\AnalysisStorageService;
use App\Services\FilesystemStorageService;

class AnalysisStorageServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $app->singleton(
            AnalysisStorageService::class,
            FilesystemStorageService::class
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            AnalysisStorageService::class,
        ];
    }
}
