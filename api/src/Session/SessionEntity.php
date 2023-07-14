<?php

namespace App\Session;

use App\Category\CategoryEntity as Category;
use App\City\CityEntity;
use App\City\CityEntity as City;
use App\Session\Restriction\BudgetEntity as RestrictionBudget;
use App\Session\Restriction\ProjectAuthor;
use App\Session\Restriction\ProjectCount as RestrictionProjectCount;
use App\Session\Restriction\ProjectVote;
use App\Session\Stage\StageEntity as Stage;
use App\Utils\DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;

#[Mapping\Entity(repositoryClass: SessionRepository::class)]
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

    #[Mapping\Column(name: 'description', type: Types::STRING, length: 900)]
    private string $description;

    #[Mapping\Column(name: 'is_draft', type: Types::BOOLEAN)]
    private bool $isDraft;

//    #[Mapping\ManyToOne(targetEntity: City::class, inversedBy: 'sessions')]
//    #[Mapping\JoinColumn(name: 'city_id', nullable: false, onDelete: 'CASCADE')]
//    private City $city;

//    #[Mapping\OneToMany(mappedBy: 'session', targetEntity: Stage::class, cascade: ['persists'], indexBy: 'id')]
//    private Collection $stages;

    //#[Mapping\JoinTable(name: 'session_categories')]
   // #[Mapping\JoinColumn(name: 'session_id', referencedColumnName: 'id')]
//    #[Mapping\InverseJoinColumn(name: 'category_id', referencedColumnName: 'id', unique: true)]
//    #[Mapping\ManyToMany(targetEntity: SessionCategory::class, inversedBy: 'sessions')]
//    private Collection $categories;

//    #[Mapping\OneToMany(targetEntity: RestrictionBudget::class, cascade: ['persists'], indexBy: 'id')]
//    private Collection $restrictionsBudget;

//    #[Mapping\OneToMany(targetEntity: RestrictionBudget::class, cascade: ['persists'], indexBy: 'id')]
//    private Collection $restrictionsBudget;

//    #[Mapping\OneToMany(targetEntity: RestrictionProjectCount::class, cascade: ['persists'], indexBy: 'id')]
//    private Collection $restrictionsProjectCount;
//
//    #[Mapping\OneToOne(mappedBy: 'session', targetEntity: ProjectAuthor::class, cascade: ['persists'],)]
//    private ProjectAuthor $restrictionProjectAuthor;

//    #[Mapping\OneToOne(mappedBy: 'session', targetEntity: ProjectVote::class, cascade: ['persists'],)]
//    private ProjectVote $restrictionProjectVote;

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
       string $description,
     //  City $city,
       bool $isDraft = false,
   ) {
       $this->name                     = $name;
       $this->description              = $description;
      // $this->city                     = $city;
       $this->isDraft                  = $isDraft;
       $this->categories               = new ArrayCollection();
       $this->stages                   = new ArrayCollection();
       $this->restrictionsBudget       = new ArrayCollection();
       $this->restrictionsProjectCount = new ArrayCollection();
   }

    // ----------------------------------------

    public function addCategory(Category $category): self
    {
        if(!$this->categories->contains($category)){
            $this->categories[] = $category;
        }

        return $this;
    }

    // ----------------------------------------

    public function addStage(Stage $stage): self
    {
        if(!$this->stages->contains($stage)){
            $this->stages[] = $stage;
        }

        return $this;
    }

    // ----------------------------------------

    public function addRestrictionsBudget(RestrictionBudget $restriction): self
    {
        if(!$this->restrictionsBudget->contains($restriction)){
            $this->restrictionsBudget[] = $restriction;
        }

        return $this;
    }

    // ----------------------------------------

    public function addRestrictionsProjectCount(RestrictionProjectCount $restriction): self
    {
        if(!$this->restrictionsProjectCount->contains($restriction)){
            $this->restrictionsProjectCount[] = $restriction;
        }

        return $this;
    }

    // ----------------------------------------

    public function setRestrictionProjectAuthor(ProjectAuthor $restriction): self
    {

        $this->restrictionProjectAuthor = $restriction;

        return $this;
    }

    // ----------------------------------------

    public function setRestrictionsProjectsVote(ProjectVote $restriction): self
    {

        $this->restrictionProjectVote = $restriction;

        return $this;
    }

    // ----------------------------------------
}