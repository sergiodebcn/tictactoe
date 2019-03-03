<?php

declare(strict_types=1);

namespace App\Application\User\DeleteUser;

use App\Application\User\CreateUser\CreateUserException;
use App\Domain\Model\User\UserId;
use InvalidArgumentException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteUserHandler implements MessageHandlerInterface
{
    /** @var DeleteUser */
    private $deleteUser;

    public function __construct(DeleteUser $deleteUser)
    {
        $this->deleteUser = $deleteUser;
    }

    public function __invoke(DeleteUserCommand $deleteUserCommand): void
    {
        try {
            $this->deleteUser->execute(
                UserId::instance($deleteUserCommand->id()),
            );
        } catch (InvalidArgumentException $e) {
            throw new CreateUserException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
