<?php

namespace App\Service;

use App\Entity\RequestHistory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class RegisterRequest
{
    private EntityManagerInterface $entityManager;
    private CacheInterface $cache;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function register(array $requestData, int $userId, ?string $ipaddress=null): void
    {
        $requestHistory = new RequestHistory();
        $requestHistory->setRequestDate(new \DateTimeImmutable());
        $requestHistory->setRequestData($requestData);
        $requestHistory->setUserId($userId);
        $requestHistory->setIpAddress($ipaddress);

        $this->entityManager->persist($requestHistory);
        $this->entityManager->flush();
    }
}