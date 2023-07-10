<?php

declare(strict_types=1);

namespace App\Session\Restriction;

use App\Category\CategoryEntity as Category;
use App\Session\SessionEntity as Session;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;

#[Mapping\Entity]
#[Mapping\Table(name: 'session_restriction_budget')]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class BudgetEntity
{
    #[Mapping\Id]
    #[Mapping\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[Mapping\GeneratedValue]
    private ?int $id = null;

    #[Mapping\Column(name: 'by_type', type: Types::STRING, length: 255, nullable: true)]
    private ?string $byType;

    #[Mapping\ManyToOne(targetEntity: Category::class)]
    #[Mapping\JoinColumn(name: 'category_id', nullable: true)]
    private ?Category $category;

    #[Mapping\ManyToOne(targetEntity: Session::class, inversedBy: 'restrictionsBudget')]
    #[Mapping\JoinColumn(name: 'session_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Session $session;

    #[Mapping\Column(name: 'total_budget', type: Types::FLOAT)]
    private float $totalBudget;

    #[Mapping\Column(name: 'min_budget', type: Types::FLOAT)]
    private float $minBudget;

    #[Mapping\Column(name: 'max_budget', type: Types::FLOAT)]
    private float $maxBudget;

    #[Mapping\Column(name: 'min_votes', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $minVotes;
}