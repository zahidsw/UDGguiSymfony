<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ViewerController extends AbstractController
{
    public function index()
    {
        return $this->render('location/Viewer/index.html.twig');
    }
	
	public function viewer()
    {
		return $this->render('location/Viewer/viewer.html.twig');
    }
	
	public function list()
    {
		//get all floors
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		//$listBuildings = $em_upv6->getRepository('iot6InteractBundle:Buildings')->findBy(array(), array('name' => 'ASC'));
		$listFloors = $em_upv6->getRepository('App\Entity\Upv6\Floors')->findFloorsOrderByBuildingName();
		$listLocations = $em_upv6->getRepository('App\Entity\Upv6\Locations')->findBy(array(), array('name' => 'ASC'));
		
		//$data["buildings"] = $listBuildings;
		$data["floors"] = $listFloors;
		$data["locations"] = $listLocations;
				
		return $this->render('location/Viewer/list.html.twig', $data);
    }
	
	public function getLocations() {
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		$locations = $em_upv6->getRepository('App\Entity\Upv6\Locations')->findAll();
		
		$json = array();
				
		foreach($locations as $location) {
			$loc = array();
			
			$loc["id"] = $location->getId();
			$loc["name"] = $location->getName();
			//$loc["content"] = $location->getContent();
			
			array_push($json,$loc);
		}
		
		return new Response(json_encode($json));
	}
	
	public function getLocationContent(Request $request) {
		//get parameters
		$loc_id = $request->query->get('id');
	
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$locations = $em_upv6->getRepository('App\Entity\Upv6\Locations')->findById($loc_id);
		
		$json = '';
		
		if(sizeof($locations) > 0) {
			$json = array("content" => $locations[0]->getContent());
		} else {
			$json = array("content" => '');
		}
		
		return new Response(json_encode($json));
	}
	
	public function getDeviceParams(Request $request) {
		//get parameters
		$device_id = $request->query->get('device_id');
		
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$devices = $em_upv6->getRepository('App\Entity\Upv6\Devices')->findById($device_id);
		
		if(sizeof($devices) > 0) {
			$json = array();
			$json["name"] = utf8_encode($devices[0]->getAssignedName());
			$json["id"] = $devices[0]->getId();
			$json["ipv6"] = $devices[0]->getIpv6address();
			$json["protocol"] = utf8_encode($devices[0]->getProtocol()->getDescription());
			
			//get variables
			$vars = array();
		
			$variables =  $em_upv6->getRepository('App\Entity\Upv6\Variables')->findByDevice($device_id);
			$var_name;
			$var_value;
			$var_newest_timestamp=null;
			
			foreach ($variables as $variable) {
				$var_name = utf8_encode($variable->getName());
				$var_value = "-";
				
				//get last value of variable
				$var_value_list =  $em_upv6->getRepository('App\Entity\Upv6\VariablesHistory')->findLatestValue($device_id, $variable->getName());
				if(sizeof($var_value_list) > 0) {
					$var_value = $var_value_list[0]->getStringValue() . " " . $var_value_list[0]->getUnit();
					
					if($var_newest_timestamp == null || $var_value_list[0]->getCreatedAt() < $var_newest_timestamp == null)
						$var_newest_timestamp = $var_value_list[0]->getCreatedAt();
				}
				
				$vars["$var_name"] = $var_value;
			}
			
			//sort the array to have it ordered by the datetime (= key)
			ksort($vars);
			
			$json["variables"] = $vars;
			
			$json["timestamp"] = $var_newest_timestamp == null ? "" : $var_newest_timestamp->format('Y-m-d H:i:s');
		} else {
			$json = array("id" => '');
		}
		
		return new Response(json_encode($json));
	}
	
	//take an image in parameter and return the image resized and in base64
	/*public function getImageEncodedAction() {
		$files = $this->getRequest()->files;
		
		//do we have 1 file at least ?
		if(sizeof($files) < 1) {
			//return an error - image not ok
			$json = array();
			$json["error"] = 1;
			return new Response(json_encode($json));
		}
		
		//resize image
		
		
		//encode image content in base64
		$path= 'myfolder/myimage.png';
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		
		$json = array();
		$json["error"] = 0;
		$json["imageB64"] = $base64;
		return new Response(json_encode($json));
	}*/
}
