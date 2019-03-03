<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Model\Game;

use App\Domain\Model\Game\Board\SquareBoard;
use App\Domain\Model\Game\Board\Cell\Cell;
use App\Domain\Model\Game\Board\Cell\CellCollection;
use App\Domain\Model\Game\Game;
use App\Domain\Model\Game\GameId;
use App\Domain\Model\Game\GameNotFoundException;
use App\Domain\Model\Game\Player\Player;
use App\Domain\Model\User\UserId;
use PHPUnit\Framework\TestCase;
use ReflectionObject;

class GameInMemoryRepositoryTest extends TestCase
{
    /** @var GameInMemoryRepository $repository */
    private $repository;

    /** @var Game */
    private $game;

    const FAKE_GAME_UUID = 'dcdef34c-b00a-4892-b866-329d93088857';
    const FAKE_NOT_SAVED_GAME_UUID = 'aadef34c-b00a-4892-b866-329d93088857';
    const FAKE_PLAYER_ONE_UUID = 'dcdef34c-b00a-4892-b866-329d93088857';
    const FAKE_PLAYER_TWO_UUID = 'dcdef34c-b00a-4892-b866-329d93088858';

    public function setUp(): void
    {
        $this->repository = new GameInMemoryRepository();
        $this->game = new Game(
            GameId::instance(self::FAKE_GAME_UUID),
            new Player(UserId::instance(self::FAKE_PLAYER_ONE_UUID)),
            new Player(UserId::instance(self::FAKE_PLAYER_TWO_UUID)),
            new SquareBoard(
                new CellCollection(
                    [new Cell(0, 0, null), new Cell(1, 0, null), new Cell(0, 1, null), new Cell(1, 1, null)]
                )
            )
        );
    }

    public function testShouldSaveUserInRepository()
    {
        $this->repository->save($this->game);

        //Check value inside repository by reflection
        $reflectionObject = new ReflectionObject($this->repository);
        $userProperty = $reflectionObject->getProperty('games');
        $userProperty->setAccessible(true);

        $games = $userProperty->getValue($this->repository);
        $game = reset($games);

        $this->assertTrue($game instanceof Game);
    }

    public function testShouldRecoverUserFromRepository()
    {
        $this->repository->save($this->game);
        $game = $this->repository->findById(GameId::instance(self::FAKE_GAME_UUID));
        $this->assertTrue(self::FAKE_GAME_UUID === $game->id()->value());
    }

    public function testShouldThrowExceptionIfUserNotFound()
    {
        $this->expectException(GameNotFoundException::class);
        $this->repository->save($this->game);
        $this->repository->findById(GameId::instance(self::FAKE_NOT_SAVED_GAME_UUID));
    }
}
