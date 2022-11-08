<?php

namespace App\Repository;

use App\Entity\OffreLangue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OffreLangue|null find($id, $lockMode = null, $lockVersion = null)
 * @method OffreLangue|null findOneBy(array $criteria, array $orderBy = null)
 * @method OffreLangue[]    findAll()
 * @method OffreLangue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreLangueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OffreLangue::class);
    }

    // /**
    //  * @return OffreLangue[] Returns an array of OffreLangue objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OffreLangue
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
