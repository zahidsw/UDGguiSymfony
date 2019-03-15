<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Upv6\Floors;
use Doctrine\ORM\Query\ResultSetMapping;

class FloorsRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Floors::class);
	}

	public function findFloorsForBuilding($idBuilding)
	{
		$qb = $this	->createQueryBuilder('f')
					->leftJoin('f.building', 'b')
					->where('b.id = :buildingId')
					->setParameter('buildingId', $idBuilding)
					->orderBy('f.name', 'ASC');
		
		return $qb	->getQuery()
					->getResult();
	}
	
	public function findFloorsOrderByBuildingName()
	{
		$qb = $this	->createQueryBuilder('f')
					->leftJoin('f.building', 'b')
					->orderBy('b.name,f.name', 'ASC');
		
		return $qb	->getQuery()
					->getResult();
	}
	
	public function getFloors($nbFloors, $currentPage, $idBuilding)
	{
		if ($currentPage < 1) {
			throw new \InvalidArgumentException('L\'argument $page ne peut �tre inf�rieur � 1 (valeur : "'.$currentPage.'").');
		}
			
		$query = $this	->createQueryBuilder('f')
						->orderBy('f.name', 'ASC');
		
		if($idBuilding != -1) {
			$query = $this->addBuildingFilter($query, $idBuilding);
		}
		
		
		$query	->getQuery();
		
		$query	->setFirstResult(($currentPage-1) * $nbFloors)
				->setMaxResults($nbFloors);
			
		return new Paginator($query);
	}
	
	private function addBuildingFilter($query, $idBuilding)
	{
		$query 	->leftJoin('f.building', 'b')
				->where('b.id = :idBuilding')
				->setParameter('idBuilding', $idBuilding);
		
		return $query;
	}
	
	public function getFloorsNotInGroups()
	{
		$sql = "SELECT id, name
				FROM floors
				WHERE id NOT IN (
					SELECT ghe.entity_id
					FROM group_has_entity ghe
						INNER JOIN groups g ON ghe.group_id = g.group_id
					WHERE g.group_category = 2
						AND ghe.deletion_date IS NULL
				)
				ORDER BY name ASC
				";
	
	
		$rsm = new ResultSetMapping();
		$rsm->addEntityResult('iot6\InteractBundle\Entity\Floors', 'f');
		$rsm->addFieldResult('f', 'id', 'id');
		$rsm->addFieldResult('f', 'name', 'name');
	
		$query = $this->_em->createNativeQuery($sql, $rsm);
	
		return $query->getResult();
	}
	
	public function getFloorsInGroup($idGroup)
	{
		$sql = "SELECT id, name
				FROM floors
				WHERE id IN (
					SELECT ghe.entity_id
					FROM group_has_entity ghe
					WHERE ghe.group_id = " . $idGroup . "
				)
				ORDER BY name ASC
				";
	
	
		$rsm = new ResultSetMapping();
		$rsm->addEntityResult('iot6\InteractBundle\Entity\Floors', 'f');
		$rsm->addFieldResult('f', 'id', 'id');
		$rsm->addFieldResult('f', 'name', 'name');
	
		$query = $this->_em->createNativeQuery($sql, $rsm);
	
		return $query->getResult();
	}
}
