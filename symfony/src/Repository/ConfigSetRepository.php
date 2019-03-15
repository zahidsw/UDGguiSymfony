<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Gui\User;
use App\Entity\Upv6\ConfigSet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


class ConfigSetRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ConfigSet::class);
	}

	public function findActiveOrderByUser()
	{
		$qb = $this	->createQueryBuilder('cs')
					->leftJoin('cs.user', 'u')
					->orderBy('u.name', 'ASC')
					->addOrderBy('cs.active', 'DESC')
					->addOrderBy('cs.name', 'ASC');
		
		return $qb	->getQuery()
		->getResult();
	}
}