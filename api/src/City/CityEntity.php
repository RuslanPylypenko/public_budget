<?php

declare(strict_types=1);

namespace App\City;

use App\Session\SessionEntity as Session;
use App\Utils\DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;

#[Mapping\Entity(repositoryClass: CityRepository::class)]
#[Mapping\Table(name: 'city')]
#[Mapping\HasLifecycleCallbacks]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class CityEntity
{
    #[Mapping\Id]
    #[Mapping\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[Mapping\GeneratedValue]
    private ?int $id = null;

    #[Mapping\Column(name: 'name', type: Types::STRING, length: 255)]
    private string $name;

    #[Mapping\Column(name: 'tech_name', type: Types::STRING, length: 140)]
    private string $techName;

    #[Mapping\Column(name: 'main_title', type: Types::STRING, length: 255)]
    private string $mainTitle;

    #[Mapping\Column(name: 'main_text', type: Types::TEXT, length: 900)]
    private string $mainText;

    #[Mapping\Column(name: 'lat', type: Types::FLOAT)]
    private float $lat;

    #[Mapping\Column(name: 'lon', type: Types::FLOAT)]
    private float $lon;

    #[Mapping\OneToMany(mappedBy: 'city', targetEntity: Session::class, cascade: ['persist'], indexBy: 'id')]
    private Collection $sessions;

    #[Mapping\Column(name: 'update_date', type: Types::DATETIME_MUTABLE)]
    private \DateTime $updateDate;

    #[Mapping\Column(name: 'create_date', type: Types::DATETIME_MUTABLE)]
    private \DateTime $createDate;

    // ----------------------------------------

    #[Mapping\PreUpdate]
    public function preUpdate(): void
    {
        $this->updateDate = DateTime::current();
    }

    #[Mapping\PrePersist]
    public function prePersist(): void
    {
        $this->updateDate = DateTime::current();
        $this->createDate = DateTime::current();
    }

    // ----------------------------------------

    public function __construct(
        string $name,
        string $techName,
        string $mainTitle,
        string $mainText,
        float $lat,
        float $lon,
    ) {
        $this->name      = $name;
        $this->techName  = $techName;
        $this->mainTitle = $mainTitle;
        $this->mainText  = $mainText;
        $this->lat       = $lat;
        $this->lon       = $lon;
    }

    // ----------------------------------------

    public function toArray(): array
    {
        return [
            'name'      => $this->name,
            'techName'  => $this->techName,
            'mainTitle' => $this->mainTitle,
            'mainText'  => $this->mainText,
            'lat'       => $this->lat,
            'lon'       => $this->lon
        ];
    }

    /**
     * @return Collection
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function getCurrentSession(): Session
    {
        return $this->sessions->first();
    }
}
