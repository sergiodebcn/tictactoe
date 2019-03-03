<?php

declare(strict_types=1);

namespace App\Application\Game\GetGame;

use App\Domain\Model\Game\Game;
use App\Domain\Model\Game\GameId;
use App\Domain\Model\Game\GameRepository;

class GetGame
{
    /** @var GameRepository */
    private $gameRepository;

    public function __construct(
        GameRepository $gameRepository
    ) {
        $this->gameRepository = $gameRepository;
    }

    public function execute(GameId $gameId): Game
    {
        return $this->gameRepository->findById($gameId);
    }
}
