<?php

namespace App\Repository;

use App\Entity\Candidature;
use App\Data\SearchCandidatData;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Candidature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidature[]    findAll()
 * @method Candidature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidature::class);
    }
    
    public function countCand ($value ) {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('YEAR(c.candidature_date) = :year')
            ->setParameter('year', $value)
            ->getQuery()
            ->getSingleScalarResult()
        ;

    }

    public function filterCandidat( SearchCandidatData $data) {
        $query = $this->createQueryBuilder(('o'))
                      ->select('c','o' , 'p' , 'po' )
                      ->join('o.cand_id','c')
                      ->join('c.pays', 'p')
                      ->join('o.poste', 'po')
                      ->orderBy('o.id', 'ASC');

     
        if(!empty($data->ref)){
            $query=$query
            ->andWhere('o.REF LIKE :ref')
            ->setParameter('ref' ,"REF/CAN/{$data->ref}%");

        }
        if(!empty($data->email)){
            $query=$query
            ->andWhere('c.cand_email LIKE :c')
            ->setParameter('c' ,"%{$data->email}%");
        }
        if(!empty($data->genre)){
            $query=$query
            ->andWhere('c.sexe LIKE :c')
            ->setParameter('c' ,"%{$data->genre}%");
        }
        if(!empty($data->pays)){
           
            $query=$query
            ->andWhere('p.id_pays LIKE :c')
            ->setParameter('c' ,"%{$data->pays}%");
        }
        if(!empty($data->poste)){
           
            $query=$query
            ->andWhere('po.id IN (:c) ')
            ->setParameter('c' ,$data->poste);
        }

       
  
        
     
   
     
        return $query->getQuery()->getResult() ;

    }
    // /**
    //  * @return Candidature[] Returns an array of Candidature objects
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
    public function findOneBySomeField($value): ?Candidature
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
