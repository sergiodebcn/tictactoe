<?php

declare(strict_types=1);

namespace App\Application\User\DeleteUser;

use App\Domain\Model\User\User;
use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserRepository;
use App\Infrastructure\Persistence\Model\User\UserInMemoryRepository;
use PHPUnit\Framework\TestCase;

class DeleteUserHandlerTest extends TestCase
{
    const FAKE_USER_UUID = '0abce6ac-0008-4291-93f4-4608e13bf2f2';

    /** @var UserRepository */
    private $userRepository;

    /** @var UserId */
    private $userId;

    /** @var DeleteUser */
    private $deleteUser;

    protected function setUp(): void
    {
        $this->userRepository = new UserInMemoryRepository();
        $this->userId = UserId::instance(self::FAKE_USER_UUID);
        $this->userRepository->save(new User($this->userId));
        $this->deleteUser = new DeleteUser($this->userRepository);
    }

    public function testShouldDeleteUser()
    {
        $deleteUserHandler = new DeleteUserHandler($this->deleteUser);
        $this->assertTrue($this->userRepository->existsById($this->userId));
        $deleteUserHandler->__invoke(new DeleteUserCommand(self::FAKE_USER_UUID));
        $this->assertFalse($this->userRepository->existsById($this->userId));
    }
}
