<?php

declare(strict_types=1);

namespace App\Application\Game\GetGame;

use App\Application\Game\CreateGame\CreateGameException;
use App\Domain\Model\Game\GameId;
use App\Domain\Shared\Bus\Query\Response;
use InvalidArgumentException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetGameHandler implements MessageHandlerInterface
{
    /** @var GetGame */
    private $getGame;

    public function __construct(GetGame $getGame)
    {
        $this->getGame = $getGame;
    }

    public function __invoke(GetGameQuery $getGameQuery): Response
    {
        try {
            $game = $this->getGame->execute(GameId::instance($getGameQuery->gameId()));

            if (is_null($game->winner())) {
                $winner = null;
            } else {
                $winner = $game->winner()->toArray();
            }

            return new GetGameResponse(
                $game->id()->value(),
                $game->isFinished(),
                $winner,
                $game->nextPlayer()->toArray()
            );
        } catch (InvalidArgumentException $e) {
            throw new CreateGameException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
