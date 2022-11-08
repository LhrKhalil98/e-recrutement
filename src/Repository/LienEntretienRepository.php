<?php

namespace App\Repository;

use App\Entity\LienEntretien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LienEntretien|null find($id, $lockMode = null, $lockVersion = null)
 * @method LienEntretien|null findOneBy(array $criteria, array $orderBy = null)
 * @method LienEntretien[]    findAll()
 * @method LienEntretien[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LienEntretienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LienEntretien::class);
    }

    // /**
    //  * @return LienEntretien[] Returns an array of LienEntretien objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LienEntretien
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
