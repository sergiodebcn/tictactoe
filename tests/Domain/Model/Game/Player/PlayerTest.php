<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Player;

use App\Domain\Model\User\UserId;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    const FAKE_USER_UUID = '0abce6ac-0008-4291-93f4-4608e13bf2f2';

    /** @var UserId */
    private $userId;

    /** @var Player */
    private $player;

    protected function setUp(): void
    {
        $this->userId = UserId::instance(self::FAKE_USER_UUID);
        $this->player = new Player($this->userId);
    }

    public function testShouldReturnUserId()
    {
        $this->assertEquals($this->player->userId(), $this->userId);
    }

    public function testShouldReturnNumberOfMoves()
    {
        $this->assertEquals($this->player->numberOfMoves(), 0);
    }

    public function testPlayerMakeAMove()
    {
        $this->player->move();
        $this->assertEquals($this->player->numberOfMoves(), 1);
    }

    public function testShouldReturnPlayerArray()
    {
        $this->player->toArray();
        $this->assertEquals(
            [
                'userId' => '0abce6ac-0008-4291-93f4-4608e13bf2f2',
                'movesCount' => 0,
            ],
            $this->player->toArray()
        );
    }

    public function testShouldBuildPlayerFromArray()
    {
        $player = [
            'userId' => '0abce6ac-0008-4291-93f4-4608e13bf2f2',
            'movesCount' => 0,
        ];

        $this->assertEquals($this->player, Player::fromArray($player));
    }
}
