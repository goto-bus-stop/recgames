<?php

namespace App\Http\Middleware;

use Closure;
use Neomerx\JsonApi\Document\Link;
use Neomerx\JsonApi\Encoder\{Encoder, EncoderOptions};

use App\Http\JsonApiResponse;
use App\Exceptions\JsonApiException;

class JsonApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonApiResponse) {
            return $response
                ->parameters(app('jsonapi.parameters'))
                ->response();
        }

        return $response;
    }
}
