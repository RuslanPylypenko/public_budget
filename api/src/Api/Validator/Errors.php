<?php

declare(strict_types=1);

namespace App\Api\Validator;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class Errors
{
    public function __construct(
        private readonly ConstraintViolationListInterface $violations
    ) {
    }

    public function toArray(): array
    {
        $errors = [];
        foreach ($this->violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }
        return $errors;
    }
}
