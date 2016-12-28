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
    private $encoder;
    private $parameters;
    private $data;

    /**
     * Constructor.
     */
    public function __construct($status = 200)
    {
        $this->encoder = Encoder::instance(
            config('jsonapi.schemas'),
            new EncoderOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES, route('api.base'))
        )->withJsonApiVersion();

        parent::__construct('', $status);
    }

    private function makeLink($target)
    {
        if (!$target) {
            return null;
        }
        return new Link($target, null, true);
    }

    public function links($links)
    {
        $this->encoder->withLinks(array_map([&$this, 'makeLink'], $links));

        return $this;
    }

    public function single($model)
    {
        $this->data = $model;

        return $this;
    }

    public function list($data)
    {
        $this->data = $data;

        if ($data instanceof LengthAwarePaginator) {
            $this->links([
                'first' => $data->url(0),
                'last' => $data->url($data->lastPage()),
                'prev' => $data->previousPageUrl(),
                'next' => $data->nextPageUrl(),
            ]);
        }

        return $this;
    }

    public function empty()
    {
        $this->data = null;

        return $this;
    }

    public function error(Error $error)
    {
        $this->data = $error;

        return $this;
    }

    public function parameters(EncodingParametersInterface $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function response()
    {
        if ($this->data instanceof Error) {
            $this->setContent($this->encoder->encodeError($this->data));
        } else {
            $this->setContent($this->encoder->encodeData($this->data, $this->parameters));
        }

        return $this;
    }
}
