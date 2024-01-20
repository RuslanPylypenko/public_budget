<?php

namespace App\Tests\Unit\User\SignUp;

use App\Tests\Builder\UserBuilder;
use App\User\UserEntity;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{
    private UserEntity $user;

    public function testSuccess(): void
    {
        $this->user->confirmSignUp();

        $this->assertTrue($this->user->isActive());
        $this->assertFalse($this->user->isNew());

        $this->assertNull($this->user->getConfirmToken());
    }

    public function testAlready(): void
    {
        $this->user->confirmSignUp();
        $this->expectExceptionMessage('User is already confirmed.');
        $this->user->confirmSignUp();
    }

    protected function setUp(): void
    {
        $this->user = (new UserBuilder())->build();
    }
}