<?php

namespace App\Service;

use App\Entity\Account;
use App\Repository\AccountRepository;
use DateTime;
use Exception;

class AccountService
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param int $accountId
     * @return Account
     * @throws Exception
     */
    public function getAccount(int $accountId): Account
    {
        $account = $this->accountRepository->find($accountId);

        if (!($account instanceof Account)) {
            throw new Exception('Account not found', 404);
        }

        return $account;
    }

    /**
     * @param array $accountData
     * @return Account
     */
    public function createAccount(array $accountData): Account
    {
        $account = new Account();
        $account->setFirstName($accountData['firstName']);
        $account->setLastName($accountData['lastName']);
        $account->setCreatedAt(new DateTime());

        $this->accountRepository->add($account, true);

        return $account;
    }
}