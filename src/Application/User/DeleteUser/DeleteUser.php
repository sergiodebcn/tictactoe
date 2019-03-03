<?php

declare(strict_types=1);

namespace App\Application\User\DeleteUser;

use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserRepository;

class DeleteUser
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(UserId $userId): void
    {
        $this->userRepository->deleteById($userId);
    }
}
