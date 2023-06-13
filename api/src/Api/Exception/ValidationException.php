<?php

namespace App\Api\Exception;

use App\Api\Validator\Errors;

class ValidationException extends \LogicException
{
    public function __construct(
        private readonly Errors $errors
    ) {
        parent::__construct('Validation errors.');
    }

    public function getErrors(): Errors
    {
        return $this->errors;
    }
}