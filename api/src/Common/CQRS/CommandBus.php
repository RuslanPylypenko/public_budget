<?php

declare(strict_types=1);

namespace App\Common\CQRS;

interface CommandBus
{
    public function dispatch(ICommand $command): void;
}
