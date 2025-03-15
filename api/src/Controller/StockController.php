<?php

namespace App\Controller;

use App\Service\StockService\StockServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class StockController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/api/stock', name: 'app_stock_check', methods: ['GET'])]
    public function stock(Request $request, StockServiceInterface $stockService): JsonResponse
    {
        if (!$request->query->has('q') || empty($request->query->get('q'))) {
            $this->logger->error('Query parameter is required');
            return $this->json([
                'message' => 'Query parameter is required',
            ], 400);
        }

        return $this->json($stockService->getStock($request->query->get('q')));
    }
}
