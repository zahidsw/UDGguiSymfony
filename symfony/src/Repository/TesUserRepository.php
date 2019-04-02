<?php

namespace App\Repository;

use App\Entity\TesUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TesUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method TesUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method TesUser[]    findAll()
 * @method TesUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TesUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesUser::class);
    }

    // /**
    //  * @return TesUser[] Returns an array of TesUser objects
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
    public function findOneBySomeField($value): ?TesUser
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
