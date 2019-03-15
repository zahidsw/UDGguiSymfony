<?php

namespace App\Repository;

use App\Entity\S;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method S|null find($id, $lockMode = null, $lockVersion = null)
 * @method S|null findOneBy(array $criteria, array $orderBy = null)
 * @method S[]    findAll()
 * @method S[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, S::class);
    }

    // /**
    //  * @return S[] Returns an array of S objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?S
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
