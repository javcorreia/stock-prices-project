<?php

namespace App\Controller;

use App\Repository\RequestHistoryRepository;
use App\Service\RegisterRequest;
use App\Service\StockMail;
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
    public function stock(
        Request $request,
        StockServiceInterface $stockService,
        StockMail $stockMail,
        RegisterRequest $registerRequest,
    ): JsonResponse
    {
        if (!$request->query->has('q') || empty($request->query->get('q'))) {
            $this->logger->error('Query parameter is required');
            return $this->json([
                'message' => 'Query parameter is required',
            ], 400);
        }

        $stockInfo = $stockService->getStock($request->query->get('q'));
        $stockMail->send($this->getUser()->getEmail(), $stockInfo);
        $registerRequest->register(
            array_merge(
                ['date' => new \DateTimeImmutable()->format('Y-m-dTH:i:sZ')],
                $stockInfo,
            ),
            $this->getUser()->getId(),
            $request->getClientIp(),
        );

        return $this->json($stockInfo);
    }

    #[Route('/api/history', name: 'app_stock_history', methods: ['GET'])]
    public function history(Request $request, RequestHistoryRepository $repository): JsonResponse
    {
        $page = $request->query->get('page', 1);
        $total = $repository->countByUserId($this->getUser()->getId());

        $response = [];
        foreach ($repository->findByUserId($this->getUser()->getId(), $page) as $item) {
            $response[] = $item->getRequestData();
        }

        return $this->json([
            'total' => $total,
            'results' => $response,
        ]);
    }
}
