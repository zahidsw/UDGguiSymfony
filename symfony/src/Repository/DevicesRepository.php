<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Upv6\Devices;
use Doctrine\ORM\Query\ResultSetMapping;
use App\Entity\Upv6\Cards;
use App\Entity\Upv6\Protocols;

class DevicesRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Devices::class);
	}

	public function findDevicesByLocation($idBuilding=-1, $idFloor=-1, $idRoomType=-1, 
			$idRoom=-1, $idCategory=-1, $idFamily=-1)
	{
		$qb = $this	->createQueryBuilder('d')
					->leftJoin('d.room', 'r')
					->leftJoin('r.roomType', 'rt')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->leftJoin('d.module', 'm')
					->leftJoin('m.actions', 'a')
					->leftJoin('d.category', 'c')
					->leftJoin('d.family', 'fa')
					->where('d.room is NOT NULL')
					->orderBy('d.assignedName', 'ASC');
		
		if($idBuilding != -1) {
			$qb = $this->buildingFilter($qb, $idBuilding);
		}
		
		if($idFloor != -1) {
			$qb = $this->floorFilter($qb, $idFloor);
		}
		
		if($idRoomType != -1) {
			$qb = $this->roomTypeFilter($qb, $idRoomType);
		}
		
		if($idRoom != -1) {
			$qb = $this->roomFilter($qb, $idRoom);
		}
		
		if($idCategory != -1) {
			$qb = $this->categoryFilter($qb, $idCategory);
		}
		
		if($idFamily != -1) {
			$qb = $this->familyFilter($qb, $idFamily);
		}
		
		return $qb	->getQuery()
					->getResult();
	}
	
	public function findDevicesForBuilding($idBuilding)
	{
		$qb = $this	->createQueryBuilder('d')
					->leftJoin('d.room', 'r')
					->leftJoin('r.floor', 'fl')
					->leftJoin('fl.building', 'b')
					->where('b.id = :buildingId')
					->setParameter('buildingId', $idBuilding)
					->orderBy('d.assignedName', 'ASC');
	
		return $qb	->getQuery()
					->getResult();
	}
	
	public function findDevicesForFloor($idFloor, $idBuilding)
	{
		$qb	= $this	->createQueryBuilder('d')
					->leftJoin('d.room', 'r')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->orderBy('d.assignedName', 'ASC');
		
		if($idBuilding != -1) {
			$qb = $this->buildingFilter($qb, $idBuilding);
		}
		
		if($idFloor != -1) {
			$qb = $this->floorFilter($qb, $idFloor);
		}
		
		return $qb	->getQuery()
					->getResult();
	}
	
	public function findDevicesForRoomType($idRoomType, $idBuilding, $idFloor)
	{
		$qb = $this	->createQueryBuilder('d')
					->leftJoin('d.room', 'r')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->leftJoin('r.roomType', 'rt')
					->orderBy('d.assignedName', 'ASC');
	
		if($idRoomType != -1) {
			$qb = $this->roomTypeFilter($qb, $idRoomType);
		}
	
		if($idBuilding != -1) {
			$qb = $this->buildingFilter($qb, $idBuilding);
		}
	
		if($idFloor != -1) {
			$qb = $this->floorFilter($qb, $idFloor);
		}
	
		$qb->addOrderBy('d.assignedName', 'ASC');
	
		return $qb	->getQuery()
					->getResult();
	}
	
	public function findDevicesForRoom($idRoom, $idRoomType, $idFloor, $idBuilding)
	{
		$qb	= $this	->createQueryBuilder('d')
					->leftJoin('d.room', 'r')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->leftJoin('r.roomType', 'rt')
					->orderBy('d.assignedName', 'ASC');
	
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
	
	public function findDevicesForCategory($idCategory, $idRoom, $idRoomType, $idFloor, $idBuilding)
	{
		$qb	= $this	->createQueryBuilder('d')
					->leftJoin('d.category', 'c')
					->leftJoin('d.room', 'r')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->leftJoin('r.roomType', 'rt')
					->orderBy('d.assignedName', 'ASC');
		
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
	
	public function findDevicesForFamily($idFamily, $idCategory, $idRoom, $idRoomType, $idFloor, $idBuilding)
	{
		$qb	= $this	->createQueryBuilder('d')
					->leftJoin('d.family', 'fa')
					->leftJoin('d.category', 'c')
					->leftJoin('d.room', 'r')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->leftJoin('r.roomType', 'rt')
					->orderBy('d.assignedName', 'ASC');
		
		if($idFamily != -1) {
			$qb = $this->familyFilter($qb, $idFamily);
		}
		
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
	
	private function roomTypeFilter($qb, $idRoomType)
	{
		$qb	->andWhere('rt.id = :idRoomType')
			->setParameter('idRoomType', $idRoomType);
	
		return $qb;
	}
	
	private function roomFilter($qb, $idRoom)
	{
		$qb	->andWhere('r.id = :idRoom')
			->setParameter('idRoom', $idRoom);
	
		return $qb;
	}
	
	private function categoryFilter($qb, $idCategory)
	{
		$qb	->andWhere('c.id = :idCategory')
			->setParameter('idCategory', $idCategory);
	
		return $qb;
	}
	
	private function familyFilter($qb, $idFamily)
	{
		$qb	->andWhere('fa.id = :idFamily')
			->setParameter('idFamily', $idFamily);
	
		return $qb;
	}
	
	public function findDevicesByCardAndProtocol(Cards $card, Protocols $protocol)
	{
		$qb = $this	->createQueryBuilder('d')
					->leftJoin('d.card', 'c')
					->leftJoin('d.protocol', 'p')
					->where('c.id = :idCard')
						->setParameter('idCard', $card->getId())
					->andWhere('p.id = :idProtocol')
						->setParameter('idProtocol', $protocol->getId())
					->orderBy('d.assignedName', 'ASC');
		
		return $qb	->getQuery()
					->getResult();
	}

	public function getDevices($nbDevices, $currentPage)
	{
		if ($currentPage < 1) {
			throw new \InvalidArgumentException('L\'argument $page ne peut �tre inf�rieur � 1 (valeur : "'.$currentPage.'").');
		}
			
		$query = $this	->createQueryBuilder('d')
						->where('d.validationStatus = :validationStatus')
							->setParameter('validationStatus', 0)
						->orderBy('d.assignedName', 'ASC')
						->getQuery();
			
		$query	->setFirstResult(($currentPage-1) * $nbDevices)
				->setMaxResults($nbDevices);
			
		return new Paginator($query);
	}
	
	public function findActionsForDevices($devicesList, $kind)
	{
		$qb = $this	->createQueryBuilder('d')
					->select('a.id, a.internalName')
					->distinct()
					->innerJoin('d.module', 'm')
					->innerJoin('m.actions', 'a')
					->where('d.id IN (:devicesList)')
						->setParameter('devicesList', $devicesList)
					->andWhere('a.kind = :kind')
						->setParameter('kind', $kind)
					->orderBy('a.internalName', 'ASC');
	
		return $qb	->getQuery()
		->getResult();
	}
	
	public function getGPSDevices($idsDevices)
	{
		$qb = $this	->createQueryBuilder('d');
		
		$qb ->where('d.latitude IS NOT NULL')
			->andWhere('d.longitude IS NOT NULL');
		
		if(!is_null($idsDevices))
		{
			$qb ->add('where', $qb->expr()->in('d.id', $idsDevices));
		}		
		
		return $qb	->getQuery()
					->getResult();
	}
	
	public function getDevicesNotInGroups()
	{
		$sql = "SELECT id, assigned_name
				FROM devices
				WHERE id NOT IN (
					SELECT ghe.entity_id
					FROM group_has_entity ghe
						INNER JOIN groups g ON ghe.group_id = g.group_id
					WHERE g.group_category = 4
						AND ghe.deletion_date IS NULL
				)
				ORDER BY assigned_name ASC
				";
		
		
		$rsm = new ResultSetMapping();
		$rsm->addEntityResult('iot6\InteractBundle\Entity\Devices', 'd');
		$rsm->addFieldResult('d', 'id', 'id');
		$rsm->addFieldResult('d', 'assigned_name', 'assignedName');

		$query = $this->_em->createNativeQuery($sql, $rsm);
		
		return $query->getResult();
	}
	
	public function getDevicesInGroup($idGroup)
	{
		$sql = "SELECT id, assigned_name
				FROM devices
				WHERE id IN (
					SELECT ghe.entity_id
					FROM group_has_entity ghe
					WHERE ghe.group_id = " . $idGroup . "
				)
				ORDER BY assigned_name ASC
				";
	
	
		$rsm = new ResultSetMapping();
		$rsm->addEntityResult('iot6\InteractBundle\Entity\Devices', 'd');
		$rsm->addFieldResult('d', 'id', 'id');
		$rsm->addFieldResult('d', 'assigned_name', 'assignedName');
	
		$query = $this->_em->createNativeQuery($sql, $rsm);
	
		return $query->getResult();
	}
}