<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Board;

use App\Domain\Model\Game\Board\Cell\Cell;
use App\Domain\Model\Game\Board\Cell\CellCollection;

class SquareBoardBuilder
{
    public function build(int $sideSize): SquareBoard
    {
        $cellCollection = $this->buildCellCollectionFromSideSize($sideSize);

        return new SquareBoard($cellCollection);
    }

    private function buildCellCollectionFromSideSize(int $sideSize): CellCollection
    {
        $cellCollection = new CellCollection();
        for ($yPosition = CellCollection::XY_START_POSITION; $yPosition < $sideSize; ++$yPosition) {
            for ($xPosition = CellCollection::XY_START_POSITION; $xPosition < $sideSize; ++$xPosition) {
                $cellCollection->add(new Cell($xPosition, $yPosition, null));
            }
        }

        return $cellCollection;
    }
}
