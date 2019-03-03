<?php

declare(strict_types=1);

namespace App\UI\Console\Command\User;

use App\Application\User\CreateUser\CreateUserCommand;
use App\Application\User\CreateUser\CreateUserException;
use App\Domain\Model\User\UserNotFoundException;
use App\Domain\Shared\Bus\Command\CommandBus;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateUserConsoleCommand extends ConsoleCommand
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
            ->setName('tictactoe:user:create')
            ->setDescription('Create new user given id')
            ->addArgument('id', InputArgument::OPTIONAL, 'The user id in uuid format')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $helper = $this->getHelper('question');
            $io = new SymfonyStyle($input, $output);
            $io->title('Tic tac toe - Create User');

            if (is_null($input->getArgument('id'))) {
                $question = new Question('Please enter an user uuid4 (example afb89091-4530-46f8-ade1-d0b2b2a76930): ');
                $userId = $helper->ask($input, $output, $question);
            } else {
                $userId = $input->getArgument('id');
            }

            if (!is_string($userId)) {
                throw new InvalidArgumentException('Please, provide a valid format for id');
            }
            $createUserCommand = new CreateUserCommand($userId);
            $this->commandBus->dispatch($createUserCommand);
            $output->write('<fg=green>User created successfully</fg=green>');
        } catch (UserNotFoundException $e) {
            $output->write('<fg=red>'.$e->getMessage().'</fg=red>');
        } catch (CreateUserException | \Throwable  $e) {
            $output->write('<fg=red>'.$e->getMessage().'</fg=red>');
        }
    }
}
