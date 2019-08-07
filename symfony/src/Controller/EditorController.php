<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Upv6\Locations;

class EditorController extends AbstractController
{
    public function index()
    {
		//get the list of families, to build the array of families icon image name
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$families_icon = $em_upv6->getRepository('App\Entity\Upv6\Families')->findAll();
		$data["families_icon"] = $families_icon;
		
        return $this->render('location/Editor/index.html.twig', $data);
    }
	
	public function renameLocation(Request $request)
    {
        //get parameters
		$loc_id = $request->query->get('id');
		$loc_name = $request->query->get('name','');
	
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$locations = $em_upv6->getRepository('App\Entity\Upv6\Locations')->findById($loc_id);
		
		if(sizeof($locations) > 0 && $loc_name !== "") {
			$locations[0]->setName($loc_name);
			$em_upv6->flush();
		}
		
		return new Response(json_encode(''));
    }
	
	public function saveLocation(Request $request)
    {
        //get parameters
		$loc_id = $request->query->get('id');
		$loc_content = $request->request->get('content','');
	
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$locations = $em_upv6->getRepository('App\Entity\Upv6\Locations')->findById($loc_id);

		if(sizeof($locations) > 0 && $loc_content !== "") {
			$locations[0]->setContent($loc_content);
			$em_upv6->flush();
		}
		
		return new Response(json_encode(''));
    }
	
	public function saveAsLocation(Request $request)
    {
        //get parameters
		$loc_id = $request->query->get('id');
		$loc_name = $request->query->get('name');
		$loc_content = $request->request->get('content','');
	
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		if($loc_name !== '' && $loc_content !== "") {
			$location = new Locations();
			$location->setName($loc_name);
			$location->setContent($loc_content);
			$em_upv6->persist($location);
			$em_upv6->flush();
		}
		
		$json = array();
		$json["new_id"] = $location->getId();
		
		return new Response(json_encode($json));
    }
	
	public function deleteLocation(Request $request)
    {
        //get parameters
		$loc_id = $request->query->get('id');
	
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$locations = $em_upv6->getRepository('App\Entity\Upv6\Locations')->findById($loc_id);
		
		if(sizeof($locations) > 0) {
			$em_upv6->remove($locations[0]);
			$em_upv6->flush();
		}
		
		return new Response(json_encode(''));
    }

	public function affectLocation(Request $request)
    {
        //get parameters
		$location_id = $request->query->get('location_id');
		$floor_id = $request->query->get('floor_id');
	
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$floors = $em_upv6->getRepository('App\Entity\Upv6\Floors')->findById($floor_id);
		
		if(sizeof($floors) > 0) {
			$locations = $em_upv6->getRepository('App\Entity\Upv6\Locations')->findById($location_id);
			
			if(sizeof($locations) > 0) {
				$floors[0]->setLocation($locations[0]);
				$em_upv6->flush();
			}
		}
		
		return new Response(json_encode(''));
    }
	
	public function unlinkLocation(Request $request)
    {
        //get parameters
		$floor_id = $request->query->get('floor_id');
	
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$floors = $em_upv6->getRepository('App\Entity\Upv6\Floors')->findById($floor_id);
		
		if(sizeof($floors) > 0) {
			$floors[0]->setLocation(null);
			$em_upv6->flush();
		}
		
		return new Response(json_encode(''));
    }
}
