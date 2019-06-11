<?php

namespace App\Repository\Gui;

use App\Entity\Gui\Flavourkeys;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Flavourkeys|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flavourkeys|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flavourkeys[]    findAll()
 * @method Flavourkeys[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlavourkeysRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Flavourkeys::class);
    }

    // /**
    //  * @return Flavourkeys[] Returns an array of Flavourkeys objects
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
    public function findOneBySomeField($value): ?Flavourkeys
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
