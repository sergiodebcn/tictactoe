<?php

declare(strict_types=1);

namespace App\Application\Game\GetGame;

use PHPUnit\Framework\TestCase;

class GetGameResponseTest extends TestCase
{
    const FAKE_GAME_UUID = '7cb29ec7-f42b-403f-9a7e-b66f8552ab2a';

    /** @var GetGameResponse */
    private $getGameResponse;
    /** @var string */
    private $gameId;
    /** @var bool */
    private $finished;
    /** @var array */
    private $winner;
    /** @var array */
    private $nextPlayer;

    protected function setUp(): void
    {
        $this->gameId = self::FAKE_GAME_UUID;
        $this->finished = true;
        $this->winner = ['fakeWinner'];
        $this->nextPlayer = ['fakeNextPlayer'];
        $this->getGameResponse = new GetGameResponse(
            $this->gameId,
            $this->finished,
            $this->winner,
            $this->nextPlayer
        );
    }

    public function testShouldReturnGameId()
    {
        $this->assertEquals(
            $this->getGameResponse->gameId(),
            $this->gameId
        );
    }

    public function testShouldReturnFinished()
    {
        $this->assertEquals(
            $this->getGameResponse->finished(),
            $this->finished
        );
    }

    public function testShouldReturnWinner()
    {
        $this->assertEquals(
            $this->getGameResponse->winner(),
            $this->winner
        );
    }

    public function testShouldReturnNextPlayer()
    {
        $this->assertEquals(
            $this->getGameResponse->nextPlayer(),
            $this->nextPlayer
        );
    }
}
