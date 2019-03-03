<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Model\Game;

use App\Domain\Model\Game\Game;
use App\Domain\Model\Game\GameId;
use App\Domain\Model\Game\GameNotFoundException;
use App\Domain\Model\Game\GameRepository;

class GameInMemoryRepository implements GameRepository
{
    private $games = [];

    public function save(Game $game): void
    {
        $this->games[] = $game;
    }

    public function findById(GameId $gameId): Game
    {
        /** @var Game $game */
        foreach ($this->games as $game) {
            if ($game->id()->equals($gameId)) {
                return $game;
            }
        }

        throw new GameNotFoundException('Game with id '.$gameId.' not found');
    }
}
