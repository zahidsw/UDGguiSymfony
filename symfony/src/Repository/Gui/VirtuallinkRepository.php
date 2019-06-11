<?php

namespace App\Repository\Gui;

use App\Entity\Gui\Virtuallink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Virtuallink|null find($id, $lockMode = null, $lockVersion = null)
 * @method Virtuallink|null findOneBy(array $criteria, array $orderBy = null)
 * @method Virtuallink[]    findAll()
 * @method Virtuallink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VirtuallinkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Virtuallink::class);
    }

    // /**
    //  * @return Virtuallink[] Returns an array of Virtuallink objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Virtuallink
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
