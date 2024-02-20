<?php

declare(strict_types=1);

namespace App\Project\Command\Reject;

use App\Common\CQRS\ICommand;
use App\Session\SessionEntity;

readonly class ProjectRejectCommand implements ICommand
{
    public function __construct(
        public int $projectNumber,
        public SessionEntity $session,
        public string $reason,
    ) {
    }
}
