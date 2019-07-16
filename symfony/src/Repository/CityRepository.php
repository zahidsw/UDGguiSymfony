<?php

namespace App\Repository;

use App\Entity\Gui\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, City::class);
    }

    
    public function findAccreditation($device, $city)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
         SELECT c.id, c.name, cd.accreditedByCityId, cd.device_id, cd.accreditedAccessProfile
         FROM city c
         LEFT JOIN CityDevice cd ON (c.id = cd.city_id AND accreditedByCityId = :cityId AND cd.device_id = :deviceId)
         ORDER BY c.name
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['cityId' => $city->getId(), 'deviceId' => $device->getId()]);

        return $stmt->fetchAll();
    }

    // SELECT * 
    // FROM city c
    // LEFT JOIN CityDevice cd ON (c.id = cd.city_id AND accreditedByCityId = 1 AND device = 6


    // /**
    //  * @return City[] Returns an array of City objects
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
    public function findOneBySomeField($value): ?City
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
