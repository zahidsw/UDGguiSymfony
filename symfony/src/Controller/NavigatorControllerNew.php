<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\DataCollectorTranslator;


class NavigatorControllerNew extends AbstractController
{
	private $translator;
	

    public function __construct(DataCollectorTranslator $translator)
    {
		$this->translator = $translator;
	}

	public function loadNavigator($displayDevices, $prefix='', $object=null, $displayObjects=true,$displayVariables=false,Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		$listBuildings = $em_upv6->getRepository('App\Entity\Upv6\Buildings')->findBy(array(), array('name' => 'ASC'));
		$listFloors = $em_upv6->getRepository('App\Entity\Upv6\Floors')->findBy(array(), array('name' => 'ASC'));
		$listRoomTypes = $em_upv6->getRepository('App\Entity\Upv6\RoomTypes')->findBy(array(), array('name' => 'ASC'));
		$listRooms = $em_upv6->getRepository('App\Entity\Upv6\Rooms')->findBy(array(), array('name' => 'ASC'));
		$listDevices = $em_upv6->getRepository('App\Entity\Upv6\Devices')->findBy(array(), array('assignedName' => 'ASC'));
		$listCategories = $em_upv6->getRepository('App\Entity\Upv6\Categories')->findBy(array(), array('internalName' => 'ASC'));
		$listFamilies = $em_upv6->getRepository('App\Entity\Upv6\Families')->findBy(array(), array('internalName' => 'ASC'));
		if($displayVariables) $listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesUniqueNames();
		
		if(!is_null($object)) {
		
			$building = $object->getBuilding();
			$floor = $object->getFloor();
			$roomType = $object->getRoomType();
			$room = $object->getRoom();
			$category = $object->getCategory();
			$family = $object->getFamily();
			$device= $object->getDevice();
			
			$idBuilding = (!is_null($building)) ? $building->getId() : -1;
			$idFloor 	= (!is_null($floor)) 	? $floor->getId() : -1;
			$idRoomType = (!is_null($roomType)) ? $roomType->getId() : -1;
			$idRoom 	= (!is_null($room)) 	? $room->getId() : -1;
			$idCategory = (!is_null($category)) ? $category->getId() : -1;
			$idFamily 	= (!is_null($family)) 	? $family->getId() : -1;
			$idDevice 	= (!is_null($device)) 	? $device->getId() : -1;
			
			if(!is_null($building)) {
				$listFloors = $em_upv6->getRepository('App\Entity\Upv6\Floors')->findFloorsForBuilding($idBuilding);
				$listRoomTypes = $em_upv6->getRepository('App\Entity\Upv6\RoomTypes')->findRoomTypesForBuilding($idBuilding);
				$listRooms = $em_upv6->getRepository('App\Entity\Upv6\Rooms')->findRoomsForBuilding($idBuilding);
				$listCategories = $em_upv6->getRepository('App\Entity\Upv6\Categories')->findCategoriesForBuilding($idBuilding);
				$listFamilies = $em_upv6->getRepository('App\Entity\Upv6\Families')->findFamiliesForBuilding($idBuilding);
				$listDevices = $em_upv6->getRepository('App\Entity\Upv6\Devices')->findDevicesForBuilding($idBuilding);
                if($displayVariables) $listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesForBuilding($idBuilding);


			}
			
			if(!is_null($roomType)) {
				$listRooms = $em_upv6->getRepository('App\Entity\Upv6\Rooms')->findRoomsForRoomType($idRoomType, $idBuilding, $idFloor);
				$listCategories = $em_upv6->getRepository('App\Entity\Upv6\Categories')->findCategoriesForRoomType($idRoomType, $idBuilding, $idFloor);
				$listFamilies = $em_upv6->getRepository('App\Entity\Upv6\Families')->findFamiliesForRoomType($idRoomType, $idBuilding, $idFloor);
				$listDevices = $em_upv6->getRepository('App\Entity\Upv6\Devices')->findDevicesForRoomType($idRoomType, $idBuilding, $idFloor);
				if($displayVariables) $listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesForRoomType($idRoomType, $idBuilding, $idFloor);


			}

			if(!is_null($floor)) {
				$listRoomTypes = $em_upv6->getRepository('App\Entity\Upv6\RoomTypes')->findRoomTypesForFloor($idFloor, $idBuilding);
				$listRooms = $em_upv6->getRepository('App\Entity\Upv6\Rooms')->findRoomsForFloor($idFloor, $idBuilding);
				$listCategories = $em_upv6->getRepository('App\Entity\Upv6\Categories')->findCategoriesForFloor($idFloor, $idBuilding);
				$listFamilies = $em_upv6->getRepository('App\Entity\Upv6\Families')->findFamiliesForFloor($idFloor, $idBuilding);
				$listDevices = $em_upv6->getRepository('App\Entity\Upv6\Devices')->findDevicesForFloor($idFloor, $idBuilding);
				if($displayVariables) $listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesForFloor($idFloor, $idBuilding);


			}
			
			if(!is_null($room)) {
				$listCategories = $em_upv6->getRepository('App\Entity\Upv6\Categories')->findCategoriesForRoom($idRoom, $idRoomType, $idFloor, $idBuilding);
				$listFamilies = $em_upv6->getRepository('App\Entity\Upv6\Families')->findFamiliesForRoom($idRoom, $idRoomType, $idFloor, $idBuilding);
				$listDevices = $em_upv6->getRepository('App\Entity\Upv6\Devices')->findDevicesForRoom($idRoom, $idRoomType, $idFloor, $idBuilding);
				if($displayVariables) $listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesForRoom($idRoom, $idRoomType, $idFloor, $idBuilding);


			}
			
			if(!is_null($category)) {
				$listFamilies = $em_upv6->getRepository('App\Entity\Upv6\Families')->findFamiliesForCategory($idCategory, $idRoom, $idRoomType, $idFloor, $idBuilding);
				$listDevices = $em_upv6->getRepository('App\Entity\Upv6\Devices')->findDevicesForCategory($idCategory, $idRoom, $idRoomType, $idFloor, $idBuilding);
				if($displayVariables) $listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesForCategory($idCategory, $idRoom, $idRoomType, $idFloor, $idBuilding);


			}
			
			if(!is_null($family)) {
				$listDevices = $em_upv6->getRepository('App\Entity\Upv6\Devices')->findDevicesForFamily($idFamily, $idCategory, $idRoom, $idRoomType, $idFloor, $idBuilding);
                if($displayVariables) $listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesForFamily($idFamily, $idCategory, $idRoom, $idRoomType, $idFloor, $idBuilding);

			}

			

			if(!is_null($device)) {

				if($displayVariables) $listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesForDevice($idDevice);
			}
		}
		
		$data["prefix"] = $prefix;
		$data["displayDevices"] = ($displayDevices == 1) ? true : false;
		$data["displayObjects"] = $displayObjects;
		$data["object"] = $object;
		if($displayVariables) $data["navigator_display_variables"] = true;
	
		$data["buildings"] = $listBuildings;
		$data["floors"] = $listFloors;
		$data["roomTypes"] = $listRoomTypes;
		$data["rooms"] = $listRooms;
		$data["categories"] = $listCategories;
		$data["families"] = $listFamilies;
		//$data["devices"] = $listDevices;

		// User session
		// $request = $this->getRequest();
		$session = $request->getSession();
		$session_id = $session->getId();

		// The user himself
		$user_id = $em_upv6->getRepository('App\Entity\Upv6\UsersMiddleware')->findOneBy(array('sessionId' => $session_id));

		// Filter the device in function of the user
		$listUserDevices = array();
		foreach($listDevices as $device)
		{
			// Check the link between the device and the user
			$user_has_device = $em_upv6->getRepository('App\Entity\Upv6\UserHasDevice')
							->findOneBy(array('userId' => $user_id, 'deviceId' => $device->getId()));
			if($user_has_device == true)
			{	
				$listUserDevices[] = $device;
			}
		}
		$data["devices"] = $listUserDevices;
		

		if($displayVariables) $data["variables"] = $listVariables;
		
		return $this->render('navigator\navigator.html.twig', $data);
	}

