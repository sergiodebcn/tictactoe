<?php

declare(strict_types=1);

namespace App\Application\User\CreateUser;

use App\Domain\Model\User\UserAlreadyExistsException;
use App\Domain\Model\User\UserId;
use InvalidArgumentException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateUserHandler implements MessageHandlerInterface
{
    /** @var CreateUser */
    private $createUser;

    public function __construct(CreateUser $createUser)
    {
        $this->createUser = $createUser;
    }

    public function __invoke(CreateUserCommand $createUserCommand): void
    {
        try {
            $this->createUser->execute(
                UserId::instance($createUserCommand->id()),
            );
        } catch (InvalidArgumentException | UserAlreadyExistsException $e) {
            throw new CreateUserException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
