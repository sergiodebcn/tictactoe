<?php

declare(strict_types=1);

namespace App\Application\Game\CreateGame;

use App\Domain\Model\Game\GameId;
use App\Domain\Model\User\UserId;
use InvalidArgumentException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateGameHandler implements MessageHandlerInterface
{
    /** @var CreateGame */
    private $createGame;

    public function __construct(CreateGame $createGame)
    {
        $this->createGame = $createGame;
    }

    public function __invoke(CreateGameCommand $createUserCommand): void
    {
        try {
            $this->createGame->execute(
                GameId::instance($createUserCommand->gameId()),
                UserId::instance($createUserCommand->playerOneId()),
                UserId::instance($createUserCommand->playerTwoId()),
                $createUserCommand->boardSize()
            );
        } catch (InvalidArgumentException $e) {
            throw new CreateGameException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