	// Buildings
	public function getFloorsForBuilding(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idBuilding = $request->get('idBuilding');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		if($idBuilding != -1)
		{
			$listFloors = $em_upv6	->getRepository('App\Entity\Upv6\Floors')
									->findFloorsForBuilding($idBuilding);
		}
		else
		{
			$listFloors = $em_upv6->getRepository('App\Entity\Upv6\Floors')->findAll();
		}
	
		
	
		foreach ($listFloors as $floor) {
			$json[$floor->getId()][] = utf8_encode($floor->getName());
		}
	
		return new Response(json_encode($json));
	}
	
	public function getRoomTypesForBuilding(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	
		$idBuilding = $request->query->get('idBuilding');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		if($idBuilding != -1)
		{
			$listRoomTypes = $em_upv6	->getRepository('App\Entity\Upv6\RoomTypes')
										->findRoomTypesForBuilding($idBuilding);
		}
		else
		{
			$listRoomTypes = $em_upv6->getRepository('App\Entity\Upv6\RoomTypes')->findAll();
		}
		
		foreach ($listRoomTypes as $roomType) {
			$json[$roomType->getId()][] = utf8_encode($roomType->getName());
		}
	
		return new Response(json_encode($json));
	}
	
	public function getRoomsForBuilding(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	
		
		$idBuilding = $request->query->get('idBuilding');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		if($idBuilding != -1)
		{
			$listRooms = $em_upv6	->getRepository('App\Entity\Upv6\Rooms')
									->findRoomsForBuilding($idBuilding);
		}
		else
		{
			$listRooms = $em_upv6->getRepository('App\Entity\Upv6\Rooms')->findAll();
		}
		
		foreach ($listRooms as $room) {
			$json[$room->getId()][] = utf8_encode($room->getName());
		}
	
		return new Response(json_encode($json));
	}
	
