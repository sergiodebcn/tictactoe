<?php

declare(strict_types=1);

namespace App\Domain\Shared\Bus\Command;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
