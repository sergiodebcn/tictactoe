<?php

declare(strict_types=1);

namespace App\Domain\Model\User;

class User
{
    /** @var UserId */
    private $id;

    public function __construct(UserId $id)
    {
        $this->id = $id;
    }

    public function id(): UserId
    {
        return $this->id;
    }
}
