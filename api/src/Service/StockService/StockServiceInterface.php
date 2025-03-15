<?php

namespace App\Service\StockService;

interface StockServiceInterface
{
    public function getStock(string $symbol): array;
}