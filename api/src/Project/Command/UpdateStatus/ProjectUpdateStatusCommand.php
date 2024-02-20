<?php

declare(strict_types=1);

namespace App\Project\Command\UpdateStatus;

use App\Common\CQRS\ICommand;
use App\Project\ProjectStatus;
use App\Session\SessionEntity;

readonly class ProjectUpdateStatusCommand implements ICommand
{
    public function __construct(
        public int $projectNumber,
        public SessionEntity $session,
        public ProjectStatus $status,
    ) {
    }
}
