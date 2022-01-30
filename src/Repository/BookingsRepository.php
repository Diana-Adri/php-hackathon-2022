<?php

namespace App\Repository;

use App\Entity\Bookings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bookings|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookings|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookings[]    findAll()
 * @method Bookings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookings::class);
    }

    public function getBookingsCountByProgram($program_id)
    {

        return $this->createQueryBuilder('b')
            ->andWhere('b.Program = :program_id')
            ->orderBy('b.Program', 'DESC')
            ->select('count(b.id)')
            ->setParameter('program_id', $program_id)
            ->getQuery()
            ->getSingleScalarResult();
    }

}
