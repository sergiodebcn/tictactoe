<?php

declare(strict_types=1);

namespace App\Application\Game\GetGame;

use App\Domain\Model\Game\GameId;
use App\Domain\Shared\Bus\Query\Response;

class GetGameResponse implements Response
{
    /** @var string */
    private $gameId;
    /** @var bool */
    private $finished;
    /** @var ?array */
    private $winner;
    /** @var array */
    private $nextPlayer;

    public function __construct(string $gameId, bool $finished, ?array $winner, array $nextPlayer)
    {
        $this->gameId = $gameId;
        $this->finished = $finished;
        $this->winner = $winner;
        $this->nextPlayer = $nextPlayer;
    }

    public function gameId(): string
    {
        return $this->gameId;
    }

    public function finished(): bool
    {
        return $this->finished;
    }

    public function winner(): ?array
    {
        return $this->winner;
    }

    public function nextPlayer(): array
    {
        return $this->nextPlayer;
    }
}
