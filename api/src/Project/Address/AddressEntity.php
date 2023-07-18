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
    #[Mapping\OneToOne(inversedBy: 'address', targetEntity: Project::class)]
    #[Mapping\JoinColumn(name: 'project_id', onDelete: 'CASCADE')]
    protected Project $project;

    public function __construct(
        Project $project,
        string $region,
        string $city,
        string $street,
        float $lat,
        float $lon,
    ) {
        parent::__construct($region, $city, $street, $lat, $lon);
        $this->project = $project;
    }
}