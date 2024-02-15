<?php

declare(strict_types=1);

namespace App\Project\Address;

use App\Address\AddressEntitySuperclass;
use App\Project\ProjectEntity as Project;
use Doctrine\ORM\Mapping;

#[Mapping\Entity()]
#[Mapping\Table(name: 'project_address')]
class AddressAddressEntity extends AddressEntitySuperclass
{
    #[Mapping\OneToOne(inversedBy: 'address', targetEntity: Project::class)]
    #[Mapping\JoinColumn(name: 'project_id', onDelete: 'CASCADE')]
    protected Project $project;

    public function __construct(
        Project $project,
        ?string $country = null,
        ?string $countryCode = null,
        ?string $region = null,
        ?string $city = null,
        ?string $district = null,
        ?string $street = null,
        ?string $building = null,
        ?string $apartment = null,
        ?string $postcode = null,
        ?float $lat = null,
        ?float $lon = null,
    ) {
        parent::__construct(
            $country,
            $countryCode,
            $region,
            $city,
            $district,
            $street,
            $building,
            $apartment,
            $postcode,
            $lat,
            $lon
        );
        $this->project = $project;
    }
}