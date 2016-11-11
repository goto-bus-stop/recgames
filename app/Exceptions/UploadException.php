<?php

namespace App\Exceptions;

use Throwable;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadError extends JsonApiException
{
    public function __construct(UploadedFile $file, Throwable $previous = null)
    {
        parent::__construct('upload-error', $file->getErrorMessage(), 400, $previous);
    }
}
