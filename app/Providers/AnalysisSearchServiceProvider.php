<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\AnalysisSearchService;
use App\Services\ElasticSearchService;

class AnalysisSearchServiceProvider extends ServiceProvider
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

        $app->singleton(AnalysisSearchService::class, function ($app) {
            return new ElasticSearchService(
                $app->make('config')->get('database.elasticsearch')
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            AnalysisSearchService::class,
        ];
    }
}
