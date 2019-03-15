<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Controller\Data;
use Symfony\Component\HttpFoundation\Request;


class DataController extends AbstractController
{
    public function data()
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	
    	$data["nbRecords"] = $this->getNbRecords();
    	
        return $this->render('data/data.html.twig', $data);
    }
    
    public function getResult(Request $request)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	
		
		$nbRecords = $request->query->get('nbRecords');
    	$actionEvent = $request->query->get('actionEvent');
    	$from = $request->query->get('from');
    	$to = $request->query->get('to');
    	
    	$idBuilding = $request->query->get('buildings');
    	$idFloor = $request->query->get('floors');
    	$idRoomType = $request->query->get('roomTypes');
    	$idRoom = $request->query->get('rooms');
    	
    	$idCategory = $request->query->get('categories');
    	$idFamily = $request->query->get('families');
    	$idDevice = $request->query->get('devices');
    	
    	$sql = '
					SELECT * FROM 
    				(
							SELECT al.id, creation_date, device_id, IFNULL(assigned_name, physical_code) AS displayed_name, host_id, action_id AS action_message, 1 AS is_action, b.id AS building_id, b.name AS building_name, f.id AS floor_id, f.name AS floor_name, r.id AS room_id, r.name AS room_name, rt.id AS room_type_id, rt.icon_name AS room_type_icon, rt.name AS room_type_name, fa.id AS family_id, c.id AS category_id, d.latitude, d.longitude
								FROM actions_log AS al LEFT OUTER JOIN devices AS d
									ON al.device_id = d.id LEFT OUTER JOIN rooms AS r
									ON d.room_id = r.id LEFT OUTER JOIN floors AS f
									ON r.floor_id = f.id LEFT OUTER JOIN buildings AS b
									ON f.building_id = b.id LEFT OUTER JOIN room_types AS rt
	    							ON r.room_type_id = rt.id LEFT OUTER JOIN families AS fa
	    							ON d.family_id = fa.id LEFT OUTER JOIN categories AS c
	    							ON d.category_id = c.id
						UNION ALL
							SELECT sl.id, created_at, device_id, IFNULL(assigned_name, physical_code), host_id, short_message, 0, b.id, b.name, f.id, f.name, r.id, r.name, rt.id, rt.icon_name, rt.name, fa.id, c.id, d.latitude, d.longitude
								FROM system_logs AS sl LEFT OUTER JOIN devices AS d
									ON sl.device_id = d.id LEFT OUTER JOIN rooms AS r
									ON d.room_id = r.id LEFT OUTER JOIN floors AS f
									ON r.floor_id = f.id LEFT OUTER JOIN buildings AS b
									ON f.building_id = b.id LEFT OUTER JOIN room_types AS rt
	    							ON r.room_type_id = rt.id LEFT OUTER JOIN families AS fa
	    							ON d.family_id = fa.id LEFT OUTER JOIN categories AS c
	    							ON d.category_id = c.id
    				) as TUnion
    					WHERE 1=1 ';
    					
    	if($actionEvent == 0 || $actionEvent == 1) {
    		$sql .= "AND is_action = " . $actionEvent . " ";
    	}
    	
    	if($from != "") {
    		$dateFrom = \DateTime::createFromFormat('d-m-Y H:i:s', $from)->format('Y-m-d H:i:s');
    		$sql .= "AND creation_date >= '" . $dateFrom . "' ";
    	}
    	
    	if($to != "") {
    		$dateTo = \DateTime::createFromFormat('d-m-Y H:i:s', $to)->format('Y-m-d H:i:s');
    		$sql .= "AND creation_date < '" . $dateTo . "' ";
    	}
    	
    	if($idBuilding != -1) {
    		$sql .= "AND building_id = '" . $idBuilding . "' ";
    	}
    	
    	if($idFloor != -1) {
    		$sql .= "AND floor_id = '" . $idFloor . "' ";
    	}
    	
    	if($idRoomType != -1) {
    		$sql .= "AND room_type_id = '" . $idRoomType . "' ";
    	}
    	
    	if($idRoom != -1) {
    		$sql .= "AND room_id = '" . $idRoom . "' ";
    	}
    	
    	if($idCategory != -1) {
    		$sql .= "AND category_id = '" . $idCategory . "' ";
    	}
    	
    	if($idFamily != -1) {
    		$sql .= "AND family_id = '" . $idFamily . "' ";
    	}
    	
    	if($idDevice != -1) {
    		$sql .= "AND device_id = '" . $idDevice . "' ";
    	}

		$sql .=	'ORDER BY creation_date DESC ';
    	
		
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
		
		$sql .= 'LIMIT 0, ' . $limit;
    	
		
    	$stmt = $em_upv6->getConnection()
    					->prepare($sql);
    	$stmt->execute();
    	$result = $stmt->fetchAll();
    	
    	$dataList = array();
    	
    	for($i=0; $i<count($result); $i++)
    	{
    		$datas = new Data();
    		$datas->setId($result[$i]['id']);
    		$datas->setCreationDate($result[$i]['creation_date']);
			$datas->setDeviceID($result[$i]['device_id']);
			$datas->setDisplayedName($result[$i]['displayed_name']);
			$datas->setHostId($result[$i]['host_id']);
			$datas->setActionMessage($result[$i]['action_message']);
			$datas->setIsAction($result[$i]['is_action']);
			$datas->setBuildingName($result[$i]['building_name']);
			$datas->setFloorName($result[$i]['floor_name']);
			$datas->setRoomName($result[$i]['room_name']);
			$datas->setRoomId($result[$i]['room_id']);
			$datas->setRoomTypeName($result[$i]['room_type_name']);
			$datas->setRoomTypeIcon($result[$i]['room_type_icon']);
			$datas->setLatitude($result[$i]['latitude']);
			$datas->setLongitude($result[$i]['longitude']);
			array_push($dataList, $datas);
    	}
    	 
    	$data['dataList'] = $dataList;
    	
    	return $this->render('data/getResult.html.twig', $data);
    }
    
    private function getNbRecords()
    {
    	$em_gui = $this->getDoctrine()->getManager("gui");
    	
    	$user = $this->container->get('security.token_storage')->getToken()->getUser();
    	return $em_gui	->getRepository('App\Entity\Gui\ConfigParam')
    					->getParamValue('dataNbRecords', $user,$em_gui);
    }
}
