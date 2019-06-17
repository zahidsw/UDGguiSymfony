<?php

namespace App\Repository\Gui;

use App\Entity\Gui\Securitygroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Securitygroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method Securitygroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method Securitygroup[]    findAll()
 * @method Securitygroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecuritygroupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Securitygroup::class);
    }

    // /**
    //  * @return securitygroup[] Returns an array of securitygroup objects
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
    public function findOneBySomeField($value): ?securitygroup
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
