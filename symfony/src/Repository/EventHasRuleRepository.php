<?php

namespace App\Repository;


use App\Entity\Upv6\EventHasRule;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class EventHasRuleRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EventHasRule::class);
	}
	public function getTriggers($nbTriggers, $currentPage, $isActive)
	{
		if ($currentPage < 1)
		{
			throw new \InvalidArgumentException('L\'argument $page ne peut �tre inf�rieur � 1 (valeur : "'.$currentPage.'").');
		}
		 
		$query = $this	->createQueryBuilder('ehr')
						->addSelect('d')
						->leftJoin('ehr.device', 'd')
						->where('ehr.isActive = :isActive')
						->setParameter('isActive', $isActive)
		 				->getQuery();
		 				
		$query	->setFirstResult(($currentPage-1) * $nbTriggers)
				->setMaxResults($nbTriggers);
		
		
		
		return new Paginator($query);
	}
}
