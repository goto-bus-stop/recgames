<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Http\JsonResponse;
use Neomerx\JsonApi\Document\Error;

class JsonApiException extends Exception
{
    protected $status;
    protected $name;
    protected $links = [];

    public function __construct(
        string $name,
        string $title,
        int $status = 500,
        Throwable $previous = null
    ) {
        $this->name = $name;
        $this->status = $status;
        parent::__construct($title, 0, $previous);
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Return the JSON-API error code. (Can't override getCode, so…)
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    public function links(array $links): JsonApiException
    {
        $this->links = $links;

        return $this;
    }

    public function response()
    {
        $error = new Error(
            null,
            null,
            $this->getStatus(),
            $this->getName(),
            $this->getMessage()
        );

        return response()->jsonapi($this->getStatus())
            ->links($this->links)
            ->error($error)
            ->response();
    }
}
