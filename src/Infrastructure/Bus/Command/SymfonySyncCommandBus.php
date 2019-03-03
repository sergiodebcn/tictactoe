<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Command;

use App\Domain\Shared\Bus\Command\Command;
use App\Domain\Shared\Bus\Command\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonySyncCommandBus implements CommandBus
{
    private $bus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->bus = $messageBus;
    }

    public function dispatch(Command $command): void
    {
        $this->bus->dispatch($command);
    }
}
