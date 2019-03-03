<?php

declare(strict_types=1);

namespace App\Application\User\CreateUser;

use App\Domain\Model\User\User;
use App\Domain\Model\User\UserAlreadyExistsException;
use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserRepository;
use App\Infrastructure\Persistence\Model\User\UserInMemoryRepository;
use PHPUnit\Framework\TestCase;

class CreateUserTest extends TestCase
{
    const FAKE_USER_UUID = '0abce6ac-0008-4291-93f4-4608e13bf2f2';

    /** @var UserRepository */
    private $userRepository;

    /** @var UserId */
    private $userId;

    protected function setUp(): void
    {
        $this->userRepository = new UserInMemoryRepository();
        $this->userId = UserId::instance(self::FAKE_USER_UUID);
    }

    public function testShouldCreateUser()
    {
        $createUser = new CreateUser($this->userRepository);
        $createUser->execute($this->userId);

        $createdUser = $this->userRepository->findById($this->userId);

        $this->assertEquals(new User($this->userId), $createdUser);
    }

    public function testShouldThrowExceptionIfUserAlreadyExists()
    {
        $user = new User($this->userId);
        $this->userRepository->save($user);

        $createUser = new CreateUser($this->userRepository);

        $this->expectException(UserAlreadyExistsException::class);
        $this->expectExceptionMessage('User with same id already exists');

        $createUser->execute($this->userId);
    }
}
