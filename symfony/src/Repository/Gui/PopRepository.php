<?php

namespace App\Repository\Gui;

use App\Entity\Gui\Pop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Pop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pop[]    findAll()
 * @method Pop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PopRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Pop::class);
    }

    // /**
    //  * @return Pop[] Returns an array of Pop objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
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
