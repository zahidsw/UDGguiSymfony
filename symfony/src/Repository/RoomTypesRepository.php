<?php

namespace App\Repository;

use App\Entity\Upv6\RoomTypes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class RoomTypesRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RoomTypes::class);
	}

	public function findRoomTypesForBuilding($idBuilding)
	{
		$qb = $this->createQueryBuilder('rt');
	
		$qb	->select('rt')
			->leftJoin('rt.rooms', 'r')
			->leftJoin('r.floor', 'f')
			->leftJoin('f.building', 'b')
			->where('b.id = :buildingId')
			->setParameter('buildingId', $idBuilding)
			->orderBy('rt.name', 'ASC');
	
		return $qb	->getQuery()
					->getResult();
	}
	
	public function findRoomTypesForFloor($idFloor, $idBuilding)
	{
		$qb	= $this->createQueryBuilder('rt')
					->leftJoin('rt.rooms', 'r')
					->leftJoin('r.floor', 'f')
					->leftJoin('f.building', 'b')
					->orderBy('rt.name', 'ASC');
	
		if($idFloor != -1) {
			$qb	->andWhere('f.id = :idFloor')
				->setParameter('idFloor', $idFloor);
		}
		
		if($idBuilding != -1) {
			$qb	->andWhere('b.id = :idBuilding')
				->setParameter('idBuilding', $idBuilding);
		}
		
		return $qb	->getQuery()
					->getResult();
	}
	
	/* ??????
	public function findRoomTypeForFloor($floor)
	{
		$qb = $this->createQueryBuilder(rt);
		
		$qb	->select('rt')
			->from('iot6InteractBundle:RoomTypes', 'rt')
			->where('rt.floor = :floor')
			->setParameter('floor', $floor)
			->orderBy('rt.name', 'ASC');
		
		return $qb	->getQuery()
					->getResult();
	}
	*/

	public function getRoomTypes($nbRoomTypesProPage, $currentPage)
	{
		if ($currentPage < 1) {
			throw new \InvalidArgumentException('L\'argument $page ne peut �tre inf�rieur � 1 (valeur : "'.$currentPage.'").');
		}
			
		$query = $this	->createQueryBuilder('rt')
						->orderBy('rt.name', 'ASC')
						->getQuery();
			
		$query	->setFirstResult(($currentPage-1) * $nbRoomTypesProPage)
				->setMaxResults($nbRoomTypesProPage);
			
		return new Paginator($query);
	}
}