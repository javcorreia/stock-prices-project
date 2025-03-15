<?php

namespace App\Repository;

use App\Entity\RequestHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @extends ServiceEntityRepository<RequestHistory>
 */
class RequestHistoryRepository extends ServiceEntityRepository
{
    private CacheInterface $cache;

    public function __construct(ManagerRegistry $registry, CacheInterface $cache)
    {
        parent::__construct($registry, RequestHistory::class);
        $this->cache = $cache;
    }

    public function findByUserId(int $userId, int $page=1): array
    {
        return $this->cache->get('request_history_' . $userId . '_' . $page, function (ItemInterface $item) use ($userId, $page) : array {
            $offset = ($page - 1) * 10;
            $item->expiresAfter(300);

            return $this->createQueryBuilder('r')
                ->andWhere('r.user_id = :userId')
                ->setParameter('userId', $userId)
                ->orderBy('r.id', 'DESC')
                ->setFirstResult($offset)
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();
        });
    }

    public function countByUserId(int $userId): int
    {
        return $this->cache->get('request_history_count_' . $userId, function (ItemInterface $item) use ($userId) : int {
            $item->expiresAfter(300);

            return $this->createQueryBuilder('r')
                ->select('COUNT(r.id)')
                ->andWhere('r.user_id = :userId')
                ->setParameter('userId', $userId)
                ->getQuery()
                ->getSingleScalarResult();
        });
    }
}
