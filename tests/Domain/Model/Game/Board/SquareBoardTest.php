<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Board;

use App\Domain\Model\Game\Board\Cell\Cell;
use App\Domain\Model\Game\Board\Cell\CellCollection;
use App\Domain\Model\Game\Player\Player;
use App\Domain\Model\User\UserId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SquareBoardTest extends TestCase
{
    const FAKE_PLAYER_ONE_USER_UUID = '0abce6ac-0008-4291-93f4-4608e13bf2f2';
    const FAKE_PLAYER_TWO_USER_UUID = '0abce6ac-0008-4291-93f4-4608e13bf2f3';

    /** @var SquareBoard */
    private $board;

    /** @var Player */
    private $playerOne;

    /** @var Player */
    private $playerTwo;

    protected function setUp(): void
    {
        $this->board = new SquareBoard(
            new CellCollection(
                [new Cell(0, 0, null), new Cell(1, 0, null), new Cell(0, 1, null), new Cell(1, 1, null)]
            )
        );
        $this->playerOne = new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID));
        $this->playerTwo = new Player(UserId::instance(self::FAKE_PLAYER_TWO_USER_UUID));
    }

    public function testShouldReturnWellFormedCellCollection(): void
    {
        /** @var Cell $firstCell */
        $iterator = $this->board->cellCollection()->getIterator();
        $firstCell = $iterator->current();
        $this->assertSame($firstCell->xPosition(), 0);
        $this->assertSame($firstCell->yPosition(), 0);
        $iterator->next();
        $secondCell = $iterator->current();
        $this->assertSame($secondCell->xPosition(), 1);
        $this->assertSame($secondCell->yPosition(), 0);
        $iterator->next();
        $thirdCell = $iterator->current();
        $this->assertSame($thirdCell->xPosition(), 0);
        $this->assertSame($thirdCell->yPosition(), 1);
        $iterator->next();
        $fourthCell = $iterator->current();
        $this->assertSame($fourthCell->xPosition(), 1);
        $this->assertSame($fourthCell->yPosition(), 1);
    }

    public function testShouldReturnThatNotAllCellsWasPlayed(): void
    {
        $this->assertFalse($this->board->areAllCellsPlayed());
    }

    public function testShouldReturnThatAllCellsWasPlayed(): void
    {
        $board = new SquareBoard(
            new CellCollection(
                [
                    new Cell(0, 0, $this->playerOne),
                    new Cell(1, 0, $this->playerOne),
                    new Cell(0, 1, $this->playerOne),
                    new Cell(1, 1, $this->playerOne),
                ]
            )
        );
        $this->assertTrue($board->areAllCellsPlayed());
    }

    public function testShouldMarkPositionToGivenPlayer(): void
    {
        /** @var Cell $firstCell */
        $firstCell = $this->board->cellCollection()->getIterator()->current();
        $this->assertNull($firstCell->playedBy());

        $newBoard = $this->board->newBoardWithMarkedPositionToGivenPlayer(0, 0, $this->playerOne);
        $firstCell = $newBoard->cellCollection()->getIterator()->current();
        $this->assertTrue($firstCell->playedBy() === $this->playerOne);
    }

    public function testShouldThrowExceptionIfPositionIsAlreadyMarked(): void
    {
        $board = new SquareBoard(
            new CellCollection(
                [
                    new Cell(0, 0, $this->playerOne),
                    new Cell(1, 0, $this->playerTwo),
                    new Cell(2, 0, $this->playerOne),
                    new Cell(0, 1, $this->playerTwo),
                    new Cell(1, 1, $this->playerOne),
                    new Cell(2, 1, $this->playerTwo),
                    new Cell(0, 2, $this->playerTwo),
                    new Cell(1, 2, $this->playerOne),
                    new Cell(2, 2, null),
                ]
            )
        );
        $this->expectException(BoardException::class);
        $this->expectExceptionMessage('Position already assigned');
        $board->newBoardWithMarkedPositionToGivenPlayer(0, 0, $this->playerTwo);
    }

    /**
     * @dataProvider invalidBoardCellCollection
     *
     * @param CellCollection $cellCollection
     */
    public function testShouldThrowExceptionIfTryToBuildNonSquareBoard(CellCollection $cellCollection): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cell Collection must fit board requirements');

        new SquareBoard($cellCollection);
    }

    public function testShouldReturnBoardToArray(): void
    {
        $this->assertEquals(
            $this->board->toArray(),
            [
                'cellCollection' => [
                    [
                        'xPosition' => 0,
                        'yPosition' => 0,
                        'player' => null,
                    ],
                    [
                        'xPosition' => 1,
                        'yPosition' => 0,
                        'player' => null,
                    ],
                    [
                        'xPosition' => 0,
                        'yPosition' => 1,
                        'player' => null,
                    ],
                    [
                        'xPosition' => 1,
                        'yPosition' => 1,
                        'player' => null,
                    ],
                ],
            ]
        );
    }

    public function testShouldBuildBoardFromArray(): void
    {
        $this->assertEquals(
            SquareBoard::fromArray(
                [
                    'cellCollection' => [
                        [
                            'xPosition' => 0,
                            'yPosition' => 0,
                            'player' => null,
                        ],
                        [
                            'xPosition' => 1,
                            'yPosition' => 0,
                            'player' => null,
                        ],
                        [
                            'xPosition' => 0,
                            'yPosition' => 1,
                            'player' => null,
                        ],
                        [
                            'xPosition' => 1,
                            'yPosition' => 1,
                            'player' => null,
                        ],
                    ],
                ]
            ),
            new SquareBoard(new CellCollection([
                new Cell(0, 0, null),
                new Cell(1, 0, null),
                new Cell(0, 1, null),
                new Cell(1, 1, null),
            ]))
        );
    }

    public function invalidBoardCellCollection()
    {
        return [
            [
                new CellCollection(
                    [
                        new Cell(1, 0, null),
                    ]
                ),
            ],
            [
                new CellCollection(
                    [
                        new Cell(20, 0, null),
                        new Cell(0, 1, null),
                        new Cell(1, 0, null),
                    ]
                ),
            ],
            [
                new CellCollection(
                    [
                        new Cell(0, 0, null),
                        new Cell(0, 1, null),
                        new Cell(1, 0, null),
                    ]
                ),
            ],
            [
                new CellCollection(
                    [
                        new Cell(0, 0, null),
                        new Cell(0, 1, null),
                        new Cell(1, 0, null),
                        new Cell(1, 0, null),
                    ]
                ),
            ],
            [
                new CellCollection(
                    [
                        new Cell(0, 0, null),
                        new Cell(0, 1, null),
                        new Cell(52, 0, null),
                        new Cell(1, 0, null),
                        new Cell(2, 0, null),
                    ]
                ),
            ],
            [
                new CellCollection(
                    [
                        new Cell(0, 0, null),
                        new Cell(0, 1, null),
                        new Cell(1, 0, null),
                        new Cell(1, 1, null),
                        new Cell(2, 0, null),
                    ]
                ),
            ],
        ];
    }

    /**
     * @dataProvider boardWithNonSolvedProvider
     *
     * @param SquareBoard $board
     */
    public function testShouldReturnThereIsNoSolverForBoard(SquareBoard $board)
    {
        $this->assertNull($board->solver());
    }

    /**
     * @dataProvider boardWithSolverProvider
     *
     * @param SquareBoard $board
     * @param Player      $player
     */
    public function testShouldReturnSolverForBoard(SquareBoard $board, Player $player)
    {
        $this->assertNotNull($player->userId());
        $this->assertTrue($board->solver() instanceof Player);
        $this->assertEquals($board->solver(), $player);
    }

    public function boardWithNonSolvedProvider()
    {
        return [
            [
                new SquareBoard(
                    new CellCollection(
                        [
                            new Cell(0, 0, null),
                            new Cell(1, 0, null),
                            new Cell(0, 1, null),
                            new Cell(1, 1, null),
                        ]
                    ),
                ),
            ],
            [
                new SquareBoard(
                    new CellCollection(
                        [
                            new Cell(0, 0, $this->playerOne),
                            new Cell(1, 0, null),
                            new Cell(0, 1, null),
                            new Cell(1, 1, null),
                        ]
                    ),
                ),
            ],
            [
                new SquareBoard(
                    new CellCollection(
                        [
                            new Cell(0, 0, null),
                            new Cell(1, 0, null),
                            new Cell(0, 1, null),
                            new Cell(1, 1, $this->playerOne),
                        ]
                    ),
                ),
            ],
            [
                new SquareBoard(
                    new CellCollection(
                        [
                            new Cell(0, 0, $this->playerOne),
                            new Cell(1, 0, null),
                            new Cell(2, 0, $this->playerOne),
                            new Cell(0, 1, null),
                            new Cell(1, 1, null),
                            new Cell(2, 1, null),
                            new Cell(0, 2, $this->playerOne),
                            new Cell(1, 2, null),
                            new Cell(2, 2, $this->playerOne),
                        ]
                    ),
                ),
            ],
        ];
    }

    public function boardWithSolverProvider()
    {
        return [
            [
                new SquareBoard(
                    new CellCollection(
                        [
                            new Cell(0, 0, new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID))),
                            new Cell(1, 0, new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID))),
                            new Cell(0, 1, null),
                            new Cell(1, 1, null),
                        ]
                    ),
                ),
                new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID)),
            ],
            [
                new SquareBoard(
                    new CellCollection(
                        [
                            new Cell(0, 0, new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID))),
                            new Cell(1, 0, null),
                            new Cell(0, 1, new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID))),
                            new Cell(1, 1, null),
                        ]
                    ),
                ),
                new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID)),
            ],
            [
                new SquareBoard(
                    new CellCollection(
                        [
                            new Cell(0, 0, new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID))),
                            new Cell(1, 0, null),
                            new Cell(0, 1, null),
                            new Cell(1, 1, new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID))),
                        ]
                    ),
                ),
                new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID)),
            ],
            [
                new SquareBoard(
                    new CellCollection(
                        [
                            new Cell(0, 0, new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID))),
                            new Cell(1, 0, new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID))),
                            new Cell(2, 0, new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID))),
                            new Cell(0, 1, null),
                            new Cell(1, 1, null),
                            new Cell(2, 1, null),
                            new Cell(0, 2, null),
                            new Cell(1, 2, null),
                            new Cell(2, 2, null),
                        ]
                    ),
                ),
                new Player(UserId::instance(self::FAKE_PLAYER_ONE_USER_UUID)),
            ],
        ];
    }
}
