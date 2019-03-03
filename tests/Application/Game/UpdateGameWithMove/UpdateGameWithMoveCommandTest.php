<?php

declare(strict_types=1);

namespace App\Application\Game\UpdateGameWithMove;

use PHPUnit\Framework\TestCase;

class UpdateGameWithMoveCommandTest extends TestCase
{
    const FAKE_GAME_UUID = '7cb29ec7-f42b-403f-9a7e-b66f8552ab2a';

    /** @var string */
    private $gameId;
    /** @var int */
    private $xPosition;
    /** @var int */
    private $yPosition;
    /** @var UpdateGameWithMoveCommand */
    private $updateGameWithMoveCommand;

    protected function setUp(): void
    {
        $this->gameId = self::FAKE_GAME_UUID;
        $this->xPosition = 0;
        $this->yPosition = 0;
        $this->updateGameWithMoveCommand = new UpdateGameWithMoveCommand(
            $this->gameId,
            $this->xPosition,
            $this->yPosition
        );
    }

    public function testShouldReturnGameId()
    {
        $this->assertEquals($this->updateGameWithMoveCommand->gameId(), $this->gameId);
    }

    public function testShouldReturnXPosition()
    {
        $this->assertEquals($this->updateGameWithMoveCommand->xPosition(), $this->xPosition);
    }

    public function testShouldReturnPlayerTwoUserId()
    {
        $this->assertEquals($this->updateGameWithMoveCommand->yPosition(), $this->yPosition);
    }
}
