<?php

declare(strict_types=1);

namespace App\User;

use App\Auth\AuthEntitySuperclass;
use App\Project\ProjectEntity as Project;
use App\ProjectVote\ProjectVoteEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;

#[Mapping\Entity(repositoryClass: Repository::class)]
#[Mapping\Table(name: 'user')]
#[Mapping\UniqueConstraint(name: 'email', columns: ['email'])]
class UserEntity extends AuthEntitySuperclass
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_NEW    = 'new';
    public const STATUS_BANNED = 'banned';

    #[Mapping\Column(name: 'name', type: Types::STRING, length: 255)]
    private string $name;

    #[Mapping\Column(name: 'surname', type: Types::STRING, length: 255)]
    private string $surname;

    #[Mapping\Column(name: 'patronymic', type: Types::STRING, length: 255)]
    private string $patronymic;

    #[Mapping\Column(name: 'passport', type: Types::STRING, length: 255, nullable: true)]
    private ?string $passport;

    #[Mapping\Column(name: 'phone', type: Types::STRING, length: 12, nullable: true)]
    private ?string $phone;

    #[Mapping\Column(name: 'status', type: Types::STRING, length: 30)]
    private string $status;

    #[Mapping\Column(name: 'ban_reason', type: Types::STRING, length: 255, nullable: true)]
    private ?string $banReason = null;

    #[Mapping\Column(name: 'birthday', type: Types::DATETIME_MUTABLE)]
    private \DateTime $birthday;

    #[Mapping\Embedded(class: ConfirmToken::class, columnPrefix: 'confirm_token_')]
    private ?ConfirmToken $confirmToken;

    #[Mapping\OneToMany(mappedBy: 'project', targetEntity: Project::class, cascade: ['persist'])]
    private Collection $projects;

    #[Mapping\OneToMany(mappedBy: 'user', targetEntity: ProjectVoteEntity::class, cascade: ['persist'], indexBy: 'id')]
    private Collection $votes;

    // ----------------------------------------

    public function __construct(
        string $name,
        string $surname,
        string $patronymic,
        string $email,
        \DateTime $birthday,
        ConfirmToken $confirmToken,
        ?string $passport = null,
        ?string $phone = null,
    ) {
        parent::__construct(
            $email
        );

        $this->name         = $name;
        $this->surname      = $surname;
        $this->patronymic   = $patronymic;
        $this->passport     = $passport;
        $this->phone        = $phone;
        $this->birthday     = $birthday;
        $this->status       = self::STATUS_NEW;
        $this->confirmToken = $confirmToken;

        $this->projects     = new ArrayCollection();
    }

    // ----------------------------------------

    public function getName(): string
    {
        return $this->name;
    }

    public function getFullName(): string
    {
        return trim(sprintf('%s %s %s', $this->name, $this->surname, $this->patronymic));
    }

    public function setName(string $name): void
    {
        $this->name = $name;
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

    public function isBanned(): bool
    {
        return $this->status === self::STATUS_BANNED;
    }

    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }

    public function moveToBanned(string $reason): void
    {
        if ($this->isBanned()) {
            throw new \DomainException('User is already banned');
        }

        $this->status = self::STATUS_BANNED;
        $this->banReason = $reason;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('User is already active');
        }

        $this->banReason = null;

        $this->status = self::STATUS_ACTIVE;
    }

    public function getBanReason(): ?string
    {
        return $this->banReason;
    }

    // ----------------------------------------

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getConfirmToken(): ?ConfirmToken
    {
        return $this->confirmToken;
    }

    public function setConfirmToken(?ConfirmToken $confirmToken): void
    {
        $this->confirmToken = $confirmToken;
    }

    public function confirmSignUp(): void
    {
        if (!$this->isNew()) {
            throw new \DomainException('User is already confirmed.');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    // ----------------------------------------

    public function getVotes(): Collection
    {
        return $this->votes;
    }

    // ----------------------------------------

    public function toArray(): array
    {
        return [
            'name'       => $this->name,
            'surname'    => $this->surname,
            'patronymic' => $this->patronymic,
            'passport'   => $this->passport,
            'phone'      => $this->phone,
            'email'      => $this->getEmail(),
            'status'     => $this->status,
        ];
    }

    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function setVotes(Collection $votes): void
    {
        $this->votes = $votes;
    }
}