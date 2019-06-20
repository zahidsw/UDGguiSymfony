<?php

namespace App\Repository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Upv6\Buildings;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BuildingsRepository extends EntityRepository
{
    public function getBuildings($nbBuildings, $currentPage)
    {
        if ($currentPage < 1) {
            throw new \InvalidArgumentException('L\'argument $page ne peut �tre inf�rieur � 1 (valeur : "'.$currentPage.'").');
        }

        $query = $this	->createQueryBuilder('b')
            ->orderBy('b.name', 'ASC')
            ->getQuery();

        $query	->setFirstResult(($currentPage-1) * $nbBuildings)
            ->setMaxResults($nbBuildings);

        return new Paginator($query);
    }

    public function getBuildingsNotInGroups()
    {
        $sql = "SELECT id, name
				FROM buildings
				WHERE id NOT IN (
					SELECT ghe.entity_id
					FROM group_has_entity ghe
						INNER JOIN groups g ON ghe.group_id = g.group_id
					WHERE g.group_category = 1
						AND ghe.deletion_date IS NULL
				)
				ORDER BY name ASC
				";


        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('App\Entity\Upv6\Buildings', 'b');
        $rsm->addFieldResult('b', 'id', 'id');
        $rsm->addFieldResult('b', 'name', 'name');

        $query = $this->_em->createNativeQuery($sql, $rsm);

        return $query->getResult();
    }

    public function getBuildingsInGroup($idGroup)
    {
        $sql = "SELECT id, name
				FROM buildings
				WHERE id IN (
					SELECT ghe.entity_id
					FROM group_has_entity ghe
					WHERE ghe.group_id = " . $idGroup . "
				)
				ORDER BY name ASC
				";


        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('App\Entity\Upv6\Buildings', 'b');
        $rsm->addFieldResult('b', 'id', 'id');
        $rsm->addFieldResult('b', 'name', 'name');

        $query = $this->_em->createNativeQuery($sql, $rsm);

        return $query->getResult();
    }
}