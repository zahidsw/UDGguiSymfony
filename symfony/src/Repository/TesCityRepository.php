<?php

namespace App\Repository;

use App\Entity\TesCity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TesCity|null find($id, $lockMode = null, $lockVersion = null)
 * @method TesCity|null findOneBy(array $criteria, array $orderBy = null)
 * @method TesCity[]    findAll()
 * @method TesCity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TesCityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesCity::class);
    }

    // /**
    //  * @return TesCity[] Returns an array of TesCity objects
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
    public function findOneBySomeField($value): ?TesCity
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
