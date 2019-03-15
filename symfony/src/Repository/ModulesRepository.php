<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Upv6\Modules;


class ModulesRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Modules::class);
	}

	public function getModules($nbModules, $currentPage)
	{
		if ($currentPage < 1) {
			throw new \InvalidArgumentException('L\'argument $page ne peut �tre inf�rieur � 1 (valeur : "'.$currentPage.'").');
		}
			
		$query = $this	->createQueryBuilder('m')
						->orderBy('m.name', 'ASC')
						->getQuery();
			
		$query	->setFirstResult(($currentPage-1) * $nbModules)
				->setMaxResults($nbModules);
			
		return new Paginator($query);
	}
}