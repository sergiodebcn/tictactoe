<?php

declare(strict_types=1);

namespace App\Application\Game\GetGame;

use PHPUnit\Framework\TestCase;

class GetGameQueryTest extends TestCase
{
    const FAKE_GAME_UUID = '7cb29ec7-f42b-403f-9a7e-b66f8552ab2a';

    /** @var GetGameQuery */
    private $getGameQuery;
    /** @var string */
    private $gameId;

    protected function setUp(): void
    {
        $this->gameId = self::FAKE_GAME_UUID;
        $this->getGameQuery = new GetGameQuery(
            $this->gameId
        );
    }

    public function testShouldReturnGameId()
    {
        $this->assertEquals(
            $this->getGameQuery->gameId(),
            $this->gameId
        );
    }
}
