<?php

declare(strict_types=1);

namespace App\Domain\Model\Game;

use App\Domain\Shared\Identity\Identity;
use Webmozart\Assert\Assert;

class GameId extends Identity
{
    public function __construct(string $value)
    {
        Assert::uuid($value);
        parent::__construct($value);
    }
}
