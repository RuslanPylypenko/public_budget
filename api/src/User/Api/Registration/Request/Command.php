<?php

declare(strict_types=1);

namespace App\User\Api\Registration\Request;

use App\Api\InputInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Command  implements InputInterface
{
    #[Assert\NotBlank, Assert\Length(null, 1, 255)]
    public string $name;

    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Assert\NotBlank]
    public string $email;

    #[Assert\Date]
    public string $birthday;

    #[Assert\NotBlank, Assert\Length(null, 6, 255)]
    public string $password;

    #[Assert\NotBlank, Assert\Length(null, 6, 255)]
    public string $rePassword;

    #[Assert\IsTrue(message: 'The rePasswordField has to the same as password')]
    public function isPasswordSafe(): bool
    {
       return $this->password === $this->rePassword;
    }
}