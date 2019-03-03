<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Board;

use App\Domain\Model\Game\Board\Cell\Cell;
use App\Domain\Model\Game\Board\Cell\CellCollection;
use PHPUnit\Framework\TestCase;

class SquareBoardBuilderTest extends TestCase
{
    private $squareBoardBuilder;

    public function testShouldBuildSquareWithSideSizeOfTwo()
    {
        $this->squareBoardBuilder = new SquareBoardBuilder();
        $board = $this->squareBoardBuilder->build(2);
        $this->assertEquals(
            $board,
            new SquareBoard(
                new CellCollection(
                    [new Cell(0, 0, null), new Cell(1, 0, null), new Cell(0, 1, null), new Cell(1, 1, null)]
                )
            )
        );
    }
}