	public function getCategoriesForBuilding(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idBuilding = $request->get('idBuilding');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		if($idBuilding != -1) {
			$categories = $em_upv6	->getRepository('App\Entity\Upv6\Categories')
									->findCategoriesForBuilding($idBuilding);
		}
		else {
			$categories = $em_upv6->getRepository('App\Entity\Upv6\Categories')->findAll();
		}
	
		foreach ($categories as $category) {
			$json[$category->getId()][] = utf8_encode($category->getInternalName());
		}
	
		return new Response(json_encode($json));
	}
	
	public function getFamiliesForBuilding(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idBuilding = $request->get('idBuilding');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		if($idBuilding != -1) {
			$families = $em_upv6->getRepository('App\Entity\Upv6\Families')
								->findFamiliesForBuilding($idBuilding);
		}
		else {
			$families = $em_upv6->getRepository('App\Entity\Upv6\Families')->findAll();
		}
		
		foreach ($families as $family) {
			$json[$family->getId()][] = utf8_encode($family->getInternalName());
		}
	
		return new Response(json_encode($json));
	}
	
	public function getDevicesForBuilding(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idBuilding = $request->get('idBuilding');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		if($idBuilding != -1) {
			$devices = $em_upv6	->getRepository('App\Entity\Upv6\Devices')
								->findDevicesForBuilding($idBuilding);
		}
		else {
			$devices = $em_upv6->getRepository('App\Entity\Upv6\Devices')->findAll();
		}

		// User session
		$session = $request->getSession();
		$session_id = $session->getId();

		// The user himself
		$user_id = $em_upv6->getRepository('App\Entity\Upv6\UsersMiddleware')->findOneBy(array('sessionId' => $session_id));
	
		foreach ($devices as $device) {
			// Check the link between the device and the user
			$user_has_device = $em_upv6->getRepository('App\Entity\Upv6\UserHasDevice')
							->findOneBy(array('userId' => $user_id, 'deviceId' => $device->getId()));
			if($user_has_device == true)
			{
				//$json[$device->getId()] = [utf8_encode($device->getAssignedName()),($device->getFamily() ? $device->getFamily()->getId() : '')];
				$json[$device->getId()] = [utf8_encode($device->getAssignedName())];
			}
		}
	
		return new Response(json_encode($json));
	}
	
	// Floors
	public function getRoomTypesForFloor(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idFloor = $request->get('idFloor');
		$idBuilding = $request->get('idBuilding');
	
		$listRoomTypes = $em_upv6	->getRepository('App\Entity\Upv6\RoomTypes')
									->findRoomTypesForFloor($idFloor, $idBuilding);
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		foreach ($listRoomTypes as $roomType) {
			$json[$roomType->getId()][] = utf8_encode($roomType->getName());
		}
	
		return new Response(json_encode($json));
	}
	
