<?php

declare(strict_types=1);

namespace App\Project\Address;

use App\Address\EntitySuperclass;
use App\Project\ProjectEntity as Project;
use Doctrine\ORM\Mapping;

#[Mapping\Entity()]
#[Mapping\Table(name: 'project_address')]
class AddressEntity extends EntitySuperclass
{
    #[Mapping\OneToOne(targetEntity: Project::class)]
    #[Mapping\JoinColumn(name: 'project_id', onDelete: 'CASCADE')]
    protected Project $project;
}