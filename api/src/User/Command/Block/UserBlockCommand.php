<?php

declare(strict_types=1);

namespace App\User\Command\Block;

use App\Api\InputInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserBlockCommand implements InputInterface
{
    #[Assert\NotBlank]
    public int $userId;

    #[Assert\NotBlank]
    public string $reason;
}
