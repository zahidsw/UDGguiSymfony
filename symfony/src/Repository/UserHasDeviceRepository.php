<?php

namespace App\Repository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Upv6\UserHasDevice;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserHasDeviceRepository extends EntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserHasDevice::class);
	}

	public function findUserDevice($user_id, $device_id)
	{
		$qb = $this->createQueryBuilder('uhd')
				->andWhere('uhd.userId = :userId')
					->setParameter('userId', $user_id)
				->andWhere('uhd.deviceId = :deviceId')
					->setParameter('deviceId', $device_id);
	
		return $qb->getQuery()->getResult();
	}
}
