<?php

namespace App\Repository;

use App\Entity\Upv6\Rules;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class RulesRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Rules::class);
	}

    public function getRules($nbRules, $currentPage, $isActive)
    {
		if ($currentPage < 1)
		{
    		throw new \InvalidArgumentException('L\'argument $page ne peut �tre inf�rieur � 1 (valeur : "'.$currentPage.'").');
    	}
    	
    	$query = $this	->createQueryBuilder('r')
    					->where('r.isActive = :isActive')
    					->setParameter('isActive', $isActive)
	    				->orderBy('r.name', 'ASC')
	    				->getQuery();
    	
    	$query	->setFirstResult(($currentPage-1) * $nbRules)
    			->setMaxResults($nbRules);
    	
    	return new Paginator($query);
    }
}