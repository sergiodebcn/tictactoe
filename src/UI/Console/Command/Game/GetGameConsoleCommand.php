<?php

declare(strict_types=1);

namespace App\UI\Console\Command\Game;

use App\Application\Game\GetGame\GetGameQuery;
use App\Application\Game\GetGame\GetGameResponse;
use App\Application\User\CreateUser\CreateUserException;
use App\Domain\Model\User\UserNotFoundException;
use App\Domain\Shared\Bus\Query\QueryBus;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class GetGameConsoleCommand extends ConsoleCommand
{
    /** @var QueryBus */
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('tictactoe:game:get')
            ->setDescription('Get game info')
            ->addArgument('id', InputArgument::OPTIONAL, 'The game id in uuid format')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $helper = $this->getHelper('question');
            $io = new SymfonyStyle($input, $output);
            $io->title('Tic tac toe - Get game');

            if (is_null($input->getArgument('id'))) {
                $question = new Question('Please enter a game uuid4 (example afb89091-4530-46f8-ade1-d0b2b2a76929): ');
                $gameId = $helper->ask($input, $output, $question);
            } else {
                $gameId = $input->getArgument('id');
            }

            if (!is_string($gameId)) {
                throw new InvalidArgumentException('Please, provide a valid format for id');
            }
            $getGameQuery = new GetGameQuery($gameId);
            /** @var GetGameResponse $response */
            $response = $this->queryBus->ask($getGameQuery);

            if ($response->finished()) {
                $io->text('Game Status: Game finished');
            } else {
                $io->text('Game Status: Game not finished');
            }
            if (is_null($response->winner())) {
                $io->text('Winner: No winner');
            } else {
                $winner = $response->winner();
                $io->text('Winner '.$winner['userId']);
            }

            if (!$response->finished()) {
                $nextPlayer = $response->nextPlayer();
                $io->text('Next player user id '.$nextPlayer['userId']);
            }
        } catch (UserNotFoundException $e) {
            $output->write('<fg=red>'.$e->getMessage().$e->getFile().'</fg=red>');
        } catch (CreateUserException | \Throwable  $e) {
            $output->write('<fg=red>'.$e->getMessage().$e->getFile().$e->getLine().'</fg=red>');
        }
    }
}
