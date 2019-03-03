<?php

declare(strict_types=1);

namespace App\Domain\Model\Game;

use App\Domain\Model\Game\Board\SquareBoard;
use App\Domain\Model\Game\Player\Player;
use InvalidArgumentException;

class Game
{
    /** @var GameId */
    private $id;

    /** @var Player */
    private $playerOne;

    /** @var Player */
    private $playerTwo;

    /** @var SquareBoard */
    private $board;

    public function __construct(GameId $id, Player $playerOne, Player $playerTwo, SquareBoard $board)
    {
        if ($playerOne->userId()->equals($playerTwo->userId())) {
            throw new InvalidArgumentException('Players must have different user id');
        }

        $this->id = $id;
        $this->playerOne = $playerOne;
        $this->playerTwo = $playerTwo;
        $this->board = $board;
    }

    public function id(): GameId
    {
        return $this->id;
    }

    public function playerOne(): Player
    {
        return $this->playerOne;
    }

    public function playerTwo(): Player
    {
        return $this->playerTwo;
    }

    public function board(): SquareBoard
    {
        return $this->board;
    }

    public function nextMove(int $xPosition, int $yPosition): void
    {
        if ($this->isFinished()) {
            throw new GameFinishedException('Game finished, not more moves allowed');
        }
        $player = $this->nextPlayer();
        $this->board = $this->board->newBoardWithMarkedPositionToGivenPlayer($xPosition, $yPosition, $player);
        $player->move();
    }

    public function isFinished(): bool
    {
        if ($this->board->areAllCellsPlayed() || !is_null($this->winner())) {
            return true;
        }

        return false;
    }

    public function winner(): ?Player
    {
        return $this->board->solver();
    }

    public function nextPlayer(): Player
    {
        if ($this->playerOne()->numberOfMoves() === $this->playerTwo()->numberOfMoves()) {
            return $this->playerOne();
        }

        return $this->playerTwo();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'playerOne' => $this->playerOne->toArray(),
            'playerTwo' => $this->playerTwo->toArray(),
            'board' => $this->board->toArray(),
        ];
    }

    public static function fromArray(array $game): Game
    {
        return new Game(
            GameId::instance($game['id']),
            Player::fromArray($game['playerOne']),
            Player::fromArray($game['playerTwo']),
            SquareBoard::fromArray($game['board'])
        );
    }
}
