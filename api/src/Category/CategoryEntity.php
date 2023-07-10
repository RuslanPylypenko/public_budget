<?php

declare(strict_types=1);

namespace App\Category;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;

#[Mapping\Entity]
#[Mapping\Table(name: 'category')]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class CategoryEntity
{
    #[Mapping\Id]
    #[Mapping\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[Mapping\GeneratedValue]
    private ?int $id = null;

    #[Mapping\Column(name: 'name', type: Types::STRING, length: 255)]
    private string $name;

    // -----------------------------------

    public function __construct(
        string $name,
    ) {
    }
}
