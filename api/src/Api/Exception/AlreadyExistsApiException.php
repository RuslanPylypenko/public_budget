<?php

namespace App\Api\Exception;

class AlreadyExistsApiException extends ApiException
{
    public function __construct(string $message = null)
    {
        parent::__construct($message ?? 'Entity already exists', 400);
    }
}