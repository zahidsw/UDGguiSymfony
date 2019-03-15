<?php

namespace App\Repository;

use App\Entity\Testz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Testz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Testz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Testz[]    findAll()
 * @method Testz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestzRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Testz::class);
    }

    // /**
    //  * @return Testz[] Returns an array of Testz objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Testz
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
