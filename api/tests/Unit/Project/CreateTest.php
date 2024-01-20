<?php

namespace App\Tests\Unit\Project;

use App\Tests\Builder\ProjectBuilder;
use App\Tests\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->build();
        $project = (new ProjectBuilder())->build($user, '');
    }
}
