<?php

declare(strict_types=1);

namespace App\Session;

use Doctrine\ORM\Mapping;

#[Mapping\Entity]
#[Mapping\Table(name: 'session_category')]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class SessionCategory
{

}