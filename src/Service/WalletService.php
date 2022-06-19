<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\Wallet;
use App\Repository\WalletRepository;
use DateTime;
use Exception;

class WalletService
{

    private WalletRepository $walletRepository;

    public function __construct(WalletRepository $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    /**
     * @param Account $account
     * @return Wallet
     */
    public function createWallet(Account $account): Wallet
    {
        $wallet = new Wallet();
        $wallet->setAccount($account);
        $wallet->setBalance(0);
        $wallet->setCreatedAt(new DateTime());

        $this->walletRepository->add($wallet, true);

        return $wallet;
    }

    /**\
     * @param Wallet $wallet
     * @param float $balance
     * @return Wallet
     */
    public function modifyWalletBalance(Wallet $wallet, float $balance): Wallet
    {
        $wallet->setBalance($balance);
        $wallet->setUpdatedAt(new DateTime());

        $this->walletRepository->add($wallet);

        return $wallet;
    }

    /**
     * @param int $walletId
     * @return Wallet
     * @throws Exception
     */
    public function getWalletById(int $walletId): Wallet
    {
        $wallet = $this->walletRepository->find($walletId);

        if (!($wallet instanceof Wallet)) {
            throw new Exception('Wallet not found', 404);
        }

        return $wallet;
    }
}