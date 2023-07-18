<?php

declare(strict_types=1);

namespace App\Address;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;

#[Mapping\MappedSuperclass]
#[Mapping\Table()]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
abstract class EntitySuperclass
{
    #[Mapping\Id]
    #[Mapping\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[Mapping\GeneratedValue]
    protected string $id;

    #[Mapping\Column(name: 'region', type: Types::STRING, length: 120)]
    protected string $region;

    #[Mapping\Column(name: 'city', type: Types::STRING, length: 120)]
    protected string $city;

    #[Mapping\Column(name: 'street', type: Types::STRING, length: 120)]
    protected string $street;

    #[Mapping\Column(name: 'lat', type: Types::FLOAT)]
    protected float $lat;

    #[Mapping\Column(name: 'lon', type: Types::FLOAT)]
    protected float $lon;

    // ----------------------------------------

    public function __construct(
        string $region,
        string $city,
        string $street,
        float $lat,
        float $lon,
    ) {
        $this->region = $region;
        $this->city = $city;
        $this->street = $street;
        $this->lat = $lat;
        $this->lon = $lon;
    }
}