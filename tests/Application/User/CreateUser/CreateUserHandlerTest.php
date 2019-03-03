<?php

declare(strict_types=1);

namespace App\Application\User\CreateUser;

use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserRepository;
use App\Infrastructure\Persistence\Model\User\UserInMemoryRepository;
use PHPUnit\Framework\TestCase;

class CreateUserHandlerTest extends TestCase
{
    const FAKE_USER_UUID = '0abce6ac-0008-4291-93f4-4608e13bf2f2';

    /** @var UserRepository */
    private $userRepository;

    /** @var UserId */
    private $userId;

    /** @var CreateUser */
    private $createUser;

    protected function setUp(): void
    {
        $this->userRepository = new UserInMemoryRepository();
        $this->userId = UserId::instance(self::FAKE_USER_UUID);
        $this->createUser = new CreateUser($this->userRepository);
    }

    public function testShouldCreateUser()
    {
        $createUserHandler = new CreateUserHandler($this->createUser);
        $createUserHandler->__invoke(new CreateUserCommand(self::FAKE_USER_UUID));
        $this->assertTrue($this->userRepository->existsById($this->userId));
    }
}
