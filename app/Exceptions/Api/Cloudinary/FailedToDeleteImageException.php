<?php

namespace App\Exceptions\Api\Cloudinary;

use Exception;

class FailedToDeleteImageException extends Exception
{
    public function report(): ?bool
    {
        // You can log the exception to an external service if needed
        // report($this);

        return false;
    }
}
