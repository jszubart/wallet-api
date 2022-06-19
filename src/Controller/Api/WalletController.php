<?php

namespace App\Controller\Api;

use App\Entity\Wallet;
use App\Service\AccountService;
use App\Service\WalletOperationService;
use App\Service\WalletService;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="wallet_")
 */
class WalletController extends AbstractController
{

    /**
     * @Route("/wallet/create", name="create", methods={"POST"})
     */
    public function walletCreate(Request $request,
                                 AccountService $accountService,
                                 WalletService $walletService): JsonResponse
    {
        try {
            $accountId = $request->get('accountId');

            if (!$accountId) {
                throw new Exception('No valid data provided', 400);
            }

            $account = $accountService->getAccount($accountId);

            $walletService->createWallet($account);

            return $this->json([
                'status' => 'success'
            ]);

        } catch (Exception $exception) {
            return $this->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], $exception->getCode() ?? 400);
        }
    }

    /**
     * @Route("/wallet/{wallet}/balance/add", name="balance_add", methods={"POST"})
     */
    public function addWalletBalance(Wallet $wallet,
                                     Request $request,
                                     WalletService $walletService,
                                     WalletOperationService $walletOperationService,
                                     ManagerRegistry $doctrine) : JsonResponse
    {
        try {
            $amount = $request->get('amount');

            if (!isset($amount) || !is_numeric($amount)) {
                throw new Exception('No valid data provided', 400);
            }

            if ($amount <= 0) {
                throw new Exception('Invalid amount value provided', 400);
            }

            $amount = round(floatval($amount), 2);
            $newBalance = $wallet->getBalance() + $amount;

            $walletService->modifyWalletBalance($wallet, $newBalance);
            $walletOperationService->addNewWalletOperation($wallet, $walletOperationService::ADD_BALANCE_OPERATION_TYPE, $amount, $newBalance);

            $doctrine->getManager()->flush();

            return $this->json([
                'status' => 'success',
                'balance' => $wallet->getBalance()
            ]);
        } catch (Exception $exception) {
            return $this->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], $exception->getCode() ?? 400);
        }
    }

    /**
     * @Route("/wallet/{wallet}/balance", name="balance", methods={"GET"})
     */
    public function getWalletBalance(Wallet $wallet): JsonResponse
    {
        try {
            return $this->json([
                'balance' => $wallet->getBalance()
            ]);
        } catch (Exception $exception) {
            return $this->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], $exception->getCode() ?? 400);
        }
    }

    /**
     * @Route("/wallet/{wallet}/balance/spend", name="balance_spend", methods={"POST"})
     */
    public function spendWalletBalance(Wallet $wallet,
                                       Request $request,
                                       WalletService $walletService,
                                       WalletOperationService $walletOperationService,
                                       ManagerRegistry $doctrine): JsonResponse
    {
        try {
            $amount = $request->get('amount');

            if (!isset($amount) || !is_numeric($amount)) {
                throw new Exception('No valid data provided', 400);
            }

            if ($amount <= 0) {
                throw new Exception('Invalid amount value provided', 400);
            }

            $amount = round(floatval($amount), 2);

            if ($amount > $wallet->getBalance()) {
                throw new Exception('Not enough balance', 400);
            }

            $newBalance = $wallet->getBalance() - $amount;

            $walletService->modifyWalletBalance($wallet, $newBalance);
            $walletOperationService->addNewWalletOperation($wallet, $walletOperationService::SPEND_BALANCE_OPERATION_TYPE, $amount, $newBalance);

            $doctrine->getManager()->flush();

            return $this->json([
                'status' => 'success',
                'balance' => $wallet->getBalance()
            ]);
        } catch (Exception $exception) {
            return $this->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], $exception->getCode() ?? 400);
        }
    }
}
