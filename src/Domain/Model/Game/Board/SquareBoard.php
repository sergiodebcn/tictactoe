<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Board;

use App\Domain\Model\Game\Player\Player;
use App\Domain\Model\Game\Board\Cell\Cell;
use App\Domain\Model\Game\Board\Cell\CellCollection;
use InvalidArgumentException;

class SquareBoard
{
    const XY_START_POSITION = 0;

    /** @var CellCollection */
    private $cellCollection;

    /** @var int */
    private $sideSize;

    public function __construct(CellCollection $cellCollection)
    {
        if (!gmp_perfect_square($cellCollection->count())) {
            throw new InvalidArgumentException('Cell Collection must fit board requirements');
        }

        $this->sideSize = $this->calcSideSizeGivenCollection($cellCollection);

        if (!$this->isValidCellCollection($cellCollection)) {
            throw new InvalidArgumentException('Cell Collection must fit board requirements');
        }
        $this->cellCollection = $cellCollection;
    }

    private function calcSideSizeGivenCollection(CellCollection $cellCollection): int
    {
        return (int) sqrt($cellCollection->count());
    }

    private function isValidCellCollection(CellCollection $cellCollection): bool
    {
        $expectedXPosition = $expectedYPosition = self::XY_START_POSITION;
        foreach ($cellCollection as $key => $value) {
            /** @var Cell $value */
            if ($value->xPosition() !== $expectedXPosition || $value->yPosition() !== $expectedYPosition) {
                return false;
            }
            $expectedXPosition = $expectedXPosition + 1;

            if ($this->isLastColumn((int) $key + 1, $this->sideSize)) {
                $expectedXPosition = 0;
                $expectedYPosition = $expectedYPosition + 1;
            }
        }

        return true;
    }

    private function isLastColumn(int $xPositionRow, float $squareRoot): bool
    {
        return 0 === $xPositionRow % $squareRoot;
    }

    public function newBoardWithMarkedPositionToGivenPlayer(int $xPosition, int $yPosition, Player $player): SquareBoard
    {
        $newCellCollection = new CellCollection();

        foreach ($this->cellCollection() as $cell) {
            /** @var Cell $cell */
            if ($cell->xPosition() === $xPosition && $cell->yPosition() === $yPosition) {
                if (!is_null($cell->playedBy())) {
                    throw new BoardException('Position already assigned');
                }
                $cell = $cell->newCellAssignedToPlayer($player);
            }
            $newCellCollection->add($cell);
        }

        return new SquareBoard($newCellCollection);
    }

    public function cellCollection(): CellCollection
    {
        return $this->cellCollection;
    }

    public function areAllCellsPlayed(): bool
    {
        foreach ($this->cellCollection as $cell) {
            /** @var Cell $cell */
            if (is_null($cell->playedBy())) {
                return false;
            }
        }

        return true;
    }

    public function solver(): ?Player
    {
        if (!is_null($this->horizontalSolver())) {
            return $this->horizontalSolver();
        }

        if (!is_null($this->verticalSolver())) {
            return $this->verticalSolver();
        }

        if (!is_null($this->firstDiagonalSolver())) {
            return $this->firstDiagonalSolver();
        }

        if (!is_null($this->secondDiagonalSolver())) {
            return $this->secondDiagonalSolver();
        }

        return null;
    }

    private function horizontalSolver(): ?Player
    {
        for ($yAxis = 0; $yAxis < $this->sideSize; ++$yAxis) {
            $yAxisCollection = $this->cellCollection->yAxisCollection($yAxis);
            if ($yAxisCollection->matchSamePlayer()) {
                /** @var Cell $cell */
                $cell = $yAxisCollection->getIterator()->current();

                return $cell->playedBy();
            }
        }

        return null;
    }

    private function verticalSolver(): ?Player
    {
        for ($xAxis = 0; $xAxis < $this->sideSize; ++$xAxis) {
            $xAxisCollection = $this->cellCollection->xAxisCollection($xAxis);
            if ($xAxisCollection->matchSamePlayer()) {
                /** @var Cell $cell */
                $cell = $xAxisCollection->getIterator()->current();

                return $cell->playedBy();
            }
        }

        return null;
    }

    private function firstDiagonalSolver(): ?Player
    {
        $firstDiagonalCollection = new CellCollection();
        for ($firstDiagonalPositions = 0; $firstDiagonalPositions < $this->sideSize; ++$firstDiagonalPositions) {
            foreach ($this->cellCollection as $cell) {
                /** @var Cell $cell */
                if ($cell->yPosition() === $firstDiagonalPositions &&
                    $cell->xPosition() === $firstDiagonalPositions) {
                    $firstDiagonalCollection->add($cell);
                }
            }
        }

        if ($firstDiagonalCollection->matchSamePlayer()) {
            /** @var Cell $cell */
            $cell = $firstDiagonalCollection->getIterator()->current();

            return $cell->playedBy();
        }

        return null;
    }

    private function secondDiagonalSolver()
    {
        $secondDiagonalCollection = new CellCollection();
        $secondDiagonalXStartingPosition = $this->sideSize;
        for ($secondDiagonalPositions = 0; $secondDiagonalPositions < $this->sideSize; ++$secondDiagonalPositions) {
            foreach ($this->cellCollection as $cell) {
                /** @var Cell $cell */
                if ($cell->yPosition() === $secondDiagonalPositions &&
                    $cell->xPosition() === $secondDiagonalXStartingPosition - 1
                ) {
                    $secondDiagonalCollection->add($cell);
                }
            }
            --$secondDiagonalXStartingPosition;
        }

        if ($secondDiagonalCollection->matchSamePlayer()) {
            /** @var Cell $cell */
            $cell = $secondDiagonalCollection->getIterator()->current();

            return $cell->playedBy();
        }

        return null;
    }

    public static function fromArray(array $data): SquareBoard
    {
        $cellCollection = new CellCollection();

        foreach ($data['cellCollection'] as $cell) {
            $cellCollection->add(Cell::fromArray($cell));
        }

        return new SquareBoard($cellCollection);
    }

    public function toArray(): array
    {
        return [
            'cellCollection' => $this->cellCollection()->toArray(),
        ];
    }
}
