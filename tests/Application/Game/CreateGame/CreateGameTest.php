<?php

declare(strict_types=1);

namespace App\Application\Game\CreateGame;

use App\Domain\Model\Game\Board\SquareBoard;
use App\Domain\Model\Game\Board\Cell\Cell;
use App\Domain\Model\Game\Board\Cell\CellCollection;
use App\Domain\Model\Game\Board\SquareBoardBuilder;
use App\Domain\Model\Game\Game;
use App\Domain\Model\Game\GameId;
use App\Domain\Model\Game\GameRepository;
use App\Domain\Model\Game\Player\Player;
use App\Domain\Model\User\User;
use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserRepository;
use App\Infrastructure\Persistence\Model\Game\GameInMemoryRepository;
use App\Infrastructure\Persistence\Model\User\UserInMemoryRepository;
use PHPUnit\Framework\TestCase;

class CreateGameTest extends TestCase
{
    const FAKE_GAME_UUID = '7cb29ec7-f42b-403f-9a7e-b66f8552ab2a';
    const FAKE_PLAYER_ONE_USER_UUID = '61a4f72c-c0c4-4eaa-a544-8ee8bc4218cf';
    const FAKE_PLAYER_TWO_USER_UUID = 'cf0e6be1-45e7-4c19-98be-02cc8a7a0e9d';

    /** @var GameRepository */
    private $gameRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var SquareBoardBuilder */
    private $squareBoardBuilder;

    /** @var GameId */
    private $gameId;

    /** @var UserId */
    private $playerOneUserId;

    /** @var UserId */
    private $playerTwoUserId;

    protected function setUp(): void
    {
        $this->squareBoardBuilder = new SquareBoardBuilder();
        $this->userRepository = new UserInMemoryRepository();
        $this->gameRepository = new GameInMemoryRepository();
        $this->gameId = GameId::instance(self::FAKE_GAME_UUID);
        $this->playerOneUserId = UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID);
        $this->playerTwoUserId = UserId::instance(self::FAKE_PLAYER_TWO_USER_UUID);
    }

    public function testShouldCreateGame()
    {
        $this->userRepository->save(new User($this->playerOneUserId));
        $this->userRepository->save(new User($this->playerTwoUserId));
        $createGame = new CreateGame($this->gameRepository, $this->userRepository, $this->squareBoardBuilder);
        $createGame->execute($this->gameId, $this->playerOneUserId, $this->playerTwoUserId, 2);

        $createGame = $this->gameRepository->findById($this->gameId);
        $expectedGame = new Game(
            $this->gameId,
            new Player($this->playerOneUserId),
            new Player($this->playerTwoUserId),
            new SquareBoard(
                new CellCollection(
                    [new Cell(0, 0, null), new Cell(1, 0, null), new Cell(0, 1, null), new Cell(1, 1, null)]
                )
            )
        );

        $this->assertEquals(
            $expectedGame,
            $createGame
        );
    }
}
