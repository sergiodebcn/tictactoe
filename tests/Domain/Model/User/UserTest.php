<?php

declare(strict_types=1);

namespace App\Domain\Model\User;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    const FAKE_USER_UUID = '0abce6ac-0008-4291-93f4-4608e13bf2f2';

    /** @var User */
    private $user;
    /** @var UserId */
    private $userId;

    protected function setUp(): void
    {
        $this->userId = UserId::instance(self::FAKE_USER_UUID);
        $this->user = new User($this->userId);
    }

    public function testShouldReturnUserId()
    {
        $this->assertEquals($this->user->id(), $this->userId);
    }
}
