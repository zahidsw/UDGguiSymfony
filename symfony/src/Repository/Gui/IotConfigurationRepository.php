<?php

namespace App\Repository\Gui;

use App\Entity\Gui\IotConfiguration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use App\Form\SlicemanagerType;

use App\Repository\Gui\SlicemanagerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Process\Process;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



/**
 * @method IotConfiguration|null find($id, $lockMode = null, $lockVersion = null)
 * @method IotConfiguration|null findOneBy(array $criteria, array $orderBy = null)
 * @method IotConfiguration[]    findAll()
 * @method IotConfiguration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IotConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IotConfiguration::class);
    }

    // /**
    //  * @return IotConfiguration[] Returns an array of IotConfiguration objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IotConfiguration
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
