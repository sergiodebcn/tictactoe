<?php

declare(strict_types=1);

namespace App\Domain\Model\Game;

use App\Domain\Model\Game\Board\SquareBoard;
use App\Domain\Model\Game\Board\Cell\Cell;
use App\Domain\Model\Game\Board\Cell\CellCollection;
use App\Domain\Model\Game\Player\Player;
use App\Domain\Model\User\UserId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
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
    }

    public function testShouldReturnGameId()
    {
        $this->assertEquals($this->game->id(), $this->gameId);
    }

    public function testShouldReturnPlayerOne()
    {
        $this->assertEquals($this->game->playerOne(), $this->playerOne);
    }

    public function testShouldReturnPlayerTwo()
    {
        $this->assertEquals($this->game->playerTwo(), $this->playerTwo);
    }

    public function testShouldReturnBoard()
    {
        $this->assertEquals($this->game->board(), $this->board);
    }

    public function testShouldReturnNullIfThereIsNoWinner()
    {
        $this->assertNull($this->game->winner());
    }

    public function testShouldReturnGameIsNotFinished()
    {
        $this->assertFalse($this->game->isFinished());
    }

    public function testShouldReturnGameFinishedIfThereIsAWinner()
    {
        $gameId = GameId::instance(self::FAKE_GAME_UUID);
        $playerOne = new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID));
        $playerTwo = new Player(UserId::instance(self::FAKE_PLAYER_TWO_USER_UUID));
        $board = new SquareBoard(
            new CellCollection(
                [new Cell(0, 0, $playerOne), new Cell(1, 0, $playerOne), new Cell(0, 1, null), new Cell(1, 1, null)]
            )
        );
        $game = new Game($gameId, $playerOne, $playerTwo, $board);
        $this->assertTrue($game->isFinished());
    }

    public function testShouldReturnGameFinishedIfAllBoardPlayedAndNotWinner()
    {
        $gameId = GameId::instance(self::FAKE_GAME_UUID);
        $playerOne = new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID));
        $playerTwo = new Player(UserId::instance(self::FAKE_PLAYER_TWO_USER_UUID));
        $board = new SquareBoard(
            new CellCollection(
                [
                    new Cell(0, 0, $playerOne),
                    new Cell(1, 0, $playerTwo),
                    new Cell(2, 0, $playerOne),
                    new Cell(0, 1, $playerTwo),
                    new Cell(1, 1, $playerOne),
                    new Cell(2, 1, $playerTwo),
                    new Cell(0, 2, $playerOne),
                    new Cell(1, 2, $playerOne),
                    new Cell(2, 2, $playerTwo),
                ]
            )
        );
        $game = new Game($gameId, $playerOne, $playerTwo, $board);
        $this->assertTrue($game->isFinished());
    }

    public function testShouldTestInitialMove()
    {
        $this->game->nextMove(0, 0);
        $this->assertEquals(
            $this->game->board(),
            new SquareBoard(
                new CellCollection(
                    [
                        new Cell(0, 0, $this->playerOne),
                        new Cell(1, 0, null),
                        new Cell(0, 1, null),
                        new Cell(1, 1, null),
                    ]
                )
            )
        );
        $this->assertEquals($this->playerOne->numberOfMoves(), 1);
    }

    public function testShouldThrowExceptionIfTryToCreateAGameWithSameUserIdForPlayers()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Players must have different user id');
        new Game($this->gameId, $this->playerOne, $this->playerOne, $this->board);
    }

    public function testShouldPlayAGameAndWinPlayerOne()
    {
        $this->game->nextMove(0, 0);
        $this->game->nextMove(0, 1);
        $this->game->nextMove(1, 0);

        $this->assertEquals(
            $this->game->board(),
            new SquareBoard(
                new CellCollection(
                    [
                        new Cell(0, 0, $this->playerOne),
                        new Cell(1, 0, $this->playerOne),
                        new Cell(0, 1, $this->playerTwo),
                        new Cell(1, 1, null),
                    ]
                )
            )
        );
        $this->assertEquals($this->playerOne->numberOfMoves(), 2);
        $this->assertEquals($this->playerTwo->numberOfMoves(), 1);
        $this->assertEquals($this->game->winner(), $this->playerOne);
    }

    public function testShouldThrowExceptionIfTryToMoveButGameIsFinished()
    {
        $gameId = GameId::instance(self::FAKE_GAME_UUID);
        $playerOne = new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID));
        $playerTwo = new Player(UserId::instance(self::FAKE_PLAYER_TWO_USER_UUID));
        $board = new SquareBoard(
            new CellCollection(
                [new Cell(0, 0, $playerOne), new Cell(1, 0, $playerOne), new Cell(0, 1, null), new Cell(1, 1, null)]
            )
        );

        $this->expectException(GameFinishedException::class);
        $this->expectExceptionMessage('Game finished, not more moves allowed');

        $game = new Game($gameId, $playerOne, $playerTwo, $board);
        $game->nextMove(0, 0);
    }

    public function testShouldBuildGameFromArray()
    {
        $this->assertEquals(
            $this->game,
            Game::fromArray($this->game->toArray())
        );
    }

    public function testShouldReturnGameToArray()
    {
        $this->assertEquals(
            $this->game->toArray(),
            [
                'id' => $this->gameId->value(),
                'playerOne' => $this->playerOne->toArray(),
                'playerTwo' => $this->playerTwo->toArray(),
                'board' => $this->board->toArray(),
            ]
        );
    }
}
