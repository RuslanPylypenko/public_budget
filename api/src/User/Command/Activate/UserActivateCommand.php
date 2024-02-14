<?php

declare(strict_types=1);

namespace App\User\Command\Activate;

use App\Api\InputInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserActivateCommand implements InputInterface
{
    #[Assert\NotBlank]
    public int $userId;
}
