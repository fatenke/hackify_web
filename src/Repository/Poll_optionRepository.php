<?php

namespace App\Repository;

use App\Entity\Poll_option;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Poll_option>
 */
class Poll_optionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Poll_option::class);
    }

    // Add your custom methods here
}
