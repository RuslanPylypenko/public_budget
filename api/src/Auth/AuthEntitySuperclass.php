<?php

declare(strict_types=1);

namespace App\Auth;

use App\Http\Annotation\Authenticate\AuthInterface;
use App\Utils\DateTime;
use App\Utils\Random;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Mapping\MappedSuperclass]
#[Mapping\Table()]
#[Mapping\HasLifecycleCallbacks]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
abstract class AuthEntitySuperclass implements AuthInterface, PasswordAuthenticatedUserInterface
{
    #[Mapping\Id]
    #[Mapping\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[Mapping\GeneratedValue]
    private ?int $id = null;

    #[Mapping\Column(name: 'email', type: Types::STRING, unique: true)]
    private string $email;

    #[Mapping\Column(name: 'password_hash', type: Types::STRING, length: 100)]
    private string $passwordHash;

    #[Mapping\Column(name: 'hash_session', type: Types::STRING, length: 64, options: ['fixed' => true])]
    private string $hashSession;

    #[Mapping\Column(name: 'update_date', type: Types::DATETIME_MUTABLE)]
    private \DateTime $updateDate;

    #[Mapping\Column(name: 'create_date', type: Types::DATETIME_MUTABLE)]
    private \DateTime $createDate;

    // ----------------------------------------

    #[Mapping\PreUpdate]
    public function preUpdate(): void
    {
        $this->updateDate = DateTime::current();
    }

    #[Mapping\PrePersist]
    public function prePersist(): void
    {
        $this->updateDate = DateTime::current();
        $this->createDate = DateTime::current();
    }

    // ----------------------------------------

    public function __construct(
        string $email,
    ) {
        $this->email        = $email;
        $this->hashSession  = Random::getRandomString();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email       = $email;
        $this->hashSession = Random::getRandomString();
    }

    public function getHashSession(): string
    {
       return $this->hashSession;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getPassword(): ?string
    {
        return $this->passwordHash;
    }

    public function setPassword(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
        $this->hashSession  = Random::getRandomString();
    }

    public function getUpdateDate(): \DateTime
    {
        return $this->updateDate;
    }

    public function getCreateDate(): \DateTime
    {
        return $this->createDate;
    }
}
