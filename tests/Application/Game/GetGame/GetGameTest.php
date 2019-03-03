<?php

declare(strict_types=1);

namespace App\Application\Game\GetGame;

use App\Domain\Model\Game\Board\Cell\Cell;
use App\Domain\Model\Game\Board\Cell\CellCollection;
use App\Domain\Model\Game\Board\SquareBoard;
use App\Domain\Model\Game\Game;
use App\Domain\Model\Game\GameId;
use App\Domain\Model\Game\GameRepository;
use App\Domain\Model\Game\Player\Player;
use App\Domain\Model\User\UserId;
use App\Infrastructure\Persistence\Model\Game\GameInMemoryRepository;
use PHPStan\Testing\TestCase;

class GetGameTest extends TestCase
{
    const FAKE_GAME_UUID = '7cb29ec7-f42b-403f-9a7e-b66f8552ab2a';
    const FAKE_PLAYER_ONE_USER_UUID = '61a4f72c-c0c4-4eaa-a544-8ee8bc4218cf';
    const FAKE_PLAYER_TWO_USER_UUID = 'cf0e6be1-45e7-4c19-98be-02cc8a7a0e9d';

    /** @var Game */
    private $game;
    /** @var GameId */
    private $gameId;
    /** @var Player */
    private $playerOne;
    /** @var Player */
    private $playerTwo;
    /** @var SquareBoard */
    private $board;
    /** @var GetGame */
    private $getGame;
    /** @var GameRepository */
    private $gameRepository;

    protected function setUp(): void
    {
        $this->gameId = GameId::instance(self::FAKE_GAME_UUID);
        $this->playerOne = new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID));
        $this->playerTwo = new Player(UserId::instance(self::FAKE_PLAYER_TWO_USER_UUID));
        $this->board = new SquareBoard(
            new CellCollection(
                [new Cell(0, 0, null), new Cell(1, 0, null), new Cell(0, 1, null), new Cell(1, 1, null)]
            )
        );
        $this->game = new Game($this->gameId, $this->playerOne, $this->playerTwo, $this->board);

        $this->gameRepository = new GameInMemoryRepository();
        $this->gameRepository->save($this->game);
    }

    public function testShouldGetGame()
    {
        $this->getGame = new GetGame($this->gameRepository);
        $game = $this->getGame->execute($this->gameId);
        $this->assertEquals(
            $game,
            $this->game
        );
    }
}
