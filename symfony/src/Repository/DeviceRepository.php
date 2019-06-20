<?php

namespace App\Repository;

use App\Entity\Gui\Device;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Device|null find($id, $lockMode = null, $lockVersion = null)
 * @method Device|null findOneBy(array $criteria, array $orderBy = null)
 * @method Device[]    findAll()
 * @method Device[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Device::class);
    }

    // /**
    //  * @return Device[] Returns an array of Device objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Device
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getDevicesNotInGroups()
	{
		$sql = "SELECT id, assigned_name
				FROM devices
				WHERE id NOT IN (
					SELECT ghe.entity_id
					FROM group_has_entity ghe
						INNER JOIN groups g ON ghe.group_id = g.group_id
					WHERE g.group_category = 4
						AND ghe.deletion_date IS NULL
				)
				ORDER BY assigned_name ASC
				";
		
		
		$rsm = new ResultSetMapping();
		$rsm->addEntityResult('App\Entity\Upv6\Devices', 'd');
		$rsm->addFieldResult('d', 'id', 'id');
		$rsm->addFieldResult('d', 'assigned_name', 'assignedName');

		$query = $this->_em->createNativeQuery($sql, $rsm);
		
		return $query->getResult();
	}
}
