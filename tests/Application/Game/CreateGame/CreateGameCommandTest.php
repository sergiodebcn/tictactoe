<?php

declare(strict_types=1);

namespace App\Application\Game\CreateGame;

use PHPUnit\Framework\TestCase;

class CreateGameCommandTest extends TestCase
{
    const FAKE_GAME_UUID = '7cb29ec7-f42b-403f-9a7e-b66f8552ab2a';
    const FAKE_PLAYER_ONE_USER_UUID = '61a4f72c-c0c4-4eaa-a544-8ee8bc4218cf';
    const FAKE_PLAYER_TWO_USER_UUID = 'cf0e6be1-45e7-4c19-98be-02cc8a7a0e9d';

    /** @var string */
    private $gameId;
    /** @var string */
    private $playerOneUserId;
    /** @var string */
    private $playerTwoUserId;
    /** @var CreateGameCommand */
    private $createGameCommand;
    /** @var int */
    private $boardSize;

    protected function setUp(): void
    {
        $this->gameId = self::FAKE_GAME_UUID;
        $this->playerOneUserId = self::FAKE_PLAYER_ONE_USER_UUID;
        $this->playerTwoUserId = self::FAKE_PLAYER_TWO_USER_UUID;
        $this->boardSize = 3;
        $this->createGameCommand = new CreateGameCommand(
            $this->gameId,
            $this->playerOneUserId,
            $this->playerTwoUserId,
            $this->boardSize
        );
    }

    public function testShouldReturnGameId()
    {
        $this->assertEquals($this->createGameCommand->gameId(), $this->gameId);
    }

    public function testShouldReturnPlayerOneUserId()
    {
        $this->assertEquals($this->createGameCommand->playerOneId(), $this->playerOneUserId);
    }

    public function testShouldReturnPlayerTwoUserId()
    {
        $this->assertEquals($this->createGameCommand->playerTwoId(), $this->playerTwoUserId);
    }

    public function testShouldReturnSideSize()
    {
        $this->assertEquals($this->createGameCommand->boardSize(), $this->boardSize);
    }
}
