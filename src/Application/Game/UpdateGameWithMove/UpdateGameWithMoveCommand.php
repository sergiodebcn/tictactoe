<?php

declare(strict_types=1);

namespace App\Application\Game\UpdateGameWithMove;

use App\Domain\Shared\Bus\Command\Command;

class UpdateGameWithMoveCommand implements Command
{
    /** @var string */
    private $gameId;
    /** @var int */
    private $xPosition;
    /** @var int */
    private $yPosition;

    public function __construct(string $gameId, int $xPosition, int $yPosition)
    {
        $this->gameId = $gameId;
        $this->xPosition = $xPosition;
        $this->yPosition = $yPosition;
    }

    public function gameId(): string
    {
        return $this->gameId;
    }

    public function xPosition(): int
    {
        return $this->xPosition;
    }

    public function yPosition(): int
    {
        return $this->yPosition;
    }
}
