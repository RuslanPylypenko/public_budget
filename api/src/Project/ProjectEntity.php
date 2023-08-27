<?php

declare(strict_types=1);

namespace App\Project;

use App\Project\Address\AddressEntity as Address;
use App\Session\SessionEntity as Session;
use App\User\UserEntity as User;
use App\Utils\DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;

#[Mapping\Entity]
#[Mapping\Table(name: 'project')]
#[Mapping\HasLifecycleCallbacks]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class ProjectEntity
{
    public const STATUS_REJECTED       = 'rejected';
    public const STATUS_REJECTED_FULLY = 'rejected fully';
    public const STATUS_TAKE_PART      = 'take part';
    public const STATUS_IMPOSSIBLE     = 'impossible to implement';
    public const STATUS_IMPLEMENTED    = 'implemented';

    #[Mapping\Id]
    #[Mapping\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[Mapping\GeneratedValue]
    private ?int $id = null;

    #[Mapping\Column(name: 'number', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $number;

    #[Mapping\Column(name: 'status', type: Types::STRING, length: 32)]
    private string $status;

    #[Mapping\Column(name: 'budget', type: Types::FLOAT)]
    private float $budget;

    #[Mapping\Column(name: 'name', type: Types::STRING, length: 255)]
    private string $name;

    #[Mapping\Column(name: 'short', type: Types::TEXT, length: 900)]
    private string $short;

    #[Mapping\Column(name: 'description', type: Types::TEXT)]
    private string $description;

    #[Mapping\ManyToOne(targetEntity: User::class, inversedBy: 'projects')]
    #[Mapping\JoinColumn('author_id', onDelete: 'CASCADE')]
    private User $author;

    #[Mapping\ManyToOne(targetEntity: Session::class, inversedBy: 'projects')]
    #[Mapping\JoinColumn(name: 'session_id', onDelete: 'CASCADE')]
    private Session $session;

    #[Mapping\OneToOne(mappedBy: 'project', targetEntity: Address::class, cascade: ['persist'])]
    protected ?Address $address = null;

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
        int $number,
        string $status,
        float $budget,
        string $name,
        string $short,
        string $description,
        User $author,
        Session $session,

    ) {
        $this->number = $number;
        $this->status = $status;
        $this->budget = $budget;
        $this->name = $name;
        $this->short = $short;
        $this->description = $description;
        $this->author = $author;
        $this->session = $session;
    }

    // ----------------------------------------

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getBudget(): float
    {
        return $this->budget;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getShort(): string
    {
        return $this->short;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function toArray(): array
    {
        return [
            'name'   => $this->getName(),
            'number' => $this->getNumber(),
            'status' => $this->getStatus(),
            'budget' => $this->getBudget(),
            'short'  => $this->getShort(),
        ];
    }
}
