<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Upv6\Families;

class FamiliesRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Families::class);
	}
	public function getFamilies($nbFamilies, $currentPage)
	{
		if ($currentPage < 1) {
			throw new \InvalidArgumentException('L\'argument $page ne peut �tre inf�rieur � 1 (valeur : "'.$currentPage.'").');
		}
			
		$query = $this	->createQueryBuilder('f')
						->orderBy('f.internalName', 'ASC')
						->getQuery();
			
		$query	->setFirstResult(($currentPage-1) * $nbFamilies)
				->setMaxResults($nbFamilies);
			
		return new Paginator($query);
	}
	
	public function findFamiliesForBuilding($idBuilding)
	{
		$qb = $this	->createQueryBuilder('f')
					->leftJoin('f.devices', 'd')
					->leftJoin('d.room', 'r')
					->leftJoin('r.floor', 'fl')
					->leftJoin('fl.building', 'b')
					->where('b.id = :buildingId')
					->setParameter('buildingId', $idBuilding)
					->orderBy('f.internalName', 'ASC');
	
		return $qb	->getQuery()
					->getResult();
	}
	
	public function findFamiliesForFloor($idFloor, $idBuilding)
	{
		$qb	= $this	->createQueryBuilder('fa')
					->leftJoin('fa.devices', 'd')
					->leftJoin('d.room', 'r')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->orderBy('fa.internalName', 'ASC');
		
		if($idBuilding != -1) {
			$qb = $this->buildingFilter($qb, $idBuilding);
		}
		
		if($idFloor != -1) {
			$qb = $this->floorFilter($qb, $idFloor);
		}
		
		return $qb	->getQuery()
					->getResult();
	}
	
	public function findFamiliesForRoomType($idRoomType, $idBuilding, $idFloor)
	{
		$qb = $this	->createQueryBuilder('fa')
					->leftJoin('fa.devices', 'd')
					->leftJoin('d.room', 'r')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->leftJoin('r.roomType', 'rt')
					->orderBy('fa.internalName', 'ASC');
	
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
	
	public function findFamiliesForRoom($idRoom, $idRoomType, $idFloor, $idBuilding)
	{
		$qb	= $this	->createQueryBuilder('fa')
					->leftJoin('fa.devices', 'd')
					->leftJoin('d.room', 'r')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->leftJoin('r.roomType', 'rt')
					->orderBy('fa.internalName', 'ASC');
		
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
	
	public function findFamiliesForCategory($idCategory, $idRoom, $idRoomType, $idFloor, $idBuilding)
	{
		$qb = $this	->createQueryBuilder('fa')
					->leftJoin('fa.devices', 'd')
					->leftJoin('d.category', 'c')
					->leftJoin('d.room', 'r')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->leftJoin('r.roomType', 'rt')
					->orderBy('fa.internalName', 'ASC');
	
		if($idCategory != -1) {
			$qb = $this->categoryFilter($qb, $idCategory);
		}
		
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
	
	private function categoryFilter($qb, $idCategory) {
		$qb	->andWhere('c.id = :idCategory')
			->setParameter('idCategory', $idCategory);
	
		return $qb;
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