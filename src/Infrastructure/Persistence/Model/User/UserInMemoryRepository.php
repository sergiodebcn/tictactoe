<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Model\User;

use App\Domain\Model\User\User;
use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserNotFoundException;
use App\Domain\Model\User\UserRepository;

class UserInMemoryRepository implements UserRepository
{
    private $users = [];

    public function save(User $user): void
    {
        $this->users[] = $user;
    }

    public function findById(UserId $userId): User
    {
        /** @var User $user */
        foreach ($this->users as $user) {
            if ($user->id()->equals($userId)) {
                return $user;
            }
        }

        throw new UserNotFoundException('User with id '.$userId.' not found');
    }

    public function existsById(UserId $userId): bool
    {
        try {
            self::findById($userId);

            return true;
        } catch (UserNotFoundException $ex) {
            return false;
        }
    }

    public function deleteById(UserId $userId): void
    {
        /** @var User $user */
        foreach ($this->users as $key => $user) {
            if ($user->id()->equals($userId)) {
                unset($this->users[$key]);

                return;
            }
        }
        throw new UserNotFoundException('User not found');
    }
}
