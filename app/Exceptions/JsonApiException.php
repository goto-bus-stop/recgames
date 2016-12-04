<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Http\Response;

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

    public function response(): Response
    {
        return response()->json([
            'links' => $this->links,
            'errors' => [
                [
                    'code' => $this->getName(),
                    'title' => $this->getMessage(),
                ],
            ],
        ], $this->getStatus());
    }
}
