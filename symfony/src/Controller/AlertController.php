<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Upv6\Alerts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AlertController extends AbstractController
{
	public function alert()
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		 
		$data["nbRecords"] = $this->getNbRecords();
		 
		return $this->render('alert/alerts.html.twig', $data);
	}
	
	public function getResult(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		 
		$nbRecords = $request->query->get('nbRecords');
		$status = $request->query->get('status');
		$from = $request->query->get('from');
		$to = $request->query->get('to');
	
		if($nbRecords != "") {
			if($nbRecords > 0 && $nbRecords < 300 && is_numeric($nbRecords)) {
				$limit = $nbRecords;
			}
			else {
				$limit = $this->getNbRecords();
			}
		}
		else {
			$limit = $this->getNbRecords();
		}
		
		$alerts = $em_upv6->getRepository('App\Entity\Upv6\Alerts')->getAlerts($limit, $status, $from, $to);
	
		$data['alerts'] = $alerts;
		 
		return $this->render('alert/getResult.html.twig', $data);
    }
	
    private function getNbRecords()
	{
	    $em_gui = $this->getDoctrine()->getManager("gui");
	     
	    $user = $this->container->get('security.token_storage')->getToken()->getUser();
	    return $em_gui	->getRepository('App\Entity\Gui\ConfigParam')
	    				->getParamValue('alertsNbRecords', $user, $em_gui);
	}
	
    public function alertShowEditAction(Request $request,Alerts $alert)
    {

    	if ($request->getMethod() == 'POST')
    	{
    		if($request->get('edit'))
    		{
    			$newStatus = $request->get('status');
    			$alert->setStatus($newStatus);
    			
    			$em_upv6 = $this->getDoctrine()->getManager("upv6");
    			$em_upv6->persist($alert);
    			$em_upv6->flush();
    			
    			$message = $this->get('translator')->trans('alert.edited');
    			$this->get('session')->getFlashBag()->add('ok', $message);
    	
    			return $this->redirect($this->generateUrl('iot6_AlertBundle'));
    		}
    	}
    	
    	$data["alert"] = $alert;
    	
    	return $this->render('iot6AlertBundle:Alert:alertShow.html.twig', $data);
    }
	
}
