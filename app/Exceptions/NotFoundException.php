<?php

namespace App\Exceptions;

use Throwable;

class NotFoundException extends JsonApiException
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct('not-found', $message, 404, $previous);
    }
}
