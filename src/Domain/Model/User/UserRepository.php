<?php

declare(strict_types=1);

namespace App\Domain\Model\User;

interface UserRepository
{
    public function save(User $user): void;

    public function findById(UserId $userId): User;

    public function existsById(UserId $userId): bool;

    public function deleteById(UserId $userId): void;
}
