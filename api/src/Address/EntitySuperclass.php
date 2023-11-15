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

    #[Mapping\Column(name: 'country', type: Types::STRING, length: 120, nullable: true)]
    protected ?string $country;

    #[Mapping\Column(name: 'country_code', type: Types::STRING, length: 120, nullable: true)]
    protected ?string $countryCode;

    #[Mapping\Column(name: 'region', type: Types::STRING, length: 120, nullable: true)]
    protected ?string $region;

    #[Mapping\Column(name: 'city', type: Types::STRING, length: 120, nullable: true)]
    protected ?string $city;

    #[Mapping\Column(name: 'district', type: Types::STRING, length: 120, nullable: true)]
    protected ?string $district;

    #[Mapping\Column(name: 'street', type: Types::STRING, length: 120, nullable: true)]
    protected ?string $street;

    #[Mapping\Column(name: 'building', type: Types::STRING, length: 120, nullable: true)]
    protected ?string $building;

    #[Mapping\Column(name: 'apartment', type: Types::STRING, length: 120, nullable: true)]
    protected ?string $apartment;

    #[Mapping\Column(name: 'postcode', type: Types::STRING, length: 120, nullable: true)]
    protected ?float $postcode;

    #[Mapping\Column(name: 'lat', type: Types::FLOAT, nullable: true)]
    protected ?float $lat;

    #[Mapping\Column(name: 'lon', type: Types::FLOAT, nullable: true)]
    protected ?float $lon;

    // ----------------------------------------

    public function __construct(
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
        $this->country = $country;
        $this->countryCode = $countryCode;
        $this->region = $region;
        $this->city = $city;
        $this->district = $district;
        $this->street = $street;
        $this->building = $building;
        $this->apartment = $apartment;
        $this->postcode = $postcode;
        $this->lat = $lat;
        $this->lon = $lon;
    }

    public function getLocalAddress(): string
    {
        return implode(', ', array_filter([$this->city, $this->district, $this->street, $this->building, $this->apartment]));
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function getBuilding(): ?string
    {
        return $this->building;
    }

    public function getApartment(): ?string
    {
        return $this->apartment;
    }

    public function getPostcode(): ?float
    {
        return $this->postcode;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function getLon(): ?float
    {
        return $this->lon;
    }

    public function toArray(): array
    {
        return [
            'postcode' => $this->getPostcode(),
            'apartment' => $this->getApartment(),
            'street' => $this->getStreet(),
            'building' => $this->getBuilding(),
            'district' => $this->getDistrict(),
            'city' => $this->getCity(),
            'region' => $this->getRegion(),
            'country' => $this->getCountry(),
            'country_code' => $this->getCountryCode(),
            'lat' => $this->getLat(),
            'lon' => $this->getLon(),
        ];
    }
}