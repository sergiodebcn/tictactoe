<?php

declare(strict_types=1);

namespace App\Application\User\CreateUser;

use App\Domain\Model\User\User;
use App\Domain\Model\User\UserAlreadyExistsException;
use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserRepository;

class CreateUser
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(UserId $userId): void
    {
        if ($this->userRepository->existsById($userId)) {
            throw new UserAlreadyExistsException('User with same id already exists');
        }

        $newUser = new User($userId);
        $this->userRepository->save($newUser);
    }
}
