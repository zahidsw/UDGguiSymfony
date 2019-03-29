<?php

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Upv6\Buildings;
use App\Form\BuildingsType;
use App\Entity\Upv6\Floors;
use App\Form\FloorsType;
use App\Entity\Upv6\Rooms;
use App\Form\RoomsType;
use App\Entity\Upv6\RoomTypes;
use App\Form\RoomTypesType;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Gui\UserMenu;
use App\Entity\Upv6\Devices;
use App\Form\DevicesType;
use App\Entity\Upv6\Families;
use App\Form\FamiliesType;
use App\Entity\Upv6\Categories;
use App\Form\CategoriesType;
use App\Entity\Gui\UserConfigParam;
use App\Entity\Gui\User;
use App\Form\ProfilUserType;
use App\Form\PasswordType;
use App\Entity\Upv6\Services;
use App\Entity\Upv6\Groups;
use Doctrine\Tests\Models\Quote\Group;
use App\Entity\Upv6\GroupHasEntity;
use App\Entity\Upv6\ConfigSet;
use App\Entity\Upv6\ConfigSetting;
use App\Entity\Gui\WebserviceParam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use FOS\UserBundle\Doctrine\UserManager;
use App\Service\KeyRockAPI;




class ConfigController extends AbstractController
{
	private $translator;
	private $encoderFactory;
	private $userManager;

    public function __construct(DataCollectorTranslator $translator, EncoderFactory $encoderFactory, UserManager $userManager,KeyRockAPI $keyRockAPI)
    {
		$this->translator = $translator;
		$this->encoderFactory = $encoderFactory;
		$this->userManager = $userManager;
		$keyRockAPI->setAuthToken('275e4067-5f87-4b55-a3c2-15a9b1e86eb6');// to get for the current user
		$keyRockAPI->setApplicationId('2c87dae1-8c6c-48e5-a319-ba35388df068'); // to get from configuration
	}

    public function config()
    {
        return $this->render('config/config.html.twig');
    }
    
    public function generalParameters(Request $request)
    {
    	$em_gui = $this->getDoctrine()->getManager("gui");
    	
    	$idUser = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
    	$currentUser = $em_gui->getRepository('App\Entity\Gui\User')->find($idUser);
    	
    	
    	
    
    	/************************** Menu **********************/
    	
    	$menus = $em_gui->getRepository('App\Entity\Gui\Menu')->findAll();
    	
    	if ($request->getMethod() == 'POST')
    	{
    		if($request->get('set'))
    		{
    			foreach ($menus as $menu)
	    		{
	    			$currentColor = $request->get('colorpicker_'.$menu->getId());
	    			
	    			if($currentColor != null)
	    			{
	    				$userMenu = $em_gui->getRepository('App\Entity\Gui\UserMenu')->findOneBy(array('user' => $currentUser, 'menu' => $menu));
	    				
	    				if(!is_null($userMenu))//add
	    				{
	    					$userMenu = new UserMenu();
	    					$userMenu->setUser($currentUser);
	    					$userMenu->setMenu($menu);
	    				}
	    				
	    				//edit
	    				$userMenu->setColor($currentColor);
	    				
	    				$em_gui->persist($userMenu);
	    			}
	    		}
	    		
	    		$em_gui->flush();
	    		
	    		$this->setMessage('ok', 'conf.ok.changes_saved');
	    		
	    		return $this->redirect($this->generateUrl('iot6_ConfigBundle_GeneralParameters'));
    		}
    		
    		if($request->get('default'))
    		{
    			$userMenus = $em_gui->getRepository('App\Entity\Gui\UserMenu')->findBy(array('user' => $currentUser));
    			
    			foreach ($userMenus as $userMenu)
    			{
    				$em_gui->remove($userMenu);
    			}
    			
    			$em_gui->flush();
    			
    			$this->setMessage('ok', 'conf.ok.default_values');
    			
    			return $this->redirect($this->generateUrl('iot6_ConfigBundle_GeneralParameters'));
    		}
    	}
    	
    	$userMenus = $em_gui->getRepository('App\Entity\Gui\UserMenu')->findByUser($currentUser);
    		
    	foreach ($menus as $menu)
    	{
    		foreach($userMenus as $userMenu)
    		{
    			if($menu == $userMenu->getMenu())
    			{
    				$menu->setColor($userMenu->getColor());
    			}
    		}
    	}
    	
    	/************************** Config parameters **********************/
    	
    	$configParameters = $em_gui->getRepository('App\Entity\Gui\ConfigParam')->findAll();
    	
    	if ($request->getMethod() == 'POST')
    	{
    		if($request->get('setParam'))
    		{
    			foreach ($configParameters as $configParameter)
    			{
    				$currentValue = $request->get('param_'.$configParameter->getId());
    	
    				if($currentValue != null)
    				{
    					$userParameter = $em_gui->getRepository('App\Entity\Gui\UserConfigParam')->findOneBy(array('user' => $currentUser, 'configParam' => $configParameter));
    		    
    					if(!is_null($userParameter))//add
    					{
    						$userParameter = new UserConfigParam();
    						$userParameter->setUser($currentUser);
    						$userParameter->setConfigParam($configParameter);
    					}
    		    
    					//edit
    					$userParameter->setValue($currentValue);
    		    
    					$em_gui->persist($userParameter);
    				}
    			}
    			 
    			$em_gui->flush();
    			
    			$this->setMessage('ok', 'conf.ok.changes_saved');
    			 
    			return $this->redirect($this->generateUrl('iot6_ConfigBundle_GeneralParameters'));
    		}
    	
    		if($request->get('defaultParam'))
    		{
    			$userParameters = $em_gui->getRepository('App\Entity\Gui\UserConfigParam')->findBy(array('user' => $currentUser));
    			 
    			foreach ($userParameters as $userParameter)
    			{
    				$em_gui->remove($userParameter);
    			}
    			 
    			$em_gui->flush();
    			
    			$this->setMessage('ok', 'conf.ok.default_values');
    			 
    			return $this->redirect($this->generateUrl('iot6_ConfigBundle_GeneralParameters'));
    		}
    	}
    	
    	
    	$userConfigParameters = $em_gui->getRepository('App\Entity\Gui\UserConfigParam')->findByUser($currentUser);
    	
    	foreach ($configParameters as $configParam)
    	{
    		foreach($userConfigParameters as $userConfigParam)
    		{
    			if($configParam == $userConfigParam->getConfigParam())
    			{
    				$configParam->setDefaultValue($userConfigParam->getValue());
    			}
    		}
    	}
    	
    	$data["menus"] = $menus;
    	$data["configParams"] = $configParameters;
    	
    	return $this->render('config/generalParameters.html.twig', $data);
    }
    
