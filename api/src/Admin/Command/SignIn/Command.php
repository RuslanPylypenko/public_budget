<?php

namespace App\Admin\Command\SignIn;

use App\Api\InputInterface;
use App\Common\CQRS\ICommand;
use Symfony\Component\Validator\Constraints as Assert;

class Command implements InputInterface, ICommand
{
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Assert\NotBlank]
    public string $email;

    #[Assert\NotBlank]
    public string $password;
}