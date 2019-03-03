<?php

declare(strict_types=1);

namespace App\Application\Game\UpdateGameWithMove;

use App\Domain\Model\Game\GameId;
use App\Domain\Model\Game\GameRepository;

class UpdateGameWithMove
{
    /** @var GameRepository */
    private $gameRepository;

    public function __construct(
        GameRepository $gameRepository
    ) {
        $this->gameRepository = $gameRepository;
    }

    public function execute(GameId $gameId, int $xPosition, int $yPosition): void
    {
        $game = $this->gameRepository->findById($gameId);
        $game->nextMove($xPosition, $yPosition);
        $this->gameRepository->save($game);
    }
}
