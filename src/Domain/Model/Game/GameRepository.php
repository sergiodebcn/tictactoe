<?php

declare(strict_types=1);

namespace App\Domain\Model\Game;

interface GameRepository
{
    public function save(Game $game): void;

    public function findById(GameId $gameIdId): Game;
}
