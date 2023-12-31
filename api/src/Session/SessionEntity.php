<?php

declare(strict_types=1);

namespace App\Session;

use App\City\CityEntity as City;
use App\Project\ProjectEntity as Project;
use App\Utils\DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;

#[Mapping\Entity]
#[Mapping\Table(name: 'session')]
#[Mapping\HasLifecycleCallbacks]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class SessionEntity
{
    #[Mapping\Id]
    #[Mapping\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[Mapping\GeneratedValue]
    private ?int $id = null;

    #[Mapping\Column(name: 'name', type: Types::STRING, length: 255)]
    private string $name;

    #[Mapping\ManyToOne(targetEntity: City::class, inversedBy: 'sessions')]
    #[Mapping\JoinColumn(name: 'city_id', nullable: false, onDelete: 'CASCADE')]
    private City $city;

    #[Mapping\OneToMany(mappedBy: 'session', targetEntity: StageEntity::class, cascade: ['persist'], indexBy: 'id')]
    private Collection $stages;

    #[Mapping\OneToMany(mappedBy: 'session', targetEntity: Project::class, cascade: ['persist'], indexBy: 'id')]
    private Collection $projects;

    #[Mapping\Column(name: 'project_status_update_last_run_date', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $projectStatusUpdateLastRunDate;

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
        City $city,
        ?\DateTime $projectStatusUpdateLastRunDate = null,
    ) {
        $this->name = $name;
        $this->city = $city;
        $this->projectStatusUpdateLastRunDate = $projectStatusUpdateLastRunDate;
    }

    // ----------------------------------------


    public function getCity(): City
    {
        return $this->city;
    }

    // ----------------------------------------

    public function toArray(): array
    {
        return [
            'name'   => $this->name,
            'stages' => array_values(
                array_map(static fn(StageEntity $stage) => $stage->toArray(), $this->stages->toArray())
            ),
            'projects' => array_values(
                array_map(static fn(Project $p) => $p->toArray(), $this->projects->slice(0, 5))
            )
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStage(): ?StageEntity
    {
        $now = DateTime::current();
        foreach ($this->getStages() as $stage){
           if($stage->isEnable() && $stage->getStartDate() <= $now && $stage->getEndDate() >= $now){
               return $stage;
           }
        }
        return null;
    }

    /**
     * @return Collection<int, StageEntity>
     */
    public function getStages(): Collection
    {
        return $this->stages;
    }

    public function getProjectStatusUpdateLastRunDate(): ?\DateTime
    {
        return $this->projectStatusUpdateLastRunDate;
    }

    public function setProjectStatusUpdateLastRunDate(?\DateTime $projectStatusUpdateLastRunDate): void
    {
        $this->projectStatusUpdateLastRunDate = $projectStatusUpdateLastRunDate;
    }
}
