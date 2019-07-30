<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MapController extends AbstractController
{
    public function loadMap($devices)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	
		$user = $this->container->get('security.token_storage')->getToken()->getUser();
        $user_id = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
        $em_upv6 = $this->getDoctrine()->getManager("upv6");
        $user_has_device = $em_upv6->getRepository('App\Entity\Upv6\UserHasDevice')
            ->findBy(array('userId' => $user_id ));
			
        $devices_list = [];
        foreach($user_has_device as $device)
        {
            array_push($devices_list, $device->getDeviceId());
        }
		$devices = $em_upv6->getRepository('App\Entity\Upv6\Devices')->findBy(['id' => $devices_list]);
		
        $data['devices'] = $devices;
		
    	return $this->render('map/map1.html.twig', $data);
    }
}
