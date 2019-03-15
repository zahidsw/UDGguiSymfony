<?php

namespace App\Repository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Upv6\Buildings;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BuildingsRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Buildings::class);
	}

	public function getBuildings($nbBuildings, $currentPage)
	{
		if ($currentPage < 1) {
			throw new \InvalidArgumentException('L\'argument $page ne peut �tre inf�rieur � 1 (valeur : "'.$currentPage.'").');
		}
		 
		$query = $this	->createQueryBuilder('b')
						->orderBy('b.name', 'ASC')
						->getQuery();
		 
		$query	->setFirstResult(($currentPage-1) * $nbBuildings)
				->setMaxResults($nbBuildings);
		 
		return new Paginator($query);
	}
}