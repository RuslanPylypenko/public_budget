<?php

declare(strict_types=1);

namespace App\Project\Command\Update;

use App\Api\InputInterface;
use App\City\CityEntity;
use App\Common\CQRS\ICommand;
use App\Project\Category;
use App\Project\Command\Create\CreateProjectCommand;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateProjectCommand extends CreateProjectCommand implements InputInterface
{
    private ?int $number = null;

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): void
    {
        $this->number = $number;
    }
}
