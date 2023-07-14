<?php

declare(strict_types=1);

namespace App\Session;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;

#[Mapping\Entity]
#[Mapping\Table(name: 'session_category')]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class SessionCategory
{
    #[Mapping\Id]
    #[Mapping\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[Mapping\GeneratedValue]
    private ?int $id = null;
}