	public function getRoomsForFloor(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idFloor = $request->get('idFloor');
		$idBuilding = $request->get('idBuilding');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		$listRooms = $em_upv6	->getRepository('App\Entity\Upv6\Rooms')
								->findRoomsForFloor($idFloor, $idBuilding);
	
		foreach ($listRooms as $room) {
			$json[$room->getId()][] = utf8_encode($room->getName());
		}
	
		return new Response(json_encode($json));
	}
	
	public function getCategoriesForFloor(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idFloor = $request->get('idFloor');
		$idBuilding = $request->get('idBuilding');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		$categories = $em_upv6	->getRepository('App\Entity\Upv6\Categories')
								->findCategoriesForFloor($idFloor, $idBuilding);
	
		foreach ($categories as $category) {
			$json[$category->getId()][] = utf8_encode($category->getInternalName());
		}
	
		return new Response(json_encode($json));
	}
	
	public function getFamiliesForFloor(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idFloor = $request->get('idFloor');
		$idBuilding = $request->get('idBuilding');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		$families = $em_upv6->getRepository('App\Entity\Upv6\Families')
							->findFamiliesForFloor($idFloor, $idBuilding);
	
		foreach ($families as $family) {
			$json[$family->getId()][] = utf8_encode($family->getInternalName());
		}
	
		return new Response(json_encode($json));
	}
	
	public function getDevicesForFloor(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idFloor = $request->get('idFloor');
		$idBuilding = $request->get('idBuilding');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		$devices = $em_upv6	->getRepository('App\Entity\Upv6\Devices')
							->findDevicesForFloor($idFloor, $idBuilding);
		// User session
		$session = $request->getSession();
		$session_id = $session->getId();

		// The user himself
		$user_id = $em_upv6->getRepository('App\Entity\Upv6\UsersMiddleware')->findOneBy(array('sessionId' => $session_id));
	
		foreach ($devices as $device) {
			// Check the link between the device and the user
			$user_has_device = $em_upv6->getRepository('App\Entity\Upv6\UserHasDevice')
							->findOneBy(array('userId' => $user_id, 'deviceId' => $device->getId()));
			if($user_has_device == true)
			{
				//$json[$device->getId()] = [utf8_encode($device->getAssignedName()),($device->getFamily() ? $device->getFamily()->getId() : '')];
				$json[$device->getId()] = [utf8_encode($device->getAssignedName())];
			}
		}
	
		return new Response(json_encode($json));
	}
	
	// Room Type
	public function getRoomsForRoomType(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idRoomType = $request->get('idRoomType');
		$idBuilding = $request->get('idBuilding');
		$idFloor = $request->get('idFloor');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		$listRooms = $em_upv6	->getRepository('App\Entity\Upv6\Rooms')
								->findRoomsForRoomType($idRoomType, $idBuilding, $idFloor);
	
		foreach ($listRooms as $room) {
			$json[$room->getId()][] = utf8_encode($room->getName());
		}
	
		return new Response(json_encode($json));
	}
	
	public function getCategoriesForRoomType(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idRoomType = $request->get('idRoomType');
		$idBuilding = $request->get('idBuilding');
		$idFloor = $request->get('idFloor');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		$categories = $em_upv6	->getRepository('App\Entity\Upv6\Categories')
								->findCategoriesForRoomType($idRoomType, $idBuilding, $idFloor);
	
		foreach ($categories as $category) {
			$json[$category->getId()][] = utf8_encode($category->getInternalName());
		}
	
		return new Response(json_encode($json));
	}
	
	public function getFamiliesForRoomType(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idRoomType = $request->get('idRoomType');
		$idBuilding = $request->get('idBuilding');
		$idFloor = $request->get('idFloor');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		$families = $em_upv6	->getRepository('App\Entity\Upv6\Families')
								->findFamiliesForRoomType($idRoomType, $idBuilding, $idFloor);
	
		foreach ($families as $family) {
			$json[$family->getId()][] = utf8_encode($family->getInternalName());
		}
	
		return new Response(json_encode($json));
	}
	
