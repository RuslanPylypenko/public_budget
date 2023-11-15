<?php

namespace App\Tests\Unit\User\SignUp;

use App\User\ConfirmToken;
use App\User\UserEntity;
use App\Utils\DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = new UserEntity(
            $name = 'First',
            $surname = 'Last',
            'Patronymic',
            $email = 'test@app.test',
            new \DateTime('2010-10-10'),
            $confirmToken = new ConfirmToken('token', DateTime::current()->modify('+ 1 day')),
            'НE6652322',
            380000000000,
        );

        $user->setPassword('hash');

        $this->assertTrue($user->isNew());
        $this->assertFalse($user->isActive());

        $this->assertEquals($name, $user->getName());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($confirmToken, $user->getConfirmToken());
        $this->assertEquals('hash', $user->getPassword());

        $this->expectException(\DomainException::class);
        $user->getConfirmToken()->validate('token', DateTime::current()->modify('+ 2 day'));
    }
}