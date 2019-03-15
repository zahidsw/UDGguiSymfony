<?php

namespace App\Repository;

use App\Entity\Far;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Far|null find($id, $lockMode = null, $lockVersion = null)
 * @method Far|null findOneBy(array $criteria, array $orderBy = null)
 * @method Far[]    findAll()
 * @method Far[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Far::class);
    }

    // /**
    //  * @return Far[] Returns an array of Far objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Far
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
