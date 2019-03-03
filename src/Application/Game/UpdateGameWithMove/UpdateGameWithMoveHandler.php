<?php

declare(strict_types=1);

namespace App\Application\Game\UpdateGameWithMove;

use App\Application\Game\CreateGame\CreateGameException;
use App\Domain\Model\Game\GameId;
use InvalidArgumentException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateGameWithMoveHandler implements MessageHandlerInterface
{
    /** @var UpdateGameWithMove */
    private $updateGameWithMove;

    public function __construct(UpdateGameWithMove $updateGameWithMove)
    {
        $this->updateGameWithMove = $updateGameWithMove;
    }

    public function __invoke(UpdateGameWithMoveCommand $updateGameWithMoveCommand): void
    {
        try {
            $this->updateGameWithMove->execute(
                GameId::instance($updateGameWithMoveCommand->gameId()),
                $updateGameWithMoveCommand->xPosition(),
                $updateGameWithMoveCommand->yPosition()
            );
        } catch (InvalidArgumentException $e) {
            throw new CreateGameException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
