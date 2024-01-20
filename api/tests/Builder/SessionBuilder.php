<?php

namespace App\Tests\Builder;

use App\Project\Category;
use App\Project\ProjectEntity;
use App\Project\ProjectEntity as Project;
use App\Session\SessionEntity;
use App\User\UserEntity;

class SessionBuilder
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

    public function build(): SessionEntity
    {
        return new SessionEntity(

        );
    }
}