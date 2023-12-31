<?php

namespace App\User;

use App\Http\Annotation\Authenticate\UserInterface;
use App\Project\ProjectEntity as Project;
use App\ProjectVote\ProjectVoteEntity;
use App\Session\StageEntity;
use App\Utils\DateTime;
use App\Utils\Random;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Mapping\Entity(repositoryClass: Repository::class)]
#[Mapping\Table(name: 'user')]
#[Mapping\HasLifecycleCallbacks]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class UserEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_NEW    = 'new';

    #[Mapping\Id]
    #[Mapping\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[Mapping\GeneratedValue]
    private ?int $id = null;

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

    #[Mapping\Column(name: 'email', type: Types::STRING, unique: true)]
    private string $email;

    #[Mapping\Column(name: 'status', type: Types::STRING, length: 30)]
    private string $status;

    #[Mapping\Column(name: 'birthday', type: Types::DATETIME_MUTABLE)]
    private \DateTime $birthday;

    #[Mapping\Column(name: 'password_hash', type: Types::STRING, length: 100)]
    private string $passwordHash;

    #[Mapping\Column(name: 'hash_session', type: Types::STRING, length: 64, options: ['fixed' => true])]
    private string $hashSession;

    #[Mapping\Embedded(class: ConfirmToken::class, columnPrefix: 'confirm_token_')]
    private ?ConfirmToken $confirmToken;

    #[Mapping\OneToMany(mappedBy: 'project', targetEntity: Project::class, cascade: ['persist'])]
    private Collection $projects;

    #[Mapping\OneToMany(mappedBy: 'user', targetEntity: ProjectVoteEntity::class, cascade: ['persist'], indexBy: 'id')]
    private Collection $votes;

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
        string $surname,
        string $patronymic,
        string $email,
        \DateTime $birthday,
        ConfirmToken $confirmToken,
        ?string $passport = null,
        ?string $phone = null,
    ) {
        $this->name         = $name;
        $this->surname      = $surname;
        $this->patronymic   = $patronymic;
        $this->passport     = $passport;
        $this->phone        = $phone;
        $this->email        = $email;
        $this->birthday     = $birthday;
        $this->status       = self::STATUS_NEW;
        $this->confirmToken = $confirmToken;

        $this->projects     = new ArrayCollection();

        $this->hashSession = Random::getRandomString();
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email       = $email;
        $this->hashSession = Random::getRandomString();
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

    public function setConfirmToken(?ConfirmToken $confirmToken): void
    {
        $this->confirmToken = $confirmToken;
    }

    // ----------------------------------------

    public function getVotes(): Collection
    {
        return $this->votes;
    }

    // ----------------------------------------

    public function getPassword(): ?string
    {
        return $this->passwordHash;
    }

    public function setPassword(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
        $this->hashSession  = Random::getRandomString();
    }

    public function getHashSession(): string
    {
        return $this->hashSession;
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
            'email'      => $this->email,
            'status'     => $this->status,
        ];
    }
}