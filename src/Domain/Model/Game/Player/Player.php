<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Player;

use App\Domain\Model\User\UserId;

class Player
{
    /** @var UserId */
    private $userId;

    /** @var int */
    private $movesCount;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
        $this->movesCount = 0;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function move(): void
    {
        $this->movesCount = $this->movesCount + 1;
    }

    public function numberOfMoves(): int
    {
        return $this->movesCount;
    }

    public static function fromArray(array $player): Player
    {
        /** @var Player $instance */
        $instance = (new \ReflectionClass(self::class))->newInstanceWithoutConstructor();
        $instance->userId = UserId::instance($player['userId']);
        $instance->movesCount = $player['movesCount'];

        return $instance;
    }

    public function toArray(): array
    {
        return [
            'userId' => $this->userId->value(),
            'movesCount' => $this->movesCount,
        ];
    }
}
