<?php

namespace App\User\Api\Login;

use App\Api\InputInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Command implements InputInterface
{
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Assert\NotBlank]
    public string $email;

    #[Assert\NotBlank]
    public string $password;
}