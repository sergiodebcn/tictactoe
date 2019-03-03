<?php

declare(strict_types=1);

namespace App\Application\Game\GetGame;

use App\Domain\Shared\Bus\Query\Query;

class GetGameQuery implements Query
{
    /** @var string */
    private $gameId;

    public function __construct(string $gameId)
    {
        $this->gameId = $gameId;
    }

    public function gameId(): string
    {
        return $this->gameId;
    }
}
