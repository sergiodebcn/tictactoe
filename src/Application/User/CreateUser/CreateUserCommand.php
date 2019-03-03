<?php

declare(strict_types=1);

namespace App\Application\User\CreateUser;

use App\Domain\Shared\Bus\Command\Command;

class CreateUserCommand implements Command
{
    /** @var string */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }
}
