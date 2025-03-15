<?php

namespace App\Controller;

use App\Repository\RequestHistoryRepository;
use App\Service\RegisterRequest;
use App\Service\StockMail;
use App\Service\StockService\StockServiceInterface;
use Nelmio\ApiDocBundle\Attribute\Security;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class StockController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/api/stock', name: 'app_stock_check', methods: ['GET'])]
    #[OA\Get(
        description: 'Checks stock info from a given symbol using Alpha Vantage API.',
        summary: 'Get stock info',
    )]
    #[OA\Parameter(
        name: 'q',
        description: 'The symbol of the stock to check, e.g. IBM',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[Security(name: 'Bearer')]
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
    #[OA\Get(
        description: 'Check request history from the user',
        summary: 'Get stock request history',
    )]
    #[OA\Parameter(
        name: 'page',
        description: 'The page number to retrieve the history, e.g. 1',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string'),
    )]
    #[Security(name: 'Bearer')]
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
