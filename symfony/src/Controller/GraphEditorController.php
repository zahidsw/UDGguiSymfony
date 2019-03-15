<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
//use iot6\InteractBundle\VariablesHistoryRepository;

class GraphEditorController extends AbstractController
{
    public function index()
    {
        //return $this->render('iot6GraphBundle:Default:index.html.twig', array('name' => $name));
		return $this->render('graph/editor/index.html.twig');
    }

	public function getVariablesForDevice()
    {
		$device_id = $this->getRequest()->query->get('device_id');
		
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findByDevice($device_id);
		
		$json = array();
		$i=0;
		foreach ($listVariables as $variable) {
			$json[$i]["id"] = $variable->getId();
			$json[$i]["device_id"] = $device_id;
			$json[$i]["device_name"] = $variable->getDevice()->getName();
			$json[$i]["variable_name"] = utf8_encode($variable->getName());
			$json[$i]["unit"] = utf8_encode($variable->getUnit());
			$i++;
			//$json[$variable->getId()]["name"] = utf8_encode($variable->getName());
		}

		//$json[0][] = "test";

		return new Response(json_encode($json));
	}
	
	public function populateDummyVariableHistory()
    {
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		//$stmt = $em_upv6->getConnection()->prepare('INSERT into variables_history (id,devices_id,name, serialized_value,unit,created_at,string_value) values(:id,143,"energy",:serialized_value,"kwH",:createdAt,:stringValue)');
		$stmt = $em_upv6->getConnection()->prepare('INSERT into variables_history (devices_id,name, serialized_value,unit,created_at,string_value) values(143,"energy",:serialized_value,"kwH",:createdAt,:stringValue)');
		//$stmt = $em_upv6->getConnection()->prepare('INSERT into variables_history (devices_id,name, serialized_value,unit,created_at,string_value) values(194,"energy",:serialized_value,"kwH",:createdAt,:stringValue)');
		$stmt->bindValue('serialized_value','');
		
		$d = new \DateTime("2014-08-01 00:00:00");
		$di= new \DateInterval('PT1S');
		$i=0;
		$v = 50;
		$up = true;
		
		do {
			//$stmt->bindValue('id',$i);
			$stmt->bindValue('createdAt', $d->format('Y-m-d H:i:s'));
			$stmt->bindValue('stringValue',"$v");
			$stmt->execute();
			
			$d->add($di);
			++$i;
			
			if($up) {
				$v += 1;
				//$v += 10;
				if($v > 500)
					$up=false;
			} else {
				$v -= 1;
				//$v -= 10;
				if($v < 50)
					$up=true;
			}
		}
		//while($i<10); //test
		//while($i<86400)//1 day
		//while($i<(86400 * 3))//n days
		while($i<10000); //test
	}
}
