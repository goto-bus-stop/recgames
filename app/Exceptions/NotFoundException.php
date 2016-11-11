<?php

namespace App\Exceptions;

use Throwable;

class NotFoundException extends JsonApiException
{
    public function __construct($message, Throwable $previous = null)
    {
        parent::__construct('not-found', $message, 404, $previous);
    }
}
