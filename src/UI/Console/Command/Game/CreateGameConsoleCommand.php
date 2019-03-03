<?php

declare(strict_types=1);

namespace App\UI\Console\Command\Game;

use App\Application\Game\CreateGame\CreateGameCommand;
use App\Domain\Shared\Bus\Command\CommandBus;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Application\User\CreateUser\CreateUserException;
use App\Domain\Model\User\UserNotFoundException;

class CreateGameConsoleCommand extends ConsoleCommand
{
    const BOARD_SIZE = 3;

    /** @var CommandBus */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('tictactoe:game:create')
            ->setDescription('Create new game given game id and two player ids')
            ->addArgument('id', InputArgument::OPTIONAL, 'The game id in uuid format')
            ->addArgument('firstUserId', InputArgument::OPTIONAL, 'first user id in uuid format')
            ->addArgument('secondUserId', InputArgument::OPTIONAL, 'second user id in uuid format')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $helper = $this->getHelper('question');
            $io = new SymfonyStyle($input, $output);
            $io->title('Tic tac toe - Create game');

            if (is_null($input->getArgument('id'))) {
                $question = new Question('Please enter a game id in uuid4 (example afb89091-4530-46f8-ade1-d0b2b2a76929): ');
                $gameId = $helper->ask($input, $output, $question);
                $question = new Question('Please enter a user id in uuid4 (example afb89091-4530-46f8-ade1-d0b2b2a76930): ');
                $firstUserId = $helper->ask($input, $output, $question);
                $question = new Question('Please enter a user id in uuid4 (example afb89091-4530-46f8-ade1-d0b2b2a76931): ');
                $secondUserId = $helper->ask($input, $output, $question);
            } else {
                $gameId = $input->getArgument('id');
                $firstUserId = $input->getArgument('firstUserId');
                $secondUserId = $input->getArgument('secondUserId');
            }

            if (!is_string($gameId) || !is_string($firstUserId) || !is_string($secondUserId)) {
                throw new InvalidArgumentException('Please, provide a valid format for ids');
            }
            $createGameConsoleCommand = new CreateGameCommand($gameId, $firstUserId, $secondUserId, self::BOARD_SIZE);
            $this->commandBus->dispatch($createGameConsoleCommand);
            $output->write('<fg=green>Game created successfully</fg=green>');
        } catch (UserNotFoundException $e) {
            $output->write('<fg=red>'.$e->getMessage().'</fg=red>');
        } catch (CreateUserException | \Throwable  $e) {
            $output->write('<fg=red>'.$e->getMessage().'</fg=red>');
        }
    }
}
