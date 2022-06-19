<?php

namespace App\Command;

use App\Service\AccountService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create:new:account',
    description: 'This command creates new account.',
)]
class CreateNewAccountCommand extends Command
{

    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        parent::__construct();
        $this->accountService = $accountService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $helper = $this->getHelper('question');

        $question = new Question('Please enter account user first name: ');
        $question->setValidator(function ($answer) {
            if (!$answer) {
                throw new \RuntimeException('First name cannot be empty');
            }

            return $answer;
        });

        $accountData['firstName'] = $helper->ask($input, $output, $question);

        $question = new Question('Please enter account user last name: ');
        $question->setValidator(function ($answer) {
            if (!$answer) {
                throw new \RuntimeException('Last name cannot be empty');
            }

            return $answer;
        });

        $accountData['lastName'] = $helper->ask($input, $output, $question);

        $account = $this->accountService->createAccount($accountData);

        $io->success('New account has been created: #' . $account->getId());

        return Command::SUCCESS;
    }
}
