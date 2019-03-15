<?php

namespace App\Repository;

use App\Entity\Gui\ConfigParam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


class ConfigParamRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ConfigParam::class);
	}
	
	public function getParamValue($strParam, $user, $em_gui)
	{
		$configParam = $em_gui	->getRepository('App\Entity\Gui\configParam')
								->findOneBy(array('param' => $strParam));
		 
		$userConfigParam = $em_gui	->getRepository('App\Entity\Gui\UserConfigParam')
									->findOneBy(array('user' => $user, 'configParam' => $configParam));
		
		if(is_null($userConfigParam)) {
			return $configParam->getDefaultValue();
		}
		else {
			return $userConfigParam->getValue();
		}
	}
}