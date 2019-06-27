<?php

namespace App\Repository;

use App\Entity\CityDevice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CityDevice|null find($id, $lockMode = null, $lockVersion = null)
 * @method CityDevice|null findOneBy(array $criteria, array $orderBy = null)
 * @method CityDevice[]    findAll()
 * @method CityDevice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityDeviceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CityDevice::class);
    }

    // /**
    //  * @return CityDevice[] Returns an array of CityDevice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CityDevice
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
