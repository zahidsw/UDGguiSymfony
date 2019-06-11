<?php

namespace App\Repository;

use App\Entity\VNO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VNO|null find($id, $lockMode = null, $lockVersion = null)
 * @method VNO|null findOneBy(array $criteria, array $orderBy = null)
 * @method VNO[]    findAll()
 * @method VNO[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VNORepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VNO::class);
    }

    // /**
    //  * @return Pop[] Returns an array of Pop objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')b
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pop
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
