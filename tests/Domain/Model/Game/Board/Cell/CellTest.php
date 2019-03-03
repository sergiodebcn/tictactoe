<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Board\Cell;

use App\Domain\Model\Game\Player\Player;
use App\Domain\Model\User\UserId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CellTest extends TestCase
{
    const FAKE_USER_UUID = '8fb13d27-2870-4781-b2ce-cd5652beeee9';

    /** @var Cell */
    private $cell;

    /** @var int */
    private $xPosition;

    /** @var int */
    private $yPosition;

    protected function setUp(): void
    {
        $this->xPosition = 0;
        $this->yPosition = 0;
        $this->cell = new Cell($this->xPosition, $this->yPosition, null);
    }

    public function testShouldReturnXPosition(): void
    {
        $this->assertSame($this->cell->xPosition(), $this->xPosition);
    }

    public function testShouldReturnYPosition(): void
    {
        $this->assertSame($this->cell->yPosition(), $this->yPosition);
    }

    public function testShouldReturnNotPlayedByAnyoneWhenCellIsCreated(): void
    {
        $this->assertSame($this->cell->playedBy(), null);
    }

    public function testShouldAssignCellToPlayer(): void
    {
        $player = new Player(UserId::instance(self::FAKE_USER_UUID));
        $cell = $this->cell->newCellAssignedToPlayer($player);
        $this->assertSame($cell->playedBy(), $player);
    }

    public function testShouldThrowExceptionIfTryToCreateCellWithInvalidXPosition(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('xPosition must be greater than 0');
        new Cell(-1, 0, null);
    }

    public function testShouldThrowExceptionIfTryToCreateCellWithInvalidYPosition(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('yPosition must be greater than 0');
        new Cell(0, -1, null);
    }

    public function testShouldTestCellToArray(): void
    {
        $cell = new Cell(
            0,
            0,
            new Player(UserId::instance(self::FAKE_USER_UUID))
        );

        $this->assertEquals(
            $cell->toArray(),
            [
                'xPosition' => 0,
                'yPosition' => 0,
                'player' => [
                    'userId' => '8fb13d27-2870-4781-b2ce-cd5652beeee9',
                    'movesCount' => 0,
                ],
            ]
        );
    }

    public function testShouldBuildCellFromArray(): void
    {
        $player = new Player(UserId::instance(self::FAKE_USER_UUID));
        $arrayCell = [
            'xPosition' => 0,
            'yPosition' => 0,
            'player' => $player->toArray(),
        ];

        $this->assertEquals(
            Cell::fromArray($arrayCell),
            new Cell(
                0,
                0,
                new Player(UserId::instance(self::FAKE_USER_UUID))
            )
        );
    }

    public function testShouldBuildCellFromArrayWithNullPlayer(): void
    {
        $arrayCell = [
            'xPosition' => 0,
            'yPosition' => 0,
            'player' => null,
        ];

        $this->assertEquals(
            Cell::fromArray($arrayCell),
            new Cell(
                0,
                0,
                null
            )
        );
    }
}
