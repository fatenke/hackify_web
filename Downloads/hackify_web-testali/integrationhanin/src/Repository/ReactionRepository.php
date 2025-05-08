<?php

namespace App\Repository;

use App\Entity\Reaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reaction>
 */
class ReactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reaction::class);
    }

    /**
     * Find reactions by message ID
     */
    public function findByMessageId(int $messageId): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.message = :messageId')
            ->setParameter('messageId', $messageId)
            ->orderBy('r.created_at', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get reaction counts grouped by emoji
     */
    public function getReactionCounts(int $messageId): array
    {
        $results = $this->createQueryBuilder('r')
            ->select('r.emoji, COUNT(r.id) as count')
            ->andWhere('r.message = :messageId')
            ->setParameter('messageId', $messageId)
            ->groupBy('r.emoji')
            ->getQuery()
            ->getResult();

        // Format results into a more usable structure
        $counts = [];
        foreach ($results as $result) {
            $counts[$result['emoji']] = (int) $result['count'];
        }

        return $counts;
    }

    /**
     * Get most used emojis across all messages
     */
    public function getMostUsedEmojis(int $limit = 10): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.emoji, COUNT(r.id) as count')
            ->groupBy('r.emoji')
            ->orderBy('count', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
} 