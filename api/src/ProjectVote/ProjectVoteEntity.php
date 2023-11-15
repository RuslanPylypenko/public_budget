<?php

namespace App\ProjectVote;

use App\Project\ProjectEntity;
use App\User\UserEntity as User;
use App\Project\ProjectEntity as Project;
use App\Utils\DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;

#[Mapping\Entity]
#[Mapping\Table(name: 'project_vote')]
#[Mapping\UniqueConstraint(name: 'project_id_user_id', columns: ['project_id', 'user_id'])]
#[Mapping\HasLifecycleCallbacks]
#[Mapping\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class ProjectVoteEntity
{
    #[Mapping\Id]
    #[Mapping\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[Mapping\GeneratedValue]
    private ?int $id = null;

    #[Mapping\ManyToOne(targetEntity: User::class)]
    #[Mapping\JoinColumn('user_id', onDelete: 'CASCADE')]
    private User $user;

    #[Mapping\ManyToOne(targetEntity: ProjectEntity::class)]
    #[Mapping\JoinColumn('project_id', onDelete: 'CASCADE')]
    private Project $project;

    #[Mapping\Column(name: 'ip', type: Types::STRING, length: 15, nullable: true)]
    private ?string $ip;

    #[Mapping\Column(name: 'user_agent', type: Types::STRING, length: 244)]
    private string $userAgent;

    #[Mapping\Column(name: 'browser', type: Types::STRING, length: 244, nullable: true)]
    private ?string $browser;

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
        User $user,
        Project $project,
        ?string $ip,
        string $userAgent,
        ?string $browser,
    ) {
      $this->user = $user;
      $this->project = $project;
      $this->ip = $ip;
      $this->userAgent = $userAgent;
      $this->browser = $browser;
    }

}