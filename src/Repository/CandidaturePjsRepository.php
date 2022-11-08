<?php

namespace App\Repository;

use App\Entity\CandidaturePjs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CandidaturePjs|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandidaturePjs|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandidaturePjs[]    findAll()
 * @method CandidaturePjs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidaturePjsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CandidaturePjs::class);
    }

    // /**
    //  * @return CandidaturePjs[] Returns an array of CandidaturePjs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CandidaturePjs
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
