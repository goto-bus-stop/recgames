<?php

namespace App\Http;

use Illuminate\Http\JsonResponse;
use Neomerx\JsonApi\Document\{Link, Error};
use Neomerx\JsonApi\Encoder\{Encoder, EncoderOptions};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;

class JsonApiResponse extends BaseResponse
{
    /**
     * JSON-API encoder to use.
     *
     * @var \Neomerx\JsonApi\Contracts\Encoder\EncoderInterface
     */
    private $encoder;

    /**
     * Encoding parameters to use.
     *
     * @var \Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface
     */
    private $parameters;

    /**
     * Data to send in the response.
     *
     * @var mixed
     */
    private $data;

    /**
     * Constructor.
     *
     * @param int  $status  HTTP status.
     * @return void
     */
    public function __construct($status = 200)
    {
        $this->encoder = Encoder::instance(
            config('jsonapi.schemas'),
            new EncoderOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES, route('api.base'))
        )->withJsonApiVersion();

        parent::__construct('', $status);
    }

    /**
     * Create a Link instance from a URL string.
     *
     * @return \Neomerx\JsonApi\Contracts\Document\LinkInterface
     */
    private function makeLink($target)
    {
        if (!$target) {
            return null;
        }
        return new Link($target, null, true);
    }

    /**
     * Configure links for the response.
     *
     * @param array  $links  Links as an associative array, string name =>
     *     string url.
     * @return $this
     */
    public function links($links)
    {
        $this->encoder->withLinks(array_map([&$this, 'makeLink'], $links));

        return $this;
    }

    /**
     * Send a single resource in the response.
     *
     * @return this
     */
    public function single($resource)
    {
        $this->data = $resource;

        return $this;
    }

    /**
     * Send a list of resources in the response.
     *
     * @return this
     */
    public function list($resources)
    {
        $this->data = $resources;

        if ($resources instanceof LengthAwarePaginator) {
            $this->links([
                Link::FIRST => $resources->url(0),
                Link::LAST => $resources->url($resources->lastPage()),
                Link::PREV => $resources->previousPageUrl(),
                Link::NEXT => $resources->nextPageUrl(),
            ]);
        }

        return $this;
    }

    /**
     * Send an empty response.
     *
     * @return $this
     */
    public function empty()
    {
        $this->data = null;

        return $this;
    }

    /**
     * Send an Error response.
     *
     * @param \Neomerx\JsonApi\Document\Error  $error  Error object.
     * @return $this
     */
    public function error(Error $error)
    {
        $this->data = $error;

        return $this;
    }

    /**
     * Configure encoding parameters for the response.
     *
     * @param \Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface  $parameters
     * @return $this
     */
    public function parameters(EncodingParametersInterface $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Finalise the response.
     *
     * @return \Symfony\Component\HttpFoundation\Response Response object ready
     *     for further use by Laravel.
     */
    public function response(): BaseResponse
    {
        if ($this->data instanceof Error) {
            $this->setContent($this->encoder->encodeError($this->data));
        } else {
            $this->setContent($this->encoder->encodeData($this->data, $this->parameters));
        }

        return $this;
    }
}
