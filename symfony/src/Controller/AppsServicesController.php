<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppsServicesController extends AbstractController
{
    public function appServices()
    {
    	$em_gui = $this->getDoctrine()->getManager("gui");
    	$apps = $em_gui->getRepository('App\Entity\Gui\Apps')->findBy(array(), array('name' => 'asc'));
    	
    	$data["apps"] = $apps;
    	
        return $this->render('appsServices/appsServices.html.twig', $data);
    }
}
