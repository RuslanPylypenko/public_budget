<?php

declare(strict_types=1);

namespace App\Session\Stage;

use App\Session\SessionEntity as Session;
use App\Session\Stage\StageEntity as Stage;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;

#[Mapping\Entity]
#[Mapping\Table(name: 'session_stage')]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class StageEntity
{
    public const STAGE_START       = 'start';
    public const STAGE_REVIEW      = 'review';
    public const STAGE_VOTING      = 'voting';
    public const STAGE_WINNERS     = 'winners';
    public const STAGE_REALIZATION = 'realization';

    public const STAGE_MAP = [
        self::STAGE_START,
        self::STAGE_REVIEW,
        self::STAGE_VOTING,
        self::STAGE_WINNERS,
        self::STAGE_REALIZATION,
    ];

    #[Mapping\Id]
    #[Mapping\Column(type: Types::BIGINT, options: ['unsigned' => true])]
    #[Mapping\GeneratedValue]
    private ?int $id = null;

    #[Mapping\Column(name: 'name', type: Types::STRING, length: 50)]
    private string $name;

    #[Mapping\Column(name: 'start', type: Types::DATETIME_MUTABLE)]
    private \DateTime $start;

    #[Mapping\Column(name: 'end', type: Types::DATETIME_MUTABLE)]
    private \DateTime $end;

    #[Mapping\Column(name: 'is_active', type: Types::BOOLEAN)]
    private bool $isActive;

    #[Mapping\ManyToOne(targetEntity: Session::class, inversedBy: 'stages')]
    #[Mapping\JoinColumn(name: 'session_id', nullable: false, onDelete: 'CASCADE')]
    private Session $session;

    // ----------------------------------------

    public function __construct(
        string $name,
        \DateTime $start,
        \DateTime $end,
        bool $isActive,
    ) {
        $this->start    = $start;
        $this->end      = $end;
        $this->isActive = $isActive;

        $this->setName($name);
    }

    // ----------------------------------------

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        if(!in_array($name, self::STAGE_MAP)){
            throw new \InvalidArgumentException("Can’t resolve status name: $name");
        }

        $this->name = $name;
    }

}