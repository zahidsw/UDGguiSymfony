<?php

namespace App\Repository;

use App\Entity\Der;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Der|null find($id, $lockMode = null, $lockVersion = null)
 * @method Der|null findOneBy(array $criteria, array $orderBy = null)
 * @method Der[]    findAll()
 * @method Der[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Der::class);
    }

    // /**
    //  * @return Der[] Returns an array of Der objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Der
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
