<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MapController extends AbstractController
{
    public function loadMap($devices)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	
    	$devices = $em_upv6	->getRepository('App\Entity\Upv6\Devices')->getGPSDevices($devices);
    	
    	$data["devices"] = $devices;
    	
    	return $this->render('map/map1.html.twig', $data);
    }
}
