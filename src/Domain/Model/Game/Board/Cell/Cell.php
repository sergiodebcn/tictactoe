<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Board\Cell;

use App\Domain\Model\Game\Player\Player;
use Webmozart\Assert\Assert;

class Cell
{
    const MIN_XY_VALID_POSITION = 0;

    /** @var int */
    private $xPosition;
    /** @var int */
    private $yPosition;
    /** @var ?Player */
    private $player;

    public function __construct(int $xPosition, int $yPosition, ?Player $player)
    {
        Assert::greaterThanEq($xPosition, self::MIN_XY_VALID_POSITION, 'xPosition must be greater than 0');
        Assert::greaterThanEq($yPosition, self::MIN_XY_VALID_POSITION, 'yPosition must be greater than 0');

        $this->xPosition = $xPosition;
        $this->yPosition = $yPosition;
        $this->player = $player;
    }

    public function xPosition(): int
    {
        return $this->xPosition;
    }

    public function yPosition(): int
    {
        return $this->yPosition;
    }

    public function playedBy(): ?Player
    {
        return $this->player;
    }

    public function newCellAssignedToPlayer(Player $player): Cell
    {
        return new Cell($this->xPosition, $this->yPosition, $player);
    }

    public static function fromArray(array $data): Cell
    {
        $player = is_null($data['player']) ? null : Player::fromArray($data['player']);

        return new Cell(
            $data['xPosition'],
            $data['yPosition'],
            $player
        );
    }

    public function toArray(): array
    {
        $player = is_null($this->playedBy()) ? null : $this->playedBy()->toArray();

        return [
            'xPosition' => $this->xPosition,
            'yPosition' => $this->yPosition,
            'player' => $player,
        ];
    }
}
