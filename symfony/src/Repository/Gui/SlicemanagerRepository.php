<?php

namespace App\Repository\Gui;

use App\Entity\Gui\Slicemanager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Slicemanager|null find($id, $lockMode = null, $lockVersion = null)
 * @method Slicemanager|null findOneBy(array $criteria, array $orderBy = null)
 * @method Slicemanager[]    findAll()
 * @method Slicemanager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SlicemanagerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Slicemanager::class);
    }

    // /**
    //  * @return Slicemanager[] Returns an array of Slicemanager objects
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
    public function findOneBySomeField($value): ?Slicemanager
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
