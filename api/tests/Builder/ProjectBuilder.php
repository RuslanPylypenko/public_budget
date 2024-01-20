<?php

namespace App\Tests\Builder;

use App\Project\Category;
use App\Project\ProjectEntity;
use App\Project\ProjectEntity as Project;
use App\Session\SessionEntity;
use App\User\UserEntity;

class ProjectBuilder
{
    public function __construct(
        private readonly int $number = 1,
        private readonly Category $category = Category::CULTURE,
        private readonly float $budget = 999.99,
        private readonly string $name = 'Project',
        private readonly string $short = 'Short description',
        private readonly string $description = 'Description',
    ){
    }

    public function build(UserEntity $user, SessionEntity $session): ProjectEntity
    {
        return new ProjectEntity(
            number     : $this->number,
            category   : $this->category,
            status     : Project::STATUS_AWAIT,
            budget     : $this->budget,
            name       : $this->name,
            short      : $this->short,
            description: $this->description,
            author     : $user,
            session    : $session,
        );
    }
}