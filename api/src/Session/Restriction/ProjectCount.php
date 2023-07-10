<?php

declare(strict_types=1);

namespace App\Session\Restriction;

use App\Category\CategoryEntity as Category;
use App\Session\SessionEntity as Session;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;

#[Mapping\Entity]
#[Mapping\Table(name: 'session_restriction_project_count')]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class ProjectCount
{
    #[Mapping\Id]
    #[Mapping\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[Mapping\GeneratedValue]
    private ?int $id = null;

    #[Mapping\ManyToOne(targetEntity: Category::class)]
    #[Mapping\JoinColumn(name: 'category_id', nullable: true)]
    private ?Category $category;

    #[Mapping\ManyToOne(targetEntity: Session::class, inversedBy: 'restrictionsBudget')]
    #[Mapping\JoinColumn(name: 'session_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Session $session;

    #[Mapping\Column(name: 'max_projects_count', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $maxProjectCount;
}