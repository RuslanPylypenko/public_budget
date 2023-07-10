<?php

declare(strict_types=1);

namespace App\Session\Restriction;

use App\Category\CategoryEntity as Category;
use App\Session\SessionEntity as Session;
use App\Utils\DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;

#[Mapping\MappedSuperclass]
#[Mapping\Table()]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
abstract class ProjectSuperclass
{
    #[Mapping\Id]
    #[Mapping\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[Mapping\GeneratedValue]
    private ?int $id = null;

    #[Mapping\ManyToOne(targetEntity: Session::class, inversedBy: 'restrictionsBudget')]
    #[Mapping\JoinColumn(name: 'session_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Session $session;

    #[Mapping\Column(name: 'type', type: Types::STRING, length: 64)]
    private string $type;

    #[Mapping\Column(name: 'residents_only', type: Types::BOOLEAN)]
    private bool $residentsOnly;

    #[Mapping\Column(name: 'min_age', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $minAge;

    #[Mapping\Column(name: 'email_required', type: Types::BOOLEAN)]
    private bool $emailRequired;

    #[Mapping\Column(name: 'phone_required', type: Types::BOOLEAN)]
    private bool $phoneRequired;

    #[Mapping\Column(name: 'create_date', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected \DateTime $createDate;

    #[Mapping\Column(name: 'update_date', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected \DateTime $updateDate;

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
}