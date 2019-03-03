<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Model\Game;

use App\Domain\Model\Game\Game;
use App\Domain\Model\Game\GameId;
use App\Domain\Model\Game\GameNotFoundException;
use App\Domain\Model\Game\GameRepository;
use Exception;

class GameInFileRepository implements GameRepository
{
    /** @var array */
    private $games;

    private $gamesFilePath;

    public function __construct(string $gamesFilePath)
    {
        $games = file_get_contents($gamesFilePath);
        if (false === $games) {
            throw new Exception('Unable to load games file: '.$gamesFilePath);
        }

        $this->games = json_decode($games, true);
        $this->gamesFilePath = $gamesFilePath;
    }

    public function findById(GameId $gameId): Game
    {
        foreach ($this->games as $game) {
            if ($game['id'] === $gameId->value()) {
                return Game::fromArray($game);
            }
        }

        throw new GameNotFoundException('Game with id '.$gameId.' not found');
    }

    public function save(Game $game): void
    {
        foreach ($this->games as $key => $inFileGame) {
            if ($inFileGame['id'] === $game->id()->value) {
                $this->games[$key] = $game->toArray();
                file_put_contents($this->gamesFilePath, json_encode($this->games));

                return;
            }
        }
        $this->games[] = $game->toArray();
        file_put_contents($this->gamesFilePath, json_encode($this->games));
    }
}
