<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\AnalysisStorageService;
use App\Services\ElasticSearchService;

class AnalysisStorageServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $app->singleton(ElasticSearchService::class, function () {
            return new ElasticSearchService();
        });

        $app->bind(AnalysisStorageService::class, ElasticSearchService::class);

        $app->alias(AnalysisStorageService::class, 'analysis-storage');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [AnalysisStorageService::class];
    }
}
