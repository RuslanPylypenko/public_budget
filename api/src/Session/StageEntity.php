<?php

declare(strict_types=1);

namespace App\Session;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;
use App\Session\SessionEntity as Session;

#[Mapping\Entity]
#[Mapping\Table(name: 'session_stage')]
#[Mapping\HasLifecycleCallbacks]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class StageEntity
{
    public const STAGE_SUBMISSION = "submission";
    public const STAGE_REVIEW = "review";
    public const STAGE_VOTING = "voting";
    public const STAGE_DECISION = "decision";
    public const STAGE_IMPLEMENTATION = "implementation";


    public const STAGES = [
        self::STAGE_SUBMISSION,
        self::STAGE_REVIEW,
        self::STAGE_VOTING,
        self::STAGE_DECISION,
        self::STAGE_IMPLEMENTATION,
    ];

    #[Mapping\Id]
    #[Mapping\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[Mapping\GeneratedValue]
    private ?int $id = null;

    #[Mapping\Column(name: 'name', type: Types::STRING, length: 255)]
    private string $name;

    #[Mapping\ManyToOne(targetEntity: Session::class, inversedBy: 'stages')]
    #[Mapping\JoinColumn(name: 'session_id', nullable: false, onDelete: 'CASCADE')]
    private Session $session;

    #[Mapping\Column(name: 'is_enable', type: Types::BOOLEAN)]
    private bool $isEnable;

    #[Mapping\Column(name: 'start_date', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $startDate;

    #[Mapping\Column(name: 'end_date', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $endDate;

    // ----------------------------------------

    public function __construct(
        string $name,
        Session $session,
        bool $isEnable,
        ?DateTime $startDate = null,
        ?DateTime $endDate = null,
    )
    {
        $this->name = $name;
        $this->session = $session;
        $this->isEnable = $isEnable;

        if($isEnable){
            $this->startDate = $startDate;
            $this->endDate = $endDate;
        }
    }

    // ----------------------------------------

    public function toArray(): array
    {
        return [
            'name'      => $this->name,
            'isEnable'  => $this->isEnable,
            'startDate' => $this->startDate,
            'endDate'   => $this->endDate,
        ];
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function isEnable(): bool
    {
        return $this->isEnable;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