	public function getDevicesForRoomType(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idRoomType = $request->get('idRoomType');
		$idBuilding = $request->get('idBuilding');
		$idFloor = $request->get('idFloor');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		$devices = $em_upv6	->getRepository('App\Entity\Upv6\Devices')
							->findDevicesForRoomType($idRoomType, $idBuilding, $idFloor);
		// User session
		$session = $request->getSession();
		$session_id = $session->getId();

		// The user himself
		$user_id = $em_upv6->getRepository('App\Entity\Upv6\UsersMiddleware')->findOneBy(array('sessionId' => $session_id));
		
		foreach ($devices as $device) {
			// Check the link between the device and the user
			$user_has_device = $em_upv6->getRepository('App\Entity\Upv6\UserHasDevice')
							->findOneBy(array('userId' => $user_id, 'deviceId' => $device->getId()));
			if($user_has_device == true)
			{
				//$json[$device->getId()] = [utf8_encode($device->getAssignedName()),($device->getFamily() ? $device->getFamily()->getId() : '')];
				$json[$device->getId()] = [utf8_encode($device->getAssignedName())];
			}
		}
	
		return new Response(json_encode($json));
	}
	
	// Room
	public function getCategoriesForRoom(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idRoom = $request->get('idRoom');
		$idRoomType = $request->get('idRoomType');
		$idFloor = $request->get('idFloor');
		$idBuilding = $request->get('idBuilding');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		$categories = $em_upv6	->getRepository('App\Entity\Upv6\Categories')
								->findCategoriesForRoom($idRoom, $idRoomType, $idFloor, $idBuilding);
	
		foreach ($categories as $category) {
			$json[$category->getId()][] = utf8_encode($category->getInternalName());
		}
	
		return new Response(json_encode($json));
	}
	
	public function getFamiliesForRoom(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idRoom = $request->get('idRoom');
		$idRoomType = $request->get('idRoomType');
		$idFloor = $request->get('idFloor');
		$idBuilding = $request->get('idBuilding');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		$families = $em_upv6->getRepository('App\Entity\Upv6\Families')
							->findFamiliesForRoom($idRoom, $idRoomType, $idFloor, $idBuilding);
	
		foreach ($families as $family) {
			$json[$family->getId()][] = utf8_encode($family->getInternalName());
		}
	
		return new Response(json_encode($json));
	}
	
	public function getDevicesForRoom(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idRoom = $request->get('idRoom');
		$idRoomType = $request->get('idRoomType');
		$idFloor = $request->get('idFloor');
		$idBuilding = $request->get('idBuilding');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		$devices = $em_upv6	->getRepository('App\Entity\Upv6\Devices')
							->findDevicesForRoom($idRoom, $idRoomType, $idFloor, $idBuilding);

		// User session
		$session = $request->getSession();
		$session_id = $session->getId();

		// The user himself
		$user_id = $em_upv6->getRepository('App\Entity\Upv6\UsersMiddleware')->findOneBy(array('sessionId' => $session_id));
		
		foreach ($devices as $device) {
			// Check the link between the device and the user
			$user_has_device = $em_upv6->getRepository('App\Entity\Upv6\UserHasDevice')
							->findOneBy(array('userId' => $user_id, 'deviceId' => $device->getId()));
			if($user_has_device == true)
			{
				//$json[$device->getId()] = [utf8_encode($device->getAssignedName()),($device->getFamily() ? $device->getFamily()->getId() : '')];
				$json[$device->getId()] = [utf8_encode($device->getAssignedName())];
			}
		}
	
		return new Response(json_encode($json));
	}
	
	// Categories
	public function getFamiliesForCategory(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idCategory = $request->get('idCategory');
		$idRoom = $request->get('idRoom');
		$idRoomType = $request->get('idRoomType');
		$idFloor = $request->get('idFloor');
		$idBuilding = $request->get('idBuilding');
	
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		$families = $em_upv6->getRepository('App\Entity\Upv6\Families')
							->findFamiliesForCategory($idCategory, $idRoom, $idRoomType, $idFloor, $idBuilding);
			
		foreach ($families as $family) {
			$json[$family->getId()][] = utf8_encode($family->getInternalName());
		}
	
		return new Response(json_encode($json));
	}
	
