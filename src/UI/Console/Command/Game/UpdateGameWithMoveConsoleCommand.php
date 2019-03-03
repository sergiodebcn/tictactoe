<?php

declare(strict_types=1);

namespace App\UI\Console\Command\Game;

use App\Application\Game\UpdateGameWithMove\UpdateGameWithMoveCommand;
use App\Application\Game\UpdateGameWithMove\UpdateGameWithMoveException;
use App\Domain\Model\Game\GameNotFoundException;
use App\Domain\Shared\Bus\Command\CommandBus;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateGameWithMoveConsoleCommand extends ConsoleCommand
{
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
            ->setName('tictactoe:game:move')
            ->setDescription('Make a move for next player in game')
            ->addArgument('id', InputArgument::OPTIONAL, 'The game id in uuid format')
            ->addArgument('xPosition', InputArgument::OPTIONAL, 'the x position in board')
            ->addArgument('yPosition', InputArgument::OPTIONAL, 'the y position in board')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $helper = $this->getHelper('question');
            $io = new SymfonyStyle($input, $output);
            $io->title('Tic tac toe - Make a move');

            if (is_null($input->getArgument('id'))) {
                $question = new Question('Please enter an game uuid4 (example afb89091-4530-46f8-ade1-d0b2b2a76929): ');
                $gameId = $helper->ask($input, $output, $question);
                $question = new Question('Please enter next player xPosition (example 0): ');
                $xPosition = $helper->ask($input, $output, $question);
                $question = new Question('Please enter next player yPosition (example 0): ');
                $yPosition = $helper->ask($input, $output, $question);
            } else {
                $gameId = $input->getArgument('id');
                $xPosition = $input->getArgument('xPosition');
                $yPosition = $input->getArgument('yPosition');
            }

            if (!is_string($gameId) || !is_string($xPosition) || !is_string($yPosition)) {
                throw new InvalidArgumentException('Please, provide a valid format for ids');
            }

            $moveInGameConsoleCommand = new UpdateGameWithMoveCommand($gameId, (int) $xPosition, (int) $yPosition);
            $this->commandBus->dispatch($moveInGameConsoleCommand);
            $output->write('<fg=green>Movement made</fg=green>');
        } catch (GameNotFoundException $e) {
            $output->write('<fg=red>'.$e->getMessage().'</fg=red>');
        } catch (UpdateGameWithMoveException | \Throwable  $e) {
            $output->write('<fg=red>'.$e->getMessage().$e->getFile().$e->getLine().'</fg=red>');
        }
    }
}
