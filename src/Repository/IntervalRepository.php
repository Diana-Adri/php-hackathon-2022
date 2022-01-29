<?php

namespace App\Repository;

use App\Entity\Interval;
use App\Entity\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Interval|null find($id, $lockMode = null, $lockVersion = null)
 * @method Interval|null findOneBy(array $criteria, array $orderBy = null)
 * @method Interval[]    findAll()
 * @method Interval[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntervalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Program::class);
    }
}