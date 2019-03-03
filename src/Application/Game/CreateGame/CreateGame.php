<?php

declare(strict_types=1);

namespace App\Application\Game\CreateGame;

use App\Domain\Model\Game\Board\SquareBoardBuilder;
use App\Domain\Model\Game\Game;
use App\Domain\Model\Game\GameId;
use App\Domain\Model\Game\GameRepository;
use App\Domain\Model\Game\Player\Player;
use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserRepository;

class CreateGame
{
    /** @var GameRepository */
    private $gameRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var SquareBoardBuilder */
    private $squareBoardBuilder;

    public function __construct(
        GameRepository $gameRepository,
        UserRepository $userRepository,
        SquareBoardBuilder $squareBoardBuilder
    ) {
        $this->gameRepository = $gameRepository;
        $this->userRepository = $userRepository;
        $this->squareBoardBuilder = $squareBoardBuilder;
    }

    public function execute(GameId $gameId, UserId $firstPlayerId, UserId $secondPlayerId, int $boardSize): void
    {
        $firstUser = $this->userRepository->findById($firstPlayerId);
        $secondUser = $this->userRepository->findById($secondPlayerId);

        $squareBoard = $this->squareBoardBuilder->build($boardSize);

        $newGame = new Game(
            $gameId,
            new Player($firstUser->id()),
            new Player($secondUser->id()),
            $squareBoard
        );
        $this->gameRepository->save($newGame);
    }
}
