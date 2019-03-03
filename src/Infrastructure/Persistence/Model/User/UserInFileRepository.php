<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Model\User;

use App\Domain\Model\User\User;
use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserNotFoundException;
use App\Domain\Model\User\UserRepository;
use Exception;

class UserInFileRepository implements UserRepository
{
    /** @var array */
    private $users;

    private $usersFilePath;

    public function __construct(string $usersFilePath)
    {
        $users = file_get_contents($usersFilePath);
        if (false === $users) {
            throw new Exception('Unable to load users file: '.$usersFilePath);
        }

        $this->users = json_decode($users, true);
        $this->usersFilePath = $usersFilePath;
    }

    public function findById(UserId $userId): User
    {
        foreach ($this->users as $user) {
            if ($user['id'] === $userId->value) {
                return new User(
                    UserId::instance($user['id'])
                );
            }
        }

        throw new UserNotFoundException('User with id '.$userId.' not found');
    }

    public function deleteById(UserId $id): void
    {
        foreach ($this->users as $key => $user) {
            if ($user['id'] === $id->value()) {
                unset($this->users[$key]);
                file_put_contents($this->usersFilePath, json_encode($this->users));

                return;
            }
        }

        throw new UserNotFoundException('User not found');
    }

    public function save(User $user): void
    {
        $this->users[] = [
            'id' => $user->id()->value(),
        ];

        file_put_contents($this->usersFilePath, json_encode($this->users));
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
}
