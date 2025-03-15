<?php

namespace App\Service\StockService;

use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AlphaVantageService implements StockServiceInterface
{
    private CacheInterface $cache;
    private string $url;
    private HttpClientInterface $httpClient;
    private string $apiKey;

    public function __construct(
        CacheInterface $cache,
        HttpClientInterface $httpClient,
        string $apiKey,
        string $url
    )
    {
        $this->cache = $cache;
        $this->apiKey = $apiKey;
        $this->url = $url;
        $this->httpClient = $httpClient;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getStock(string $symbol): array
    {
        return $this->cache->get('stock_service_get', function (ItemInterface $item) use ($symbol) : array {
            $now = new \DateTime();
            $endOfDay = (clone $now)->setTime(23, 59, 59);
            $totalSeconds = $endOfDay->getTimestamp() - $now->getTimestamp();
            $item->expiresAfter($totalSeconds);

            // get stock from alpha vantage
            $response = $this->httpClient->request(
                'GET',
                $this->url,
                [
                    'query' => [
                        'function' => 'GLOBAL_QUOTE',
                        'symbol' => $symbol,
                        'apikey' => $this->apiKey,
                    ]
                ],
            );

            return $this->transformResponse($response->toArray(), $symbol);
        });
    }

    private function transformResponse(array $response, string $symbol): array
    {
        return [
            'name' => $response['Global Quote']['01. symbol'],
            'symbol' => strtoupper($symbol),
            'open' => $response['Global Quote']['02. open'],
            'high' => $response['Global Quote']['03. high'],
            'low' => $response['Global Quote']['04. low'],
            'close' => $response['Global Quote']['08. previous close'],
        ];
    }
}