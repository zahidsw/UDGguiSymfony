<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Upv6\EventFilters;
use Symfony\Bridge\Doctrine\RegistryInterface;

class EventFiltersRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EventFilters::class);
    }
	public function findEventsForDevices($devicesList, $kind)
	{
		$qb = $this	->createQueryBuilder('ef')
					->select('a.id, a.internalName')
					->distinct()
					->innerJoin('ef.action', 'a')
					->where('ef.device IN (:devicesList)')
						->setParameter('devicesList', $devicesList)
					->andWhere('a.kind = :kind')
						->setParameter('kind', $kind)
					->orderBy('a.internalName', 'ASC');
	
		return $qb	->getQuery()
					->getResult();
	}
}