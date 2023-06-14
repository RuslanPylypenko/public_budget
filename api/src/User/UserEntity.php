<?php

namespace App\User;
use App\DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;

#[Mapping\Entity(repositoryClass: UserRepository::class)]
#[Mapping\Table(name: 'user')]
#[Mapping\HasLifecycleCallbacks]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class UserEntity
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_NEW    = 'new';

    #[Mapping\Id]
    #[Mapping\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[Mapping\GeneratedValue]
    private ?int $id = null;

    #[Mapping\Column(name: 'name', type: Types::STRING, length: 255)]
    private string $name;

    #[Mapping\Column(name: 'email', type: Types::STRING, unique: true)]
    private string $email;

    #[Mapping\Column(name: 'status', type: Types::STRING, length: 30)]
    private string $status;

    #[Mapping\Column(name: 'birthday', type: Types::DATETIME_MUTABLE)]
    private \DateTime $birthday;

    #[Mapping\Column(name: 'password_hash', type: Types::STRING, length: 100)]
    private string $passwordHash;

    #[Mapping\Embedded(class: ConfirmToken::class, columnPrefix: 'confirm_token_')]
    private ?ConfirmToken $confirmToken;

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
        string $name,
        string $email,
        \DateTime $birthday,
        string $passwordHash,
        ConfirmToken $confirmToken,
    ) {
        $this->name         = $name;
        $this->email        = $email;
        $this->birthday     = $birthday;
        $this->passwordHash = $passwordHash;
        $this->status       = self::STATUS_NEW;
        $this->confirmToken = $confirmToken;
    }

    // ----------------------------------------

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getBirthday(): \DateTime
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTime $birthday): void
    {
        $this->birthday = $birthday;
    }

    // ----------------------------------------

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }

    // ----------------------------------------

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getUpdateDate(): \DateTime
    {
        return $this->updateDate;
    }

    public function getCreateDate(): \DateTime
    {
        return $this->createDate;
    }

    public function getConfirmToken(): ?ConfirmToken
    {
        return $this->confirmToken;
    }
}