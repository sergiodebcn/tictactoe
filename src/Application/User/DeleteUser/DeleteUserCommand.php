<?php

declare(strict_types=1);

namespace App\Application\User\DeleteUser;

use App\Domain\Shared\Bus\Command\Command;

class DeleteUserCommand implements Command
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
