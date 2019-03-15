<?php

namespace App\Repository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Upv6\Alerts;

use Doctrine\ORM\EntityRepository;

class AlertsRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Alerts::class);
	}

	public function getAlerts($limit, $status, $from, $to)
	{
		$qb = $this	->createQueryBuilder('a')
					->setMaxResults($limit)
					->orderBy('a.createdAt', 'DESC');
		
		if($status == 0 || $status == 1 || $status == 2) {
			$qb = $this->addStatusFilter($qb, $status);
		}
		
		if($from != "") {
			$from = \DateTime::createFromFormat('d-m-Y H:i:s', $from);
			$qb = $this->addFromFilter($qb, $from);
		}
		
		if($to != "") {
			$to = \DateTime::createFromFormat('d-m-Y H:i:s', $to);
			$qb = $this->addToFilter($qb, $to);
		}
		
		return $qb	->getQuery()
					->getResult();
	}
	
	private function addStatusFilter($qb, $status)
	{
		$qb	->andWhere('a.status = :status')
			->setParameter('status', $status);
	
		return $qb;
	}
	
	private function addFromFilter($qb, $from)
	{
		$qb	->andWhere('a.createdAt >= :from')
			->setParameter('from', $from);
	
		return $qb;
	}
	
	private function addToFilter($qb, $to)
	{
		$qb	->andWhere('a.createdAt < :to')
			->setParameter('to', $to);
	
		return $qb;
	}
}