<?php

declare(strict_types=1);

namespace App\Project\Command\Approve;

use App\Common\CQRS\ICommand;
use App\Session\SessionEntity;

readonly class ProjectApproveCommand implements ICommand
{
    public function __construct(
        public int $projectNumber,
        public SessionEntity $session,
    ) {
    }
}
