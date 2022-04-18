<?php

namespace App\Repository;

use App\Entity\Recit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recit[]    findAll()
 * @method Recit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recit::class);
    }

    // /**
    //  * @return Recit[] Returns an array of Recit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recit
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