	// Categories
	public function getDevicesForCategory(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	

		$idCategory = $request->get('idCategory');
		$idRoom = $request->get('idRoom');
		$idRoomType = $request->get('idRoomType');
		$idFloor = $request->get('idFloor');
		$idBuilding = $request->get('idBuilding');
		
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
	
		$listDevices = $em_upv6	->getRepository('App\Entity\Upv6\Devices')
								->findDevicesForCategory($idCategory, $idRoom, $idRoomType, $idFloor, $idBuilding);
	
		// User session
		$session = $request->getSession();
		$session_id = $session->getId();

		// The user himself
		$user_id = $em_upv6->getRepository('App\Entity\Upv6\UsersMiddleware')->findOneBy(array('sessionId' => $session_id));

		foreach ($listDevices as $device) {
			// Check the link between the device and the user
			$user_has_device = $em_upv6->getRepository('App\Entity\Upv6\UserHasDevice')
							->findOneBy(array('userId' => $user_id, 'deviceId' => $device->getId()));
			if($user_has_device == true)
			{
				//$json[$device->getId()] = [utf8_encode($device->getAssignedName()),($device->getFamily() ? $device->getFamily()->getId() : '')];
				$json[$device->getId()] = [utf8_encode($device->getAssignedName())];
			}
		}
	
		return new Response(json_encode($json));
	}
	
	public function getDevicesForFamily(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		

		$idCategory = $request->get('idCategory');
		$idFamily = $request->get('idFamily');
		$idRoom = $request->get('idRoom');
		$idRoomType = $request->get('idRoomType');
		$idFloor = $request->get('idFloor');
		$idBuilding = $request->get('idBuilding');
		
		$json = array();
		$json[-1] = $this->translator->trans('nav.all');
		
		$listDevices = $em_upv6	->getRepository('App\Entity\Upv6\Devices')
								->findDevicesForFamily($idFamily, $idCategory, $idRoom, $idRoomType, $idFloor, $idBuilding);
		
		// User session
		$session = $request->getSession();
		$session_id = $session->getId();

		// The user himself
		$user_id = $em_upv6->getRepository('App\Entity\Upv6\UsersMiddleware')->findOneBy(array('sessionId' => $session_id));

		foreach ($listDevices as $device) {
			// Check the link between the device and the user
			$user_has_device = $em_upv6->getRepository('App\Entity\Upv6\UserHasDevice')
							->findOneBy(array('userId' => $user_id, 'deviceId' => $device->getId()));
			if($user_has_device == true)
			{
				//$json[$device->getId()] = [utf8_encode($device->getAssignedName()),($device->getFamily() ? $device->getFamily()->getId() : '')];
				$json[$device->getId()] = [utf8_encode($device->getAssignedName())];
			}
		}
		
		return new Response(json_encode($json));
	}
	
	//Variables
	public function getVariablesAction()
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		$request = $this->getRequest();
		$idCategory = $request->get('idCategory',-1);
		$idFamily = $request->get('idFamily',-1);
		$idRoom = $request->get('idRoom',-1);
		$idRoomType = $request->get('idRoomType',-1);
		$idFloor = $request->get('idFloor',-1);
		$idBuilding = $request->get('idBuilding',-1);
		$idDevice = $request->get('idDevice',-1);
		
		$json = array();
		
		//$json[-1] = $this->get('translator')->trans('nav.all');
		
		$listVariables = array();
		
		if($idDevice != -1)
			$listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesForDevice($idDevice);
		else if($idFamily != -1)
			$listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesForFamily($idFamily, $idCategory, $idRoom, $idRoomType, $idFloor, $idBuilding);
		else if($idCategory != -1)
			$listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesForCategory($idCategory, $idRoom, $idRoomType, $idFloor, $idBuilding);
		else if($idRoom != -1)
			$listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesForRoom($idRoom, $idRoomType, $idFloor, $idBuilding);
		else if($idRoomType != -1)
			$listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesForRoomType($idRoomType, $idBuilding, $idFloor);
		else if($idFloor != -1)
			$listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesForFloor($idFloor, $idBuilding);
		else if($idBuilding != -1)
			$listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesForBuilding($idBuilding);
		else
			$listVariables = $em_upv6->getRepository('App\Entity\Upv6\Variables')->findVariablesUniqueNames();
		
		foreach ($listVariables as $variable) {
			$json[$variable->getId()][] = utf8_encode($variable->getName() . ($variable->getUnit() != "" ? ' (' . $variable->getUnit() . ')' : ""));
		}
		
		return new Response(json_encode($json));
	}
}
