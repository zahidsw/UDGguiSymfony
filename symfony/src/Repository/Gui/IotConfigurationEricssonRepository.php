<?php

namespace App\Repository\Gui;

use App\Entity\Gui\IotConfigurationEricsson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IotConfigurationEricsson|null find($id, $lockMode = null, $lockVersion = null)
 * @method IotConfigurationEricsson|null findOneBy(array $criteria, array $orderBy = null)
 * @method IotConfigurationEricsson[]    findAll()
 * @method IotConfigurationEricsson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IotConfigurationEricssonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IotConfigurationEricsson::class);
    }

    // /**
    //  * @return IotConfigurationEricsson[] Returns an array of IotConfigurationEricsson objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IotConfigurationEricsson
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
