<?php

namespace App\Repository\Gui;

use App\Entity\Gui\SliceManagerEricsson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SliceManagerEricsson|null find($id, $lockMode = null, $lockVersion = null)
 * @method SliceManagerEricsson|null findOneBy(array $criteria, array $orderBy = null)
 * @method SliceManagerEricsson[]    findAll()
 * @method SliceManagerEricsson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SliceManagerEricssonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SliceManagerEricsson::class);
    }

    // /**
    //  * @return SliceManagerEricsson[] Returns an array of SliceManagerEricsson objects
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
    public function findOneBySomeField($value): ?SliceManagerEricsson
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