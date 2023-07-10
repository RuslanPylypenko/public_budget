<?php

declare(strict_types=1);

namespace App\Session\Restriction;

use Doctrine\ORM\Mapping;

#[Mapping\Entity]
#[Mapping\Table(name: 'session_restriction_project_vote')]
class ProjectVote extends ProjectSuperclass
{

}