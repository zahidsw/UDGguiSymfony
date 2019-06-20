<?php

namespace App\Repository;

use App\Entity\Upv6\Rooms;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class RoomsRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Rooms::class);
	}

	public function findRoomsForBuilding($idBuilding)
	{
		$qb = $this->createQueryBuilder('r');
		
		$qb	->select('r')
			->leftJoin('r.floor', 'f')
			->leftJoin('f.building', 'b')
			->where('b.id = :buildingId')
			->setParameter('buildingId', $idBuilding)
			->orderBy('r.name', 'ASC');
		
		return $qb	->getQuery()
					->getResult();
	}
	
	public function findRoomsForFloor($idFloor, $idBuilding)
	{
		$qb	= $this	->createQueryBuilder('r')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->orderBy('r.name', 'ASC');
		
		if($idBuilding != -1) {
			$qb = $this->buildingFilter($qb, $idBuilding);
		}
		
		if($idFloor != -1) {
			$qb = $this->floorFilter($qb, $idFloor);
		}
		
		return $qb	->getQuery()
					->getResult();
	}
	
	public function findRoomsForRoomType($idRoomType, $idBuilding, $idFloor)
	{
		$qb = $this	->createQueryBuilder('r')
					->leftJoin('r.roomType', 'rt')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->orderBy('r.name', 'ASC');
		
		if($idRoomType != -1) {
			$qb = $this->roomTypeFilter($qb, $idRoomType);
		}
		
		if($idBuilding != -1) {
			$qb = $this->buildingFilter($qb, $idBuilding);
		}
		
		if($idFloor != -1) {
			$qb = $this->floorFilter($qb, $idFloor);
		}
		
		return $qb	->getQuery()
					->getResult();
	}
	
	private function roomTypeFilter($qb, $idRoomType)
	{
		$qb	->andWhere('rt.id = :idRoomType')
			->setParameter('idRoomType', $idRoomType);
	
		return $qb;
	}
	
	private function buildingFilter($qb, $idBuilding)
	{
		$qb	->andWhere('b.id = :idBuilding')
			->setParameter('idBuilding', $idBuilding);
	
		return $qb;
	}
	
	private function floorFilter($qb, $idFloor)
	{
		$qb	->andWhere('f.id = :idFloor')
			->setParameter('idFloor', $idFloor);
	
		return $qb;
	}
	
	
	public function getRooms($nbRooms, $currentPage, $idFloor)
	{
		if ($currentPage < 1) {
			throw new \InvalidArgumentException('L\'argument $page ne peut �tre inf�rieur � 1 (valeur : "'.$currentPage.'").');
		}
			
		$query = $this	->createQueryBuilder('r')
						->orderBy('r.name', 'ASC');
	
		if($idFloor != -1) {
			$query = $this->addFloorFilter($query, $idFloor);
		}
	
	
		$query	->getQuery();
	
		$query	->setFirstResult(($currentPage-1) * $nbRooms)
				->setMaxResults($nbRooms);
			
		return new Paginator($query);
	}
	
	private function addFloorFilter($query, $idFloor)
	{
		$query 	->leftJoin('r.floor', 'f')
				->where('f.id = :idFloor')
				->setParameter('idFloor', $idFloor);
	
		return $query;
	}

	public function getRoomsNotInGroups()
	{
		$sql = "SELECT id, name
				FROM rooms
				WHERE id NOT IN (
					SELECT ghe.entity_id
					FROM group_has_entity ghe
						INNER JOIN groups g ON ghe.group_id = g.group_id
					WHERE g.group_category = 3
						AND ghe.deletion_date IS NULL
				)
				ORDER BY name ASC
				";
	
	
		$rsm = new ResultSetMapping();
		$rsm->addEntityResult('App\Entity\Upv6\Rooms', 'r');
		$rsm->addFieldResult('r', 'id', 'id');
		$rsm->addFieldResult('r', 'name', 'name');
	
		$query = $this->_em->createNativeQuery($sql, $rsm);
	
		return $query->getResult();
	}
}