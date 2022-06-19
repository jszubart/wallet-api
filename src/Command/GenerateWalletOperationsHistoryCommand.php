<?php

namespace App\Command;

use App\Entity\WalletOperation;
use App\Service\WalletService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate:wallet:operations:history',
    description: 'This command generates wallet operations history as CSV file.',
)]
class GenerateWalletOperationsHistoryCommand extends Command
{
    private WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        parent::__construct();
        $this->walletService = $walletService;
    }

    protected function configure(): void
    {
        $this->addArgument('walletId', InputArgument::REQUIRED, 'Existing wallet identifier');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $walletId = $input->getArgument('walletId');
            $wallet = $this->walletService->getWalletById($walletId);

            $filename = 'public/reports/'. (new \DateTime())->format('Ymd-His') . '-wallet-' . $wallet->getId() . '-operations.csv';

            $csv = fopen($filename, 'w');
            fputcsv($csv, ['Time', 'Type', 'Amount', 'Wallet Balance']);

            /** @var WalletOperation $walletOperation */
            foreach ($wallet->getWalletOperations() as $walletOperation) {
                $csvRow = [
                    $walletOperation->getCreatedAt()->format('Y-m-d H:i:s'),
                    $walletOperation->getType(),
                    $walletOperation->getAmount(),
                    $walletOperation->getWalletBalance()
                ];
                fputcsv($csv, $csvRow);
            }

            fclose($csv);

            $io->success('Report has been generated in ' . $filename . 'file');

            return Command::SUCCESS;
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }
    }
}
