<?php

namespace App\Project\Api\Find;

use App\Api\InputInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Command implements InputInterface
{
    #[Assert\NotBlank(allowNull: true)]
    public string $search;
}