<?php

declare(strict_types=1);

namespace App\Application\Game\CreateGame;

use App\Domain\Shared\Bus\Command\Command;

class CreateGameCommand implements Command
{
    /** @var string */
    private $gameId;
    /** @var string */
    private $playerOneId;
    /** @var string */
    private $playerTwoId;
    /** @var int */
    private $boardSize;

    public function __construct(string $gameId, string $playerOneId, string $playerTwoId, int $boardSize)
    {
        $this->gameId = $gameId;
        $this->playerOneId = $playerOneId;
        $this->playerTwoId = $playerTwoId;
        $this->boardSize = $boardSize;
    }

    public function gameId(): string
    {
        return $this->gameId;
    }

    public function playerOneId(): string
    {
        return $this->playerOneId;
    }

    public function playerTwoId(): string
    {
        return $this->playerTwoId;
    }

    public function boardSize(): int
    {
        return $this->boardSize;
    }
}
