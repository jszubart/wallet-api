<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class WalletController extends AbstractController
{
    /**
     * @Route("/api", name="api_base", methods={"GET"})
     */
    public function base(): JsonResponse
    {
        return $this->json([
            'connection' => 'live'
        ]);
    }
}
