<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Upv6\Categories;

class CategoriesRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Categories::class);
	}

	public function getCategories($nbCategories, $currentPage)
	{
		if ($currentPage < 1) {
			throw new \InvalidArgumentException('L\'argument $page ne peut �tre inf�rieur � 1 (valeur : "'.$currentPage.'").');
		}
			
		$query = $this	->createQueryBuilder('c')
						->orderBy('c.internalName', 'ASC')
						->getQuery();
			
		$query	->setFirstResult(($currentPage-1) * $nbCategories)
				->setMaxResults($nbCategories);
			
		return new Paginator($query);
	}
	
	public function findCategoriesForBuilding($idBuilding)
	{
		$qb = $this	->createQueryBuilder('c')
					->leftJoin('c.devices', 'd')
					->leftJoin('d.room', 'r')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->where('b.id = :buildingId')
					->setParameter('buildingId', $idBuilding)
					->orderBy('c.internalName', 'ASC');
		
		return $qb	->getQuery()
					->getResult();
	}
	
	public function findCategoriesForFloor($idFloor, $idBuilding)
	{
		$qb	= $this	->createQueryBuilder('c')
					->leftJoin('c.devices', 'd')
					->leftJoin('d.room', 'r')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->orderBy('c.internalName', 'ASC');
		
		if($idBuilding != -1) {
			$qb = $this->buildingFilter($qb, $idBuilding);
		}
		
		if($idFloor != -1) {
			$qb = $this->floorFilter($qb, $idFloor);
		}
		
		return $qb	->getQuery()
					->getResult();
	}
	
	public function findCategoriesForRoomType($idRoomType, $idBuilding, $idFloor)
	{
		$qb = $this	->createQueryBuilder('c')
					->leftJoin('c.devices', 'd')
					->leftJoin('d.room', 'r')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->leftJoin('r.roomType', 'rt')
					->orderBy('c.internalName', 'ASC');
	
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
	
	public function findCategoriesForRoom($idRoom, $idRoomType, $idFloor, $idBuilding)
	{
		$qb	= $this	->createQueryBuilder('c')
					->leftJoin('c.devices', 'd')
					->leftJoin('d.room', 'r')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->leftJoin('r.roomType', 'rt')
					->orderBy('c.internalName', 'ASC');
	
		if($idRoom != -1) {
			$qb = $this->roomFilter($qb, $idRoom);
		}
		
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
	
	private function roomFilter($qb, $idRoom)
	{
		$qb	->andWhere('r.id = :idRoom')
			->setParameter('idRoom', $idRoom);
	
		return $qb;
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
}