<?php

declare(strict_types=1);

namespace App\Api\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{
    public function __construct(
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function validate($object): ?Errors
    {
        $violations = $this->validator->validate($object);
        if ($violations->count() > 0) {
            return new Errors($violations);
        }
        return null;
    }
}
