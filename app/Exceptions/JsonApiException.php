<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class JsonApiException extends Exception
{
    protected $status;
    protected $name;
    protected $links = [];

    public function __construct($name, $title, $status = 500, Throwable $previous = null)
    {
        $this->name = $name;
        $this->status = $status;
        parent::__construct($title, 0, $previous);
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Return the JSON-API error code. (Can't override getCode, so…)
     */
    public function getName()
    {
        return $this->name;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function links(array $links)
    {
        $this->links = $links;
    }

    public function response()
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
