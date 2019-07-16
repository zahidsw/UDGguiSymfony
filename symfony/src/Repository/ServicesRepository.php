<?php

namespace App\Repository;

//use Doctrine\ORM\EntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Upv6\Services;

class ServicesRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Services::class);
	}

	public function getServices($nbServices, $currentPage)
	{
		if ($currentPage < 1) {
			throw new \InvalidArgumentException('L\'argument $page ne peut �tre inf�rieur � 1 (valeur : "'.$currentPage.'").');
		}
			
		$query = $this	->createQueryBuilder('s')
						->orderBy('s.name', 'ASC')
						->getQuery();
			
		$query	->setFirstResult(($currentPage-1) * $nbServices)
				->setMaxResults($nbServices);
			
		return new Paginator($query);
	}
}