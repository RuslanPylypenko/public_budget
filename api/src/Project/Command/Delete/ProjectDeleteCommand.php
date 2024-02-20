<?php

declare(strict_types=1);

namespace App\Project\Command\Delete;

use App\Common\CQRS\ICommand;
use App\Session\SessionEntity;

readonly class ProjectDeleteCommand implements ICommand
{
    public function __construct(
        public int           $projectNumber,
        public SessionEntity $session,
    ) {
    }
}
