<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Query;

use App\Domain\Shared\Bus\Query\Query;
use App\Domain\Shared\Bus\Query\QueryBus;
use App\Domain\Shared\Bus\Query\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class SymfonySyncQueryBus implements QueryBus
{
    private $bus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->bus = $messageBus;
    }

    public function ask(Query $query): Response
    {
        $envelope = $this->bus->dispatch($query);

        return $envelope->last(HandledStamp::class)->getResult();
    }
}
