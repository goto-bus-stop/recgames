<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Psr\Http\Message\ServerRequestInterface;
use Neomerx\JsonApi\Factories\Factory as JsonApiFactory;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;

class JsonApiServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('jsonapi.factory', JsonApiFactory::class);

        $this->app->bind(EncodingParametersInterface::class, function ($app) {
            $request = $app->make(ServerRequestInterface::class);
            $parser = $app->make('jsonapi.factory')->createQueryParametersParser();

            return $parser->parse($request);
        });

        $this->app->alias(EncodingParametersInterface::class, 'jsonapi.parameters');
    }
}