    public function devices()
    {
    	return $this->render('config/devices.html.twig');
    }

	/* ************ Locations ************** */
    
    /* Buildings */
    public function buildings($page)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    
    	$nbBuildingProPage = 10;
    	$nbPageDisplayed = 10;
    	$route = 'iot6_ConfigBundle_Locations_Buildings';
    	 
    	$buildings = $em_upv6	->getRepository('App\Entity\Upv6\Buildings')
    							->getBuildings($nbBuildingProPage, $page);
    
    	$data['buildings']		= $buildings;
    	$data['page'] 			= $page;
    	$data['nbPages']		= ceil(count($buildings)/$nbBuildingProPage);
    	$data['nbBuildings']	= count($buildings);
    	$data['nbProPage']		= $nbBuildingProPage;
    	$data['nbPageDisplayed']= $nbPageDisplayed;
    	$data['route']			= $route;
    
    	return $this->render('config/buildings.html.twig', $data);
    }
    
    public function buildingsAdd()
    {
    	$building = new Buildings();
    	$form = $this->createForm(new BuildingsType(), $building);
    	
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST')
    	{
    		$form->bind($request);
    	
    		if ($form->isValid())
    		{
    			//Traitement du fichier uploade
    			$building->upload();
    			
    			$em_upv6 = $this->getDoctrine()->getManager("upv6");
    			$em_upv6->persist($building);
    			$em_upv6->flush();
    			
    			$this->setMessage('ok', 'conf.ok.added_building');
    			
    			return $this->redirect($this->generateUrl('iot6_ConfigBundle_Locations_Buildings'));
    		}
    	}
    	
    	$data['form'] = $form->createView();
    
    	return $this->render('config/buildingsAdd.html.twig', $data);
    }

    public function buildingsEdit(Buildings $building, Request $request)
    {
    	$form = $this->createForm(BuildingsType::class, $building);
    	 
    	
    	if ($request->getMethod() == 'POST')
    	{
    		$form->handleRequest($request);
    		 
    		if ($form->isValid())
    		{
    			//Traitement du fichier uploade
    			$building->upload();
    			
    			$em_upv6 = $this->getDoctrine()->getManager("upv6");
    			$em_upv6->persist($building);
    			$em_upv6->flush();
    			
    			$this->setMessage('ok', 'conf.ok.edited_building');
    			 
    			return $this->redirect($this->generateUrl('iot6_ConfigBundle_Locations_Buildings'));
    		}
    	}
    	
    	$data['building'] = $building;
    	$data['form'] = $form->createView();
    
    	return $this->render('config/buildingsEdit.html.twig', $data);
    }

	public function buildingsDelete(Buildings $building, Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$em_upv6->remove($building);
		$em_upv6->flush();
		
		$this->setMessage('ok', 'conf.ok.deleted_building');
		
		$referer = $request->headers->get('referer');
		
		return $this->redirect($referer);
	}

	/* Floors */
	public function floors($page, $idBuilding)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    
    	$nbFloorsProPage = 10;
    	$nbPageDisplayed = 10;
    	$route = 'iot6_ConfigBundle_Locations_Floors';
    	 
    	$floors = $em_upv6	->getRepository('iot6InteractBundle:Floors')
    						->getFloors($nbFloorsProPage, $page, $idBuilding);
    	
    	$buildings =  $em_upv6	->getRepository('iot6InteractBundle:Buildings')
    							->findBy(array(), array('name' => 'asc'));
    	
    	$data['floors']			= $floors;
    	$data['page'] 			= $page;
    	$data['nbPages']		= ceil(count($floors)/$nbFloorsProPage);
    	$data['nbFloors']		= count($floors);
    	$data['nbProPage']		= $nbFloorsProPage;
    	$data['nbPageDisplayed']= $nbPageDisplayed;
    	$data['route']			= $route;
    	$data['buildings']		= $buildings;
    
    	return $this->render('config/floors.html.twig', $data);
    }
	
    public function floorsAdd()
    {
    	$floor = new Floors();
    	$form = $this->createForm(new FloorsType(), $floor);
    	 
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST')
    	{
    		$form->bind($request);
    		 
    		if ($form->isValid())
    		{
    			//Traitement du fichier uploade
    			$floor->upload();
    			
    			$em_upv6 = $this->getDoctrine()->getManager("upv6");
    			$em_upv6->persist($floor);
    			$em_upv6->flush();
    			
    			$this->setMessage('ok', 'conf.ok.added_floor');
    			 
    			return $this->redirect($this->generateUrl('iot6_ConfigBundle_Locations_Floors'));
    		}
    	}
    	 
    	$data['form'] = $form->createView();
    
    	return $this->render('config/floorsAdd.html.twig', $data);
    }
    
    public function floorsEdit(Floors $floor)
    {
    	$form = $this->createForm(new FloorsType(), $floor);
    
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST')
    	{
    		$form->bind($request);
    		 
    		if ($form->isValid())
    		{
    			//Traitement du fichier uploade
    			$floor->upload();
    			
    			$em_upv6 = $this->getDoctrine()->getManager("upv6");
    			$em_upv6->persist($floor);
    			$em_upv6->flush();
    			
    			$this->setMessage('ok', 'conf.ok.edited_floor');
    
    			return $this->redirect($this->generateUrl('iot6_ConfigBundle_Locations_Floors'));
    		}
    	}
    	 
    	$data['floor'] = $floor;
    	$data['form'] = $form->createView();
    
    	return $this->render('config/floorsEdit.html.twig', $data);
    }
    
	public function floorsDelete(Floors $floor)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$em_upv6->remove($floor);
		$em_upv6->flush();
		
		$this->setMessage('ok', 'conf.ok.deleted_floor');
		
		$referer = $this->getRequest()->headers->get('referer');
		return $this->redirect($referer);
	}
	
	/* Rooms */
	public function rooms($page, $idFloor)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	
		$nbRoomsProPage = 10;
		$nbPageDisplayed = 10;
		$route = 'iot6_ConfigBundle_Locations_Rooms';
	
		$rooms = $em_upv6	->getRepository('App\Entity\Upv6\Rooms')
							->getRooms($nbRoomsProPage, $page, $idFloor);
		 
		$floors =  $em_upv6	->getRepository('App\Entity\Upv6\Floors')
							->findBy(array(), array('name' => 'asc'));
		
		$buildings =  $em_upv6	->getRepository('iot6InteractBundle:Buildings')
								->findBy(array(), array('name' => 'asc'));
		
		$data['rooms']			= $rooms;
		$data['page'] 			= $page;
		$data['nbPages']		= ceil(count($floors)/$nbRoomsProPage);
		$data['nbRooms']		= count($rooms);
		$data['nbProPage']		= $nbRoomsProPage;
		$data['nbPageDisplayed']= $nbPageDisplayed;
		$data['route']			= $route;
		$data['floors']			= $floors;
		$data['buildings']		= $buildings;
	
		return $this->render('config/rooms.html.twig', $data);
	}
	
	public function roomsAdd()
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		$room = new Rooms();
		$form = $this->createForm(new RoomsType(), $room);
	
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST')
		{
			$form->bind($request);
			 
			if ($form->isValid())
			{
				$em_upv6 = $this->getDoctrine()->getManager("upv6");
				$em_upv6->persist($room);
				$em_upv6->flush();
				
				$this->setMessage('ok', 'conf.ok.added_room');
	
				return $this->redirect($this->generateUrl('iot6_ConfigBundle_Locations_Rooms'));
			}
		}
		
		$buildings =  $em_upv6	->getRepository('App\Entity\Upv6\Buildings')
								->findBy(array(), array('name' => 'asc'));
		
		$data['form'] = $form->createView();
		$data['buildings'] = $buildings;
	
		return $this->render('config/roomsAdd.html.twig', $data);
	}
	
	public function roomsEdit(Rooms $room)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		$form = $this->createForm(new RoomsType(), $room);
	
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST')
		{
			$form->bind($request);
			 
			if ($form->isValid())
			{
				$em_upv6 = $this->getDoctrine()->getManager("upv6");
				$em_upv6->persist($room);
				$em_upv6->flush();
				
				$this->setMessage('ok', 'conf.ok.edited_room');
	
				return $this->redirect($this->generateUrl('iot6_ConfigBundle_Locations_Rooms'));
			}
		}
		
		$buildings =  $em_upv6	->getRepository('App\Entity\Upv6\Buildings')
								->findBy(array(), array('name' => 'asc'));
		
		$data['form'] = $form->createView();
		$data['buildings'] = $buildings;
		$data['room'] = $room;
	
		return $this->render('config/roomsEdit.html.twig', $data);
	}
	
	public function roomsDelete(Rooms $room)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$em_upv6->remove($room);
		$em_upv6->flush();
		
		$this->setMessage('ok', 'conf.ok.deleted_room');
	
		$referer = $this->getRequest()->headers->get('referer');
		return $this->redirect($referer);
	}
	
	/* Room Categories */
	public function roomTypes($page)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	
		$nbRoomTypesProPage = 10;
		$nbPageDisplayed = 10;
		$route = 'iot6_ConfigBundle_Locations_RoomTypes';
	
		$roomTypes = $em_upv6	->getRepository('App\Entity\Upv6\RoomTypes')
								->getRoomTypes($nbRoomTypesProPage, $page);
	
		$data['roomTypes']		= $roomTypes;
		$data['page'] 			= $page;
		$data['nbPages']		= ceil(count($roomTypes)/$nbRoomTypesProPage);
		$data['nbRoomTypes']	= count($roomTypes);
		$data['nbProPage']		= $nbRoomTypesProPage;
		$data['nbPageDisplayed']= $nbPageDisplayed;
		$data['route']			= $route;
	
		return $this->render('config/roomTypes.html.twig', $data);
	}
	
	public function roomTypesAdd()
	{
		$roomType = new RoomTypes();
		$form = $this->createForm(new RoomTypesType(), $roomType);
		 
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST')
		{
			$form->bind($request);
			 
			if ($form->isValid())
			{
				//Traitement du fichier uploade
				$roomType->upload();
				
				$em_upv6 = $this->getDoctrine()->getManager("upv6");
				$em_upv6->persist($roomType);
				$em_upv6->flush();
				
				$this->setMessage('ok', 'conf.ok.added_roomType');
				
				return $this->redirect($this->generateUrl('iot6_ConfigBundle_Locations_RoomTypes'));
			}
		}
		 
		$data['form'] = $form->createView();
	
		return $this->render('config/roomTypesAdd.html.twig', $data);
	}
	
	public function roomTypesEdit(RoomTypes $roomType)
	{
		$form = $this->createForm(new RoomTypesType(), $roomType);
	
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST')
		{
			$form->bind($request);
			 
			if ($form->isValid())
			{
				//Traitement du fichier uploade
				$roomType->upload();
				 
				$em_upv6 = $this->getDoctrine()->getManager("upv6");
				$em_upv6->persist($roomType);
				$em_upv6->flush();
				
				$this->setMessage('ok', 'conf.ok.edited_roomType');
	
				return $this->redirect($this->generateUrl('iot6_ConfigBundle_Locations_RoomTypes'));
			}
		}
		 
		$data['roomType'] = $roomType;
		$data['form'] = $form->createView();
	
		return $this->render('config/roomTypesEdit.html.twig', $data);
	}
	
	public function roomTypesDelete(RoomTypes $roomType)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$em_upv6->remove($roomType);
		$em_upv6->flush();
		
		$this->setMessage('ok', 'conf.ok.deleted_roomType');
	
		$referer = $this->getRequest()->headers->get('referer');
		return $this->redirect($referer);
	}
	
	/* ************ Devices ************** */
	
	/* Waiting for approval */
	public function waitingApproval($page)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	
		$nbDevicesProPage = 10;
		$nbPageDisplayed = 10;
		$route = 'iot6_ConfigBundle_Devices_waitingApproval';
	
		$devices = $em_upv6	->getRepository('App\Entity\Upv6\Devices')
							->getDevices($nbDevicesProPage, $page);
	
		$data['devices']		= $devices;
		$data['page'] 			= $page;
		$data['nbPages']		= ceil(count($devices)/$nbDevicesProPage);
		$data['nbBuildings']	= count($devices);
		$data['nbProPage']		= $nbDevicesProPage;
		$data['nbPageDisplayed']= $nbPageDisplayed;
		$data['route']			= $route;
		dump($data);
		
		return $this->render('config/devices.html.twig', $data);
	}
	
	public function validate(Devices $device)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		 
		$device->setValidationStatus(1);
		 
		$em_upv6->flush();

		// Advertise UDG for the validation
		$em_gui = $this->getDoctrine()->getManager("gui");
    		$webserviceParam = $em_gui->getRepository('App\Entity\Gui\WebserviceParam')->findOneByParam('kernelUrl');
    		$setting = $em_upv6->getRepository('iot6InteractBundle:Settings')->findOneById(1);
    		$device_id = $device->getId();
		$session_id = $this->getRequest()->getSession()->getId();
    		$url = WebserviceParam::validateDevice($webserviceParam->getValue(), $setting->getKernelSharedKey(), $device_id, $session_id);
    		$response = WebserviceParam::file_get_contents_curl($url);
			
		$this->setMessage('ok', 'conf.ok.validated_device');
		 
		$referer = $this->getRequest()->headers->get('referer');
		return $this->redirect($referer);
	}
	
	public function waitingApprovalEdit(Devices $device)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		$floors =  $em_upv6	->getRepository('App\Entity\Upv6\Floors')
							->findBy(array(), array('name' => 'asc'));
		
		$buildings =  $em_upv6	->getRepository('App\Entity\Upv6\Buildings')
								->findBy(array(), array('name' => 'asc'));
		
		$form = $this->createForm(new DevicesType(), $device);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST')
		{
			$form->bind($request);
			 
			if ($form->isValid())
			{
				$em_upv6 = $this->getDoctrine()->getManager("upv6");
				$em_upv6->persist($device);
				$em_upv6->flush();
				
				$this->setMessage('ok', 'conf.ok.edited_device');
		
				return $this->redirect($this->generateUrl('iot6_ConfigBundle_Devices_waitingApproval'));
			}
		}
		 
		$data['device'] = $device;
		$data['buildings'] = $buildings;
		$data['floors'] = $floors;
		$data['form'] = $form->createView();
		
		return $this->render('config/devicesEdit.html.twig', $data);
	}
	
	/* Devices families */
	public function families($page)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	
		$nbFamiliesProPage = 10;
		$nbPageDisplayed = 10;
		$route = 'iot6_ConfigBundle_Devices_families';
	
		$families = $em_upv6	->getRepository('iot6InteractBundle:Families')
								->getFamilies($nbFamiliesProPage, $page);
	
		$data['families']		= $families;
		$data['page'] 			= $page;
		$data['nbPages']		= ceil(count($families)/$nbFamiliesProPage);
		$data['nbFamilies']		= count($families);
		$data['nbProPage']		= $nbFamiliesProPage;
		$data['nbPageDisplayed']= $nbPageDisplayed;
		$data['route']			= $route;
	
		return $this->render('config/families.html.twig', $data);
	}
	
	public function familiesAdd()
	{
		$family = new Families();
		$form = $this->createForm(new FamiliesType(), $family);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST')
		{
			$form->bind($request);
	
			if ($form->isValid())
			{
				//Traitement du fichier uploade
				$family->upload();
	
				$em_upv6 = $this->getDoctrine()->getManager("upv6");
				$em_upv6->persist($family);
				$em_upv6->flush();
				
				$this->setMessage('ok', 'conf.ok.added_family');
					
				return $this->redirect($this->generateUrl('iot6_ConfigBundle_Devices_families'));
			}
		}
			
		$data['form'] = $form->createView();
	
		return $this->render('config/familiesAdd.html.twig', $data);
	}
	
	public function familiesEdit(Families $family)
	{
		$form = $this->createForm(new FamiliesType(), $family);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST')
		{
			$form->bind($request);
	
			if ($form->isValid())
			{
				//Traitement du fichier uploade
				$family->upload();
	
				$em_upv6 = $this->getDoctrine()->getManager("upv6");
				$em_upv6->persist($family);
				$em_upv6->flush();
				
				$this->setMessage('ok', 'conf.ok.edited_family');
					
				return $this->redirect($this->generateUrl('iot6_ConfigBundle_Devices_families'));
			}
		}
			
		$data['form'] = $form->createView();
		$data['family'] = $family;
	
		return $this->render('config/familiesEdit.html.twig', $data);
	}
	
	public function familiesDelete(Families $family)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$em_upv6->remove($family);
		$em_upv6->flush();
		
		$this->setMessage('ok', 'conf.ok.deleted_family');
		
		$referer = $this->getRequest()->headers->get('referer');
		return $this->redirect($referer);
	}
	
	/* Devices categories */
	public function categories($page)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	
		$nbCategoriesProPage = 10;
		$nbPageDisplayed = 10;
		$route = 'iot6_ConfigBundle_Devices_categories';
	
		$categories = $em_upv6	->getRepository('App\Entity\Upv6\Categories')
								->getCategories($nbCategoriesProPage, $page);
	
		$data['categories']		= $categories;
		$data['page'] 			= $page;
		$data['nbPages']		= ceil(count($categories)/$nbCategoriesProPage);
		$data['nbCategories']	= count($categories);
		$data['nbProPage']		= $nbCategoriesProPage;
		$data['nbPageDisplayed']= $nbPageDisplayed;
		$data['route']			= $route;
	
		return $this->render('config/categories.html.twig', $data);
	}
	
	public function categoriesAdd()
	{
		$category = new Categories();
		$form = $this->createForm(new CategoriesType(), $category);
	
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST')
		{
			$form->bind($request);
	
			if ($form->isValid())
			{
				//Traitement du fichier uploade
				$category->upload();
	
				$em_upv6 = $this->getDoctrine()->getManager("upv6");
				$em_upv6->persist($category);
				$em_upv6->flush();
				
				$this->setMessage('ok', 'conf.ok.added_category');
					
				return $this->redirect($this->generateUrl('iot6_ConfigBundle_Devices_categories'));
			}
		}
			
		$data['form'] = $form->createView();
	
		return $this->render('config/categoriesAdd.html.twig', $data);
	}
	
	public function categoriesEdit(Categories $category)
	{
		$form = $this->createForm(new CategoriesType(), $category);
	
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST')
		{
			$form->bind($request);
	
			if ($form->isValid())
			{
				//Traitement du fichier uploade
				$category->upload();
	
				$em_upv6 = $this->getDoctrine()->getManager("upv6");
				$em_upv6->persist($category);
				$em_upv6->flush();
				
				$this->setMessage('ok', 'conf.ok.edited_category');
					
				return $this->redirect($this->generateUrl('iot6_ConfigBundle_Devices_categories'));
			}
		}
			
		$data['form'] = $form->createView();
		$data['category'] = $category;
	
		return $this->render('config/categoriesEdit.html.twig', $data);
	}
	
	public function categoriesDelete(Categories $category)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$em_upv6->remove($category);
		$em_upv6->flush();
		
		$this->setMessage('ok', 'conf.ok.deleted_category');
	
		$referer = $this->getRequest()->headers->get('referer');
		return $this->redirect($referer);
	}

	/* QR codes */
	public function qrcode()
	{
		return $this->render('config/qrcode.html.twig');
	}
	
	/* Groups */
	public function groups()
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		$groups = $em_upv6->getRepository('App\Entity\Upv6\Groups')->findBy(array('active' => true), array('category' => 'ASC'));
		
		$data["groups"] = $groups;
		
		return $this->render('config/groups.html.twig', $data);
	}
	
	public function groupsAdd()
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST')
		{
			if($request->get('send'))
			{
				$name			= $request->get('name');
				$description	= $request->get('description');
				$category		= $request->get('category');
				$entities		= $request->get('entities');
				
				if(!is_null($entities)) {
					$group = new Groups();
					$group->setName($name);
					$group->setDescription($description);
					$group->setCategory($category);
					$group->setActive(true);
					
					foreach ($entities as $entityId) {
						$groupHasEntity = new GroupHasEntity();
						$groupHasEntity->setGroup($group);
						$groupHasEntity->setEntityId($entityId);
						
						$em_upv6->persist($groupHasEntity);
					}
					
					$em_upv6->persist($group);
					$em_upv6->flush();
					
					$this->setMessage('ok', 'conf.ok.added_group');
					
					return $this->redirect($this->generateUrl("iot6_ConfigBundle_Devices_groups"));
				}
				else {
					$this->setMessage('ko', 'conf.ko.choose_min_one_entity');
				}		
				
			}
		}
		
		$buildings 	= $em_upv6->getRepository('App\Entity\Upv6\Buildings')->getBuildingsNotInGroups();
		$floors 	= $em_upv6->getRepository('App\Entity\Upv6\Floors')->getFloorsNotInGroups();
		$rooms 		= $em_upv6->getRepository('App\Entity\Upv6\Rooms')->getRoomsNotInGroups();
		$devices 	= $em_upv6->getRepository('App\Entity\Upv6\Devices')->getDevicesNotInGroups();
		
		$data["buildings"] 	= $buildings;
		$data["floors"] 	= $floors;
		$data["rooms"] 		= $rooms;
		$data["devices"] 	= $devices;
		
		return $this->render('config/groupsAdd.html.twig', $data);
	}
	
	public function groupsEdit(Groups $group)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST')
		{
			if($request->get('send'))
			{
				$name			= $request->get('name');
				$description	= $request->get('description');
				$category		= $request->get('category');
				$entities		= $request->get('entities');
		
				if(!is_null($entities)) {
					$group->setName($name);
					$group->setDescription($description);
					$group->setCategory($category);
					
					// Delete all entities
					foreach ($group->getEntities() as $entity) {
						$em_upv6->remove($entity);
					}
					
					// Insert new entities
					foreach ($entities as $entityId) {
						$groupHasEntity = new GroupHasEntity();
						$groupHasEntity->setGroup($group);
						$groupHasEntity->setEntityId($entityId);
		
						$em_upv6->persist($groupHasEntity);
					}
						
					$em_upv6->persist($group);
					$em_upv6->flush();
						
					$this->setMessage('ok', 'conf.ok.edited_group');
						
					return $this->redirect($this->generateUrl("iot6_ConfigBundle_Devices_groups"));
				}
				else {
					$this->setMessage('ko', 'conf.ko.choose_min_one_entity');
				}
			}
		}
		
		$buildings 	= $em_upv6->getRepository('App\Entity\Upv6\Buildings')->getBuildingsNotInGroups();
		$floors 	= $em_upv6->getRepository('App\Entity\Upv6\Floors')->getFloorsNotInGroups();
		$rooms 		= $em_upv6->getRepository('App\Entity\Upv6\Rooms')->getRoomsNotInGroups();
		$devices 	= $em_upv6->getRepository('App\Entity\Upv6\Devices')->getDevicesNotInGroups();
		
		if($group->getCategory() == 1)
			$entities	= $em_upv6->getRepository('App\Entity\Upv6\Buildings')->getBuildingsInGroup($group->getId());
		elseif($group->getCategory() == 2)
			$entities	= $em_upv6->getRepository('App\Entity\Upv6\Floors')->getFloorsInGroup($group->getId());
		elseif($group->getCategory() == 3)
			$entities	= $em_upv6->getRepository('App\Entity\Upv6\Rooms')->getRoomsInGroup($group->getId());
		elseif($group->getCategory() == 4)
			$entities	= $em_upv6->getRepository('App\Entity\Upv6\Devices')->getDevicesInGroup($group->getId());
		
		$data["group"] 		= $group;
		$data["buildings"] 	= $buildings;
		$data["floors"] 	= $floors;
		$data["rooms"] 		= $rooms;
		$data["devices"] 	= $devices;
		$data["entities"] 	= $entities;
		
		return $this->render('config/groupsEdit.html.twig', $data);
	}
	
	public function groupsDelete(Groups $group)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		$dt = new \DateTime();
		
		$group->setActive(false);
		$group->setDeletionDate($dt);
		
		foreach ($group->getEntities() as $entity) {
			$entity->setDeletionDate($dt);
		}
		
		$em_upv6->persist($group);
		$em_upv6->flush();
		
		$this->setMessage('ok', 'conf.ok.deleted_group');
		
		$referer = $this->getRequest()->headers->get('referer');
		return $this->redirect($referer);
	}
	
	/* ************ UDG Modules ************** */
	
	public function protocols($page)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		$nbModulesProPage = 10;
		$nbPageDisplayed = 10;
		$route = 'iot6_ConfigBundle_UdgModules_Protocols';
		
		$modules = $em_upv6	->getRepository('App\Entity\Upv6\Modules')
							->getModules($nbModulesProPage, $page);
		
		$data['modules']		= $modules;
		$data['page'] 			= $page;
		$data['nbPages']		= ceil(count($modules)/$nbModulesProPage);
		$data['nbModules']		= count($modules);
		$data['nbProPage']		= $nbModulesProPage;
		$data['nbPageDisplayed']= $nbPageDisplayed;
		$data['route']			= $route;
		
		return $this->render('config/protocols.html.twig', $data);
	}
	
	public function services($page)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		$nbServicesProPage = 10;
		$nbPageDisplayed = 10;
		$route = 'iot6_ConfigBundle_UdgModules_Services';
		
		$services = $em_upv6 	->getRepository('App\Entity\Upv6\Services')
								->getServices($nbServicesProPage, $page);
		
		$data['services']		= $services;
		$data['page'] 			= $page;
		$data['nbPages']		= ceil(count($services)/$nbServicesProPage);
		$data['nbServices']		= count($services);
		$data['nbProPage']		= $nbServicesProPage;
		$data['nbPageDisplayed']= $nbPageDisplayed;
		$data['route']			= $route;
		
		return $this->render('config/services.html.twig', $data);
	}
	
	public function serviceSettings(Services $service)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		$serviceSettings  = $em_upv6	->getRepository('App\Entity\Upv6\ServiceSettings')
										->findByService($service);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST')
		{
			if($request->get('edit'))
			{
				foreach ($serviceSettings as $setting)
				{
					$currentValue = $request->get('txt_'.$setting->getId());
					$setting->setValue($currentValue);
					
					$em_upv6->persist($setting);
				}

				$em_upv6->flush();
				
				$this->setMessage('ok', 'conf.ok.edited_serviceSettings');
		
				return $this->redirect($this->generateUrl('iot6_ConfigBundle_UdgModules_Services'));
			}
		}
		
		$data["service"] = $service;
		$data["settings"] = $serviceSettings;
		
		return $this->render('config/serviceSettings.html.twig', $data);
	}
	
	/* ************ Access Security ************** */
	
	public function profil(Request $request)
	{
		$user = $this->container->get('security.token_storage')->getToken()->getUser();
		$form = $this->createForm(ProfilUserType::class, $user);
		$error = null;
		
		if ($request->getMethod() == 'POST')
		{
			if($request->get('editProfil'))
			{
				$form->handleRequest($request);
				
				if ($form->isValid())
				{
					$em_gui = $this->getDoctrine()->getManager("gui");
					$em_gui->persist($user);
					$em_gui->flush();
					
					$this->setMessage('ok', 'conf.ok.edited_profil');
				
					return $this->redirect($this->generateUrl('iot6_ConfigBundle_AccessSecurity_Profil'));
				}
			}
			
			if($request->get('editPass'))
			{
				$oldPwd = $request->get('oldPass');
				$newPwd = $request->get('newPass');
				$conf = $request->get('newPass2');
				
				//old pwd match
				$encoder = $this->encoderFactory->getEncoder($user);
				
				if($user->getPassword() == $encoder->encodePassword($oldPwd, $user->getSalt()))
				{
					// identical
					if($newPwd == $conf)
					{
						$userManager = $this->userManager;
						$user->setPlainPassword($newPwd);
						$userManager->updateUser($user);
						
						$this->setMessage('ok', 'conf.ok.edited_profil');
					}
					else 
					{
						$this->setMessage('ko', 'conf.ko.not_identical_pwd');
					}
				}
				else 
				{
					$this->setMessage('ko', 'conf.ko.old_pwd_incorrect');
				}
			}
		}
		
		$data['form'] = $form->createView();
		$data["user"] = $user;
		
		return $this->render('config/profil.html.twig', $data);
	}
	
	public function users()
	{
		$userManager = $this->userManager;
			
		$users = $userManager->findUsers();
		
		usort($users, array("App\Entity\Gui\User", "cmp_username"));
			
		$data["users"] = $users;
		
		return $this->render('config/users.html.twig', $data);
	}
	
	public function usersActDesact(User $user)
	{
		$em_gui = $this->getDoctrine()->getManager("gui");
		 
		$currentState = $user->isEnabled();
		$user->setEnabled(!$currentState);
		 
		$em_gui->flush();
		
		$message = ($user->isEnabled()) ? 'conf.ok.activate_user' : 'conf.ok.deactivate_user';
		$this->setMessage('ok', $message);
		
		$referer = $this->getRequest()->headers->get('referer');
		
		return $this->redirect($referer);
	}
	

	public function usersAdd(Request $request, KeyRockAPI $keyRockAPI)
	{
				
		$em_gui = $this->getDoctrine()->getManager("gui");
		$roles = $em_gui->getRepository('App\Entity\Gui\Role')->findAll();
		
		$roleSources = array();
		
		for($i=count($roles)-1; $i>0; $i--) 
		{
			if($roles[$i]->getId() != 1) 
			{
				array_unshift($roleSources, array_pop($roles));
			}
		}
		
		if ($request->getMethod() == 'POST')
		{
			if($request->get('send'))
			{
				$username	= $request->get('username');
				$email		= $request->get('email');
				$isActive	= $request->get('isActive');
				$pass 		= $request->get('pass');
				$conf 		= $request->get('conf');
				$roles 		= $request->get('roles');

				
				// identical pwd
				if($pass == $conf) 
				{
					
					$response = $keyRockAPI->registerUser($username,$email,$pass);
					$statusCodeRegister = $response->getStatusCode();

					if($statusCodeRegister == "201")
					{
						$user = (string)$response->getBody();
						$user =json_decode($user,true);
						$roleId = $keyRockAPI->getRoleId('user');

						if($roleId != false)
						{
							$userFiwareId = $user['user']['id'];
							$response = $keyRockAPI->assignRole($userFiwareId,$roleId);
							$statusCodeAssignRole = $response->getStatusCode();

							if($statusCodeAssignRole == '201')
							{
								$userManager = $this->userManager;
								$user = $userManager->createUser();
								$user->setUsername($username);
								$user->setEmail($email);
								$user->setEnabled( (!is_null($isActive)) ? true : false );
								$user->setFiwareId($userFiwareId);
		
								foreach ($roles as $roleId) {
									$role = $em_gui->getRepository('App\Entity\Gui\Role')->findOneById($roleId);
									$user->addRole($role->getName());
								}
								$userManager->updateUser($user);
								$this->setMessage('ok', 'conf.ok.added_user');
								return $this->redirect($this->generateUrl("iot6_ConfigBundle_AccessSecurity_Users"));
							}
						}
					}

					if($statusCodeRegister != '201' || $roleId == false || $statusCodeAssignRole != '201')
					{
						$this->setMessage('ko', 'Somenthing went wrong. Try later!');
						$referer = $request->headers->get('referer');
						return $this->redirect($referer);
					}
				}
				else 
				{
					$this->setMessage('ko', 'conf.ko.not_identical_pwd');
					
					$referer = $request->headers->get('referer');
					return $this->redirect($referer);
				}
			}
		}
		
		$data['rolesSource'] = $roleSources;
		$data['rolesDestination'] = $roles;
		
		return $this->render('config/usersAdd.html.twig', $data);
	}
	
	public function usersEdit(User $user)
	{
		$error = null;
		
		$em_gui = $this->getDoctrine()->getManager("gui");
		$roles = $em_gui->getRepository('App\Entity\Gui\Role')->findAll();
		
		$roleDestination = array();
		$userRoles = $user->getRoles();
		
		for($i=count($roles)-1; $i>=0; $i--) {
				if(in_array($roles[$i]->getName(), $userRoles)) {
					$pop = $roles[$i];
					unset($roles[$i]);
					array_unshift($roleDestination, $pop);
				}
		}
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST')
		{
			if($request->get('send'))
			{
				$username	= $this->getRequest()->request->get('username');
				$email		= $this->getRequest()->request->get('email');
				$isActive	= $this->getRequest()->request->get('isActive');
				$pass 		= $this->getRequest()->request->get('pass');
				$conf 		= $this->getRequest()->request->get('conf');
				$roles 		= $this->getRequest()->request->get('roles');
		
				// identical pwd
				if($pass == $conf) {
					$userManager = $this->get('fos_user.user_manager');
					
					$user->setUsername($username);
					$user->setEmail($email);
					$user->setEnabled( (!is_null($isActive)) ? true : false );
					
					if($pass != 'password') {
						$user->setPlainPassword($pass);
					}
					
					$roleNames = array();
					
					foreach ($roles as $roleId) {
						$role = $em_gui->getRepository('App\Entity\Gui\Role')->findOneById($roleId);
						array_push($roleNames, $role->getName());
					}
					
					$user->setRoles($roleNames);
						
					$userManager->updateUser($user);
					
					$this->setMessage('ok', 'conf.ok.edited_user');
						
					return $this->redirect($this->generateUrl("iot6_ConfigBundle_AccessSecurity_Users"));
				}
				else {
					$this->setMessage('ko', 'conf.ko.not_identical_pwd');
					
					$referer = $this->getRequest()->headers->get('referer');
					return $this->redirect($referer);
				}
			}
		}
		
		$data['user'] = $user;
		$data['rolesSource'] = $roles;
		$data['rolesDestination'] = $roleDestination;
		
		return $this->render('config/usersEdit.html.twig', $data);
	}
	
	public function usersDelete(User $user, Request $request,KeyRockAPI $keyRockAPI)
	{
		$userManager = $this->userManager;
		$response = $keyRockAPI->deleteUser($user->getFiwareId());
		$statusCodeDeleteUser = $response->getStatusCode();
		
		if( $statusCodeDeleteUser != '204')
		{
			$this->setMessage('ko', 'Somenthing went wrong, try later!');
			$referer = $request->headers->get('referer');
    		return $this->redirect($referer);
		}
		$userManager->deleteUser($user);
		$this->setMessage('ok', 'conf.ok.deleted_user');
    	$referer = $request->headers->get('referer');
    	return $this->redirect($referer);
	}
	
	private function setMessage($type, $value)
	{
		$message = $this->translator->trans($value);
		$this->get('session')->getFlashBag()->add($type, $message);
	}
	
	public function configSet()
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$data["configSets"] = $em_upv6->getRepository('App\Entity\Upv6\ConfigSet')->findActiveOrderByUser();
		
		return $this->render('config/configSet.html.twig', $data);
	}
	
	public function configSetAdd()
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST')
		{
			if($request->get('send'))
			{
				$name			= $request->get('name');
				$idUser			= $request->get('user');
				$isActive		= $request->get('isActive');
				$keys			= $request->get('keys');
				$values			= $request->get('values');
		
				if(!is_null($keys) && !is_null($values)) {
					
					$user = $em_upv6->getRepository('App\Entity\Upv6\UsersMiddleware')->findOneById($idUser);
					
					$configSet = new ConfigSet();
					$configSet->setUser($user);
					$configSet->setName($name);
					
					if(!is_null($isActive)) {
						$activeConfigSet = $em_upv6->getRepository('App\Entity\Upv6\ConfigSet')->findOneBy(array('active' => true, 'user' => $user));
						if(!is_null($activeConfigSet)) {
							$activeConfigSet->setActive(false);
						}
						
						$configSet->setActive(true);
					}
					else {
						$configSet->setActive(false);
					}
					
					foreach (array_combine($keys, $values) as $key => $value) {
						$configSetting = new ConfigSetting();
						$configSetting->setConfigSet($configSet);
						$configSetting->setName($key);
						$configSetting->setValue($value);
						
						$em_upv6->persist($configSetting);
					}
					
					$em_upv6->persist($configSet);
					$em_upv6->flush();
						
					$this->setMessage('ok', 'conf.ok.added_configSet');
						
					return $this->redirect($this->generateUrl("iot6_ConfigBundle_ConfigSet"));
				}
				else {
					$this->setMessage('ko', 'conf.ko.set_min_one_key_value');
				}
			}
		}
		
		$data["users"] = $em_upv6->getRepository('App\Entity\Upv6\UsersMiddleware')->findBy(array(), array('name' => 'ASC'));
		
		return $this->render('config/configSetAdd.html.twig', $data);
	}
	
	/**
	 * @ParamConverter("configSet", options={"entity_manager" = "upv6"})
	 */
	public function configSetEdit(ConfigSet $configSet,Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	
		
		if ($request->getMethod() == 'POST')
		{
			if($request->get('send'))
			{
				$name			= $request->get('name');
				$idUser			= $request->get('user');
				$keys			= $request->get('keys');
				$values			= $request->get('values');
	
				if(!is_null($keys) && !is_null($values)) {
						
					$user = $em_upv6->getRepository('App\Entity\Upv6\UsersMiddleware')->findOneById($idUser);
					
					if($user != $configSet->getUser()) {
						$configSet->setActive(false);
					}
					
					$configSet->setUser($user);
					$configSet->setName($name);
					
					// Delete all settings
					foreach ($configSet->getSettings() as $setting) {
						$em_upv6->remove($setting);
					}
					
					foreach (array_combine($keys, $values) as $key => $value) {
						$configSetting = new ConfigSetting();
						$configSetting->setConfigSet($configSet);
						$configSetting->setName($key);
						$configSetting->setValue($value);
	
						$em_upv6->persist($configSetting);
					}
						
					$em_upv6->persist($configSet);
					$em_upv6->flush();
	
					$this->setMessage('ok', 'conf.ok.edited_configSet');
	
					return $this->redirect($this->generateUrl("iot6_ConfigBundle_ConfigSet"));
				}
				else {
					$this->setMessage('ko', 'conf.ko.set_min_one_key_value');
				}
			}
		}
	
		$data["users"] = $em_upv6->getRepository('App\Entity\Upv6\UsersMiddleware')->findBy(array(), array('name' => 'ASC'));
		$data["configSet"] = $configSet;
		
		return $this->render('config/configSetEdit.html.twig', $data);
	}
	
	/**
	 * @ParamConverter("configSet", options={"entity_manager" = "upv6"})
	 */
	public function configSetAct(ConfigSet $configSet)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
			
		$activeConfigSet = $em_upv6->getRepository('App\Entity\Upv6\ConfigSet')->findOneBy(array('active' => true, 'user' => $configSet->getUser()));
		$activeConfigSet->setActive(false);
		
		$configSet->setActive(true);
					
		$em_upv6->flush();
		
		$message = 'conf.ok.activate_configSet';
		$this->setMessage('ok', $message);
		
		$referer = $this->getRequest()->headers->get('referer');
		
		return $this->redirect($referer);
	}
	
	/**
	 * @ParamConverter("configSet", options={"entity_manager" = "upv6"})
	 */
	public function configSetDelete(ConfigSet $configSet)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		// Delete settings
		foreach($configSet->getSettings() as $setting) {
			$em_upv6->remove($setting);
		}
		
		// Delete configSet
		$em_upv6->remove($configSet);
			
		$em_upv6->flush();
	
		$message = 'conf.ok.deleted_configSet';
		$this->setMessage('ok', $message);
	
		$referer = $this->getRequest()->headers->get('referer');
	
		return $this->redirect($referer);
	}
}
