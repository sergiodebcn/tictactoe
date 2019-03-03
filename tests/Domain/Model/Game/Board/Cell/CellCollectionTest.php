<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Board\Cell;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

class CellCollectionTest extends TestCase
{
    /** @var Cell */
    private $cell;

    /** @var CellCollection */
    private $cellCollection;

    protected function setUp(): void
    {
        $this->cell = new Cell(1, 3, null);
        $this->cellCollection = new CellCollection([$this->cell]);
    }

    public function testShouldAddValuesFromConstructor()
    {
        $this->assertTrue(1 === $this->cellCollection->count());
        $this->assertTrue($this->cellCollection->getIterator()->current() instanceof Cell);
    }

    public function testShouldAddValuesFromMethod()
    {
        $cellCollection = new CellCollection();
        $cellCollection->add($this->cell);
        $this->assertTrue(1 === $cellCollection->count());
        $this->assertTrue($cellCollection->getIterator()->current() instanceof Cell);
    }

    public function testShouldAddTwoValuesFromMethod()
    {
        $cellCollection = new CellCollection();
        $cellCollection->add($this->cell);
        $cellCollection->add($this->cell);
        $this->assertTrue(2 === $cellCollection->count());
        $this->assertTrue($cellCollection->getIterator()->current() instanceof Cell);
    }

    public function testShouldReturnIterator()
    {
        $actionCollection = new CellCollection();
        $this->assertTrue($actionCollection->getIterator() instanceof ArrayIterator);
    }

    public function testShouldReturnCollectionCount()
    {
        $this->assertEquals($this->cellCollection->count(), 1);
    }

    public function testShouldReturnCollectionToArray()
    {
        $this->assertEquals(
            $this->cellCollection->toArray(),
            [
                [
                    'xPosition' => 1,
                    'yPosition' => 3,
                    'player' => null,
                ],
            ]
        );
    }
}
