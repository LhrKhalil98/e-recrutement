<?php

namespace App\Repository;

use App\Entity\Offre;
use App\Data\SearchOffreData;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }


    public function countOffre1 ($value  ) {
        return $this->createQueryBuilder('c')
            ->select('count(c.id) ')

            ->where('YEAR(c.date_creation) = :year')
            ->setParameter('year', $value)
            ->getQuery()
            ->getSingleScalarResult()
        ;

    }
    public function countOffre ($value  ,  $value2 ) {
        return $this->createQueryBuilder('c')
            ->select('count(c.id) ')

            ->where('YEAR(c.date_creation) = :year')
            ->andWhere('c.id_categorie = :categorie')
            ->setParameter('year', $value)
            ->setParameter('categorie', $value2)

            ->getQuery()
            ->getSingleScalarResult()
            
        ;

    }

    public function filterOffer( SearchOffreData $data) {
        $query = $this->createQueryBuilder(('o'))
                      ->select('c','o')
                      ->join('o.id_categorie','c')
                      ->orderBy('o.id', 'ASC');

         if(!empty($data->categorie)){
            $query=$query
            ->andWhere('c.id IN (:categorie)')
            ->setParameter('categorie' ,$data->categorie);
        } 
        if(!empty($data->ref)){
            $query=$query
            ->andWhere('o.intitule_offre LIKE :c')
            ->setParameter('c' ,"%{$data->ref}%");

        }
         if(!empty($data->etat)){
            $query=$query
            ->andWhere('o.etat = :etat')
            ->setParameter('etat' ,$data->etat);
        } 
         if(!empty($data->annee)){
       
            $query=$query
            
            ->andWhere('YEAR(o.date_creation) = :annee ')
            ->setParameter('annee' ,$data->annee);
        } 
         if(!empty($data->status)){
            $query=$query
            ->andWhere('o.status = :status')
            ->setParameter('status' ,$data->status);
        } 
         if(!empty($data->debut)){
            $query=$query
            ->andWhere('o.date_debut = :debut')
            ->setParameter('debut' ,$data->debut);
        } 
         if(!empty($data->fin)){
            $query=$query
            ->andWhere('o.date_fin IN :fin')
            ->setParameter('fin' ,$data->fin);
        } 
        return $query->getQuery()->getResult() ;

    }

    // /**
    //  * @return Offre[] Returns an array of Offre objects
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
    public function findOneBySomeField($value): ?Offre
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
