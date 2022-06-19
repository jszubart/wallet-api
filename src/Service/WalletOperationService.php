<?php

namespace App\Service;

use App\Entity\Wallet;
use App\Entity\WalletOperation;
use App\Repository\WalletOperationRepository;
use DateTime;

class WalletOperationService
{
    const ADD_BALANCE_OPERATION_TYPE = 'ADD_BALANCE';
    const SPEND_BALANCE_OPERATION_TYPE = 'SPEND_BALANCE';

    private WalletOperationRepository $walletOperationRepository;

    public function __construct(WalletOperationRepository $walletOperationRepository)
    {
        $this->walletOperationRepository = $walletOperationRepository;
    }

    /**
     * @param Wallet $wallet
     * @param string $type
     * @param float $amount
     * @param float $walletBalance
     * @return WalletOperation
     */
    public function addNewWalletOperation(Wallet $wallet, string $type, float $amount, float $walletBalance): WalletOperation
    {
        $walletOperation = new WalletOperation();
        $walletOperation->setWallet($wallet);
        $walletOperation->setType($type);
        $walletOperation->setAmount($amount);
        $walletOperation->setWalletBalance($walletBalance);
        $walletOperation->setCreatedAt(new DateTime());

        $this->walletOperationRepository->add($walletOperation);

        return $walletOperation;
    }
}