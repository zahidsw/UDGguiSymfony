<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Upv6\Schedules;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


class SchedulesRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Schedules::class);
	}
	
    public function getSchedules($nbSchedules, $currentPage, $isRegular)
    {
		if ($currentPage < 1)
		{
    		throw new \InvalidArgumentException('L\'argument $page ne peut �tre inf�rieur � 1 (valeur : "'.$currentPage.'").');
    	}
    	
    	$query = $this	->createQueryBuilder('s')
    					->leftJoin('s.rule', 'r')
	    				->orderBy('r.name', 'ASC')
	    				;
    	
    	if($isRegular) {
    		$query = $this->addRegularFilter($query);
    	}
    	else {
    		$query = $this->addPonctualFilter($query);
    	}
    	
    	$query ->getQuery();
    	
    	$query	->setFirstResult(($currentPage-1) * $nbSchedules)
    			->setMaxResults($nbSchedules);
    	
    	return new Paginator($query);
    }
    
    private function addRegularFilter($qb)
    {
    	$qb ->where('s.scheduleCron LIKE :param')
    		->setParameter('param', '%*%');
    
    	return $qb;
    }
    
    private function addPonctualFilter($qb)
    {
    	$qb ->where('s.scheduleCron NOT LIKE :param')
    		->setParameter('param', '%*%');
    
    	return $qb;
    }
}