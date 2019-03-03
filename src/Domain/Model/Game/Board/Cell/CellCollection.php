<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Board\Cell;

use ArrayIterator;
use Countable;
use IteratorAggregate;

class CellCollection implements IteratorAggregate, Countable
{
    const XY_START_POSITION = 0;

    protected $cells = [];

    public function __construct($cells = [])
    {
        foreach ($cells as $cell) {
            /* @var Cell */
            $this->add($cell);
        }
    }

    public function add(Cell $cell): void
    {
        $this->cells[] = $cell;
    }

    public function yAxisCollection($yAxis): CellCollection
    {
        $yAxisCollection = new CellCollection();
        foreach ($this->cells as $cell) {
            /** @var Cell $cell */
            if ($cell->yPosition() === $yAxis) {
                $yAxisCollection->add($cell);
            }
        }

        return $yAxisCollection;
    }

    public function xAxisCollection($xAxis): CellCollection
    {
        $xAxisCollection = new CellCollection();

        foreach ($this->cells as $cell) {
            /** @var Cell $cell */
            if ($cell->xPosition() === $xAxis) {
                $xAxisCollection->add($cell);
            }
        }

        return $xAxisCollection;
    }

    public function matchSamePlayer(): bool
    {
        $firstCell = $this->getIterator()->current();
        foreach ($this->cells as $key => $cell) {
            /** @var Cell $cell */
            /** @var Cell $firstCell */
            if (is_null($cell->playedBy()) ||
                is_null($firstCell->playedBy()) ||
                !$cell->playedBy()->userId()->equals($firstCell->playedBy()->userId())) {
                return false;
            }
        }

        return true;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->cells);
    }

    public function count(): int
    {
        return count($this->cells);
    }

    public function toArray(): array
    {
        $cells = [];
        foreach ($this->cells as $cell) {
            /* @var Cell $cell */
            $cells[] = $cell->toArray();
        }

        return $cells;
    }
}
