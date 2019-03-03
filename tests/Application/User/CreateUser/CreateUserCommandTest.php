<?php

declare(strict_types=1);

namespace App\Application\User\CreateUser;

use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{
    const FAKE_USER_UUID = '7cb29ec7-f42b-403f-9a7e-b66f8552ab2a';

    /** @var string */
    private $userId;
    /** @var CreateUserCommand */
    private $createUserCommand;

    protected function setUp(): void
    {
        $this->userId = self::FAKE_USER_UUID;
        $this->createUserCommand = new CreateUserCommand($this->userId);
    }

    public function testShouldReturnUserId()
    {
        $this->assertEquals($this->createUserCommand->id(), $this->userId);
    }
}
