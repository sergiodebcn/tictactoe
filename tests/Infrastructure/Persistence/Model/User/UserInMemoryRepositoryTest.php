<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Model\User;

use App\Domain\Model\User\User;
use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserNotFoundException;
use PHPUnit\Framework\TestCase;
use ReflectionObject;

class UserInMemoryRepositoryTest extends TestCase
{
    /** @var UserInMemoryRepository $repository */
    private $repository;

    /** @var User */
    private $user;

    const FAKE_USER_UUID = 'dcdef34c-b00a-4892-b866-329d93088857';
    const FAKE_NOT_SAVED_USER_UUID = 'aadef34c-b00a-4892-b866-329d93088857';

    public function setUp(): void
    {
        $this->repository = new UserInMemoryRepository();

        $this->user = new User(UserId::instance(self::FAKE_USER_UUID));
    }

    public function testShouldSaveUserInRepository()
    {
        $this->repository->save($this->user);

        //Check value inside repository by reflection
        $reflectionObject = new ReflectionObject($this->repository);
        $userProperty = $reflectionObject->getProperty('users');
        $userProperty->setAccessible(true);

        $users = $userProperty->getValue($this->repository);
        $user = reset($users);

        $this->assertTrue($user instanceof User);
    }

    public function testShouldRecoverUserFromRepository()
    {
        $this->repository->save($this->user);
        $user = $this->repository->findById(UserId::instance(self::FAKE_USER_UUID));
        $this->assertTrue(self::FAKE_USER_UUID === $user->id()->value());
    }

    public function testShouldThrowExceptionIfUserNotFound()
    {
        $this->expectException(UserNotFoundException::class);
        $this->repository->save($this->user);
        $this->repository->findById(UserId::instance(self::FAKE_NOT_SAVED_USER_UUID));
    }

    public function testReturnTrueWhenAskIfElementExistsToRepository()
    {
        $this->repository->save($this->user);
        $this->assertTrue($this->repository->existsById(UserId::instance(self::FAKE_USER_UUID)));
    }

    public function testShouldDeleteUserFromRepository()
    {
        $this->repository->save($this->user);
        $this->repository->deleteById(UserId::instance(self::FAKE_USER_UUID));
        $this->expectException(UserNotFoundException::class);
        $this->repository->findById(UserId::instance(self::FAKE_USER_UUID));
    }

    public function testShouldThrowExceptionIfTryToDeleteANonExistentUser()
    {
        $this->expectException(UserNotFoundException::class);
        $this->repository->deleteById(UserId::instance(self::FAKE_USER_UUID));
    }
}
