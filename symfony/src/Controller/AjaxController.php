<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Upv6\Devices;
use App\Entity\Gui\Device;
use App\Entity\Upv6\UserHasDevice;
use App\Entity\Upv6\Modules;
use App\Entity\Gui\WebserviceParam;
use iot6\InteractBundle\Form\Type\CardStateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Entity\Gui\User;
use App\Entity\Gui\CityDevice;




class AjaxController extends AbstractController
{
	private $translator;
	

    public function __construct(DataCollectorTranslator $translator)
    {
		$this->translator = $translator;
	}
	/**************  Tree  ****************/
	/**for admin */
	public function getJsonTree(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$listCards = $em_upv6->getRepository('App\Entity\Upv6\Cards')->findBy(array(), array('name' => 'ASC'));
		 
		$array = array();
		$cardsArray = array();

		// User session
		$session = $request->getSession();
		$session_id = $session->getId();

		$user_id = $this->container->get('security.token_storage')->getToken()->getUser()->getId();

		foreach ($listCards as $card)
		{
			$protocolArray = array();
	
			$tempArray = array();
	
			foreach($card->getProtocols() as $protocol)
			{
				$listDevices = $em_upv6->getRepository('App\Entity\Upv6\Devices')->findDevicesByCardAndProtocol($card, $protocol);
				$devicesArray = array();
	
				foreach($listDevices as $device)
				{
					$devices_list = [];
					$user = $this->container->get('security.token_storage')->getToken()->getUser();
					$city = $user->getCity();
					$cityDevices = $city->getCityDevices();
					
					foreach ($cityDevices as $cityDevice)
					{
						$deviceU = $cityDevice->getDevice();
						
						if($deviceU->getUpv6DevicesId() == $device->getId())
						{
							$devicesArray[] = array('data' => $device->getAssignedName(),
								'attr' => array('id' => $device->getId(), 'rel' => 'device'));
						}		
					}
				}
				 
				$protocolArray['data'] = $protocol->getName();
				$protocolArray['attr'] = array('id' => $protocol->getId(), 'rel' => 'protocol');
				$protocolArray['children'] = array($devicesArray);
				 
				$tempArray[] = $protocolArray;
			}
	
			$cardsArray['data'] = array('title' => $card->getName());
	
			$cardsArray['attr'] = array('id' => $card->getId(),
					'rel' => 'card',
					'status' => $card->getCardState()
			);
	
			$cardsArray['children'] = $tempArray;
	
			$array[] = $cardsArray;
		}
		
		$response = new Response(json_encode($array));
		 
		return $response;
	}

	public function getJsonTreeNormalUser(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$listCards = $em_upv6->getRepository('App\Entity\Upv6\Cards')->findBy(array(), array('name' => 'ASC'));
		 
		$array = array();
		$cardsArray = array();

		$user_id = $this->container->get('security.token_storage')->getToken()->getUser()->getId();

		foreach ($listCards as $card)
		{
			$protocolArray = array();
	
			$tempArray = array();
	
			foreach($card->getProtocols() as $protocol)
			{
				$listDevices = $em_upv6->getRepository('App\Entity\Upv6\Devices')->findDevicesByCardAndProtocol($card, $protocol);
				$devicesArray = array();
	
				foreach($listDevices as $device)
				{
					// Check the link between the device and the user
					$user_has_device = $em_upv6->getRepository('App\Entity\Upv6\UserHasDevice')
									->findOneBy(array('userId' => $user_id, 'deviceId' => $device->getId()));

									
					if($user_has_device == true)
					{
						if($user_has_device->getAccessProfile() != -1)
						{
							$devicesArray[] = array('data' => $device->getAssignedName(),
								'attr' => array('id' => $device->getId(), 'rel' => 'device'));
						}
					}
				}
				 
				$protocolArray['data'] = $protocol->getName();
				$protocolArray['attr'] = array('id' => $protocol->getId(), 'rel' => 'protocol');
				$protocolArray['children'] = array($devicesArray);
				 
				$tempArray[] = $protocolArray;
			}
	
			$cardsArray['data'] = array('title' => $card->getName());
	
			$cardsArray['attr'] = array('id' => $card->getId(),
					'rel' => 'card',
					'status' => $card->getCardState()
			);
	
			$cardsArray['children'] = $tempArray;
	
			$array[] = $cardsArray;

		}

		$response = new Response(json_encode($array));
		 
		return $response;


	}// copy the  same previous getjsontree
	
	public function getCard($id, Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$trsl = $this->translator;
		
		$card = $em_upv6->getRepository('App\Entity\Upv6\Cards')->find($id);
		 
		$form = $this	->createFormBuilder($card)
						->add('name', TextType::class, array('attr' => array('class' => 'long')))
						->add('cardState', ChoiceType::class, [
								'choices' => [
									$trsl->trans('msg.cardStates.new') => '1' ,
									$trsl->trans('msg.cardStates.validated') => '2' ,
									$trsl->trans('msg.cardStates.black_listed') => '3',
									$trsl->trans('msg.cardStates.connected') => '4',
									$trsl->trans('msg.cardStates.disconnected') => '5' 
								],'required' => true
						])
						->getForm();
		 
		 
		if ($request->getMethod() == 'POST') {
			$formFields = $request->get('form');
			$name = $formFields['name'];
	
			if(isset($name)) {
				$trsl = $this->translator;
				if($name != '') {
					$form->handleRequest($request);
	
					if ($form->isValid()) {
						$em_upv6->persist($card);
						$em_upv6->flush();
						 
						$reponse = '<div class="customSuccess">'. $trsl->trans('msg.edit_ok') .'</div>';
					}
					else {
						$reponse = '<div class="customError">'. $trsl->trans('msg.fill_all_fields') .'</div>';
					}
				}
				else {
					$reponse = '<div class="customError">'. $trsl->trans('msg.name_field_empty') .'</div>';
				}
			}
			else {
				$reponse = '<div class="customError">'. $trsl->trans('msg.error') .'</div>';
			}
			 
			return new Response($reponse);
		}
		 
		$data["card"] = $card;
		$data["form"] = $form->createView();
	
		return $this->render('interact/getCard.html.twig', $data);
	}
	
	public function getProtocol($id, Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		$deviceToAdd = new Devices();
		
		$form = $this->createFormBuilder($deviceToAdd)
			->add('ipv6address',		TextType::class, array('attr' => array('class' => 'long')))
			->add('physicalCode',		TextType::class, array('attr' => array('class' => 'long')))
			->add('assignedName',		TextType::class, array('attr' => array('class' => 'long')))
			->add('description',		TextType::class, array('attr' => array('class' => 'long')))
			->add('comments',			TextType::class, array('attr' => array('class' => 'long')))
			//->add('validationStatus',	'text')
			->add('detectedAt',			DateTimeType::class)
			->add('lastDataAt',			DateTimeType::class)
			->add('family',				EntityType::class, array('class' => 'App\Entity\Upv6\Families', 'choice_label' => 'internalName'))
			->add('protocol',			EntityType::class, array('class' => 'App\Entity\Upv6\Protocols', 'choice_label' => 'name'))
			->add('module',				EntityType::class, array('class' => 'App\Entity\Upv6\Modules', 'choice_label' => 'name'))
			->add('card',				EntityType::class, array('class' => 'App\Entity\Upv6\Cards', 'choice_label' => 'name'))
			->add('model',				EntityType::class, array('class' => 'App\Entity\Upv6\Models', 'choice_label' => 'name'))
			->add('room',				EntityType::class, array('class' => 'App\Entity\Upv6\Rooms', 'choice_label' => 'name'))
			->add('positionX',			TextType::class)
			->add('positionY',			TextType::class)
			->add('positionZ',			TextType::class)
			->add('privacyApp', CheckboxType::class, [
				'label'    => 'Privacy App ?',
				'required' => false])
			->getForm();
		 
		
		if ($request->getMethod() == 'POST')
		{
			$formFields = $request->get('form');
			//$name = $formFields['name'];
			$trsl = $this->translator;
			/*if(isset($name))
			{
				if($name != '')
				{*/
					$form->handleRequest($request);
					 
					if ($form->isValid())
					{
						$deviceToAdd->setValidationStatus(0);
						$deviceToAdd->setImportanceLevel(1);
						$deviceToAdd->setEnergylevel(1);
						$deviceToAdd->setLastEnergyLevel(1);
						$deviceToAdd->setAccessProfile(1);
						
						$em_upv6->persist($deviceToAdd);
						$em_upv6->flush();

						$user = $this->container->get('security.token_storage')->getToken()->getUser();
						$user_id = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
						/*$user_has_device = new UserHasDevice();
						$user_has_device->setUserId($user_id);
						$user_has_device->setDeviceId($deviceToAdd->getId());
						$em_upv6->persist($user_has_device);
						$em_upv6->flush();*/

                        $em_udg = $this->getDoctrine()->getManager("gui");
						$udgDevice = new Device();
						$udgCityDevice = new CityDevice();

						$udgDevice->setUpv6DevicesId($deviceToAdd->getId());
						$em_udg->persist($udgDevice);
                        $em_udg->flush();

						$udgCityDevice->setCity($user->getCity());
						$udgCityDevice->setDevice($udgDevice);

                        $em_udg->persist($udgCityDevice);
                        $em_udg->flush();



						$reponse = '<div class="customSuccess">'. $trsl->trans('msg.device_added') .'</div>';
					}
					else
					{
						$reponse = '<div class="customError">'. $trsl->trans('msg.error') .'</div>';
					}
				/*}
				else
				{
					$reponse = 'Le champ name est vide';
				}
			}
			else
			{
				$reponse = 'Tous les champs ne sont pas parvenus';
			}*/
			 
			return new Response($reponse);
		}
		
		$protocolToGet = $em_upv6->getRepository('App\Entity\Upv6\Protocols')->find($id);
		
		$data["protocol"] = $protocolToGet;
		$data["form"] = $form->createView();
		 
		return $this->render('interact/getProtocol.html.twig', $data);
	}
	
	public function getDevice(Devices $device, Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
	
		$form = $this	->createFormBuilder($device)
						->add('ipv6address',		TextType::class, array('attr' => array('class' => 'long')))
						->add('physicalCode',		TextType::class, array('attr' => array('class' => 'long')))
						->add('assignedName',		TextType::class, array('attr' => array('class' => 'long')))
						->add('description',		TextareaType::class, array('attr' => array('class' => 'long')))
						->add('comments',			TextareaType::class, array('attr' => array('class' => 'long')))
						//->add('detectedAt',			'datetime', array('attr' => array('class' => 'long')))
						//->add('lastDataAt',			'datetime', array('attr' => array('class' => 'long')))
						->add('family',				EntityType::class, array('class' => 'App\Entity\Upv6\Families', 'choice_label' => 'internalName'))
						->add('category',			EntityType::class, array('class' => 'App\Entity\Upv6\Categories', 'choice_label' => 'internalName'))
						->add('protocol',			EntityType::class, array('class' => 'App\Entity\Upv6\Protocols', 'choice_label' => 'name'))
						->add('module',				EntityType::class, array('class' => 'App\Entity\Upv6\Modules', 'choice_label' => 'name'))
						->add('card',				EntityType::class, array('class' => 'App\Entity\Upv6\Cards', 'choice_label' => 'name'))
						->add('model',				EntityType::class, array('class' => 'App\Entity\Upv6\Models', 'choice_label' => 'name'))
						->add('room',				EntityType::class, array('class' => 'App\Entity\Upv6\Rooms', 'choice_label' => 'name'))
						->add('positionX',			NumberType::class, array('required' => false))
						->add('positionY',			NumberType::class, array('required' => false))
						->add('positionZ',			NumberType::class, array('required' => false))
						->add('longitude',			NumberType::class, array('required' => false))
						->add('latitude',			NumberType::class, array('required' => false))
						->add('privacyApp', CheckboxType::class, [
							'label'    => 'Privacy App',
							'required' => false])
						->getForm();
	
		
		 
		if ($request->getMethod() == 'POST')
		{
			$formFields = $request->get('form');
			$name = $formFields['assignedName'];
			
			$trsl = $this->translator;
			
			if(isset($name))
			{
				if($name != '')
				{
					$form->handleRequest($request);
						
					if ($form->isValid())
					{
                        $em_gui = $this->getDoctrine()->getManager("gui");
						$em_upv6->persist($device);
						$em_upv6->flush();
						/*$guiDevice = new Device();
						$guiDevice->setUpv6DevicesId($device->getId());
						$em_gui->persist($guiDevice);
						$em_gui->flush();*/



						$reponse = '<div class="customSuccess">'. $trsl->trans('msg.device_edited') .'</div>';
					}
					else
					{
						$reponse = '<div class="customError">'. $trsl->trans('msg.fill_all_fields') .'</div>';
					}
				}
				else
				{
					$reponse = '<div class="customError">'. $trsl->trans('msg.fill_all_fields') .'</div>';
				}
			}
			else
			{
				$reponse = '<div class="customError">'. $trsl->trans('msg.error') .'</div>';
			}
			 
			return new Response($reponse);
		}
		
		$variables = $em_upv6	->getRepository('App\Entity\Upv6\Variables')
								->findBy(
									array('device' => $device),
									array('name' => 'ASC')
								);
		
		// Construct URL for QR Code
		$url = $this->get('router')->generate('iot6_InteractBundle_deviceShow', array('id' => $device->getId()), true);
		
		$data["variables"] = $variables;
		$data["device"] = $device;
		$data["form"] = $form->createView();
		$data["url"] = $url;
	
		return $this->render('interact/getDevice.html.twig', $data);
	}

	/**************  IoT6 Navigator  ****************/
	
	// Results of IoT Navigator
	public function getResults(Request $request)
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		
		
		$idBuilding 	= 	$request->get('buildings_complex');
		$idFloor 		= 	$request->get('floors_complex');
		$idRoomyType 	= 	$request->get('roomTypes_complex');
		$idRoom 		= 	$request->get('rooms_complex');
		$idCategory 	= 	$request->get('categories_complex');
		$idFamiliy 		= 	$request->get('families_complex');
		$filter			=	$request->get('bf');

		// User session
		$session = $request->getSession();
		$session_id = $session->getId();
		
		
		// The user himself
		$user_id = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
		$user = $this->container->get('security.token_storage')->getToken()->getUser();
		$userRole = $user->getRoles();
		
		$listUserDevices = $em_upv6->getRepository('App\Entity\Upv6\Devices')
						->findDevicesByLocation($idBuilding, $idFloor, $idRoomyType, $idRoom, $idCategory, $idFamiliy);

		// Filter the device in function of the user
		$listDevices = array();
		foreach($listUserDevices as $device)
		{
			if(in_array("ROLE_ADMIN",$userRole))
			{
				// Check the link between the device and the user
				$user_has_device = $em_upv6->getRepository('App\Entity\Upv6\UserHasDevice')
				->findOneBy(array('deviceId' => $device->getId()));
			} else 
			{
				$user_has_device = $em_upv6->getRepository('App\Entity\Upv6\UserHasDevice')
							->findOneBy(array('userId' => $user_id, 'deviceId' => $device->getId()));
			}
			
			if($user_has_device == true)
			{
				$listDevices[] = $device;
			}
		}

		// Get actions list
		$listActions = $this->getListActions($listDevices, $filter);
		
		// Get devices list ("device1, device1, ...")
		$devicesID = $this->getDevicesList($listDevices);
	
		// If no common action
		if(count($listActions) <= 0) {
			$data["error"] = $this->translator->trans('msg.no_common_action');
		}
	
		// Get units
		$units = array();
		foreach($listActions as $action) {
			//\Doctrine\Common\Util\Debug::dump($action);die();
			if($action->getUnit() != "") {
				if(!in_array($action->getUnit(),$units)) {
					array_push($units, $action->getUnit());
				}
			}
		}
		asort($units);
		
		$data["actions"] = $listActions;
		$data["devices"] = $listDevices;
		$data["devicesID"] = $devicesID;
		$data["units"] = $units;
	
		return $this->render('interact/getResults.html.twig', $data);
	}
	
	// Results of IoT Navigator
	public function getResultsSimple()
	{
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$request = $this->getRequest();
		
		$idBuilding 	= 	$request->get('buildings');
		$idFloor 		= 	$request->get('floors');
		$idRoomyType 	= 	$request->get('roomTypes');
		$idRoom 		= 	$request->get('rooms');
		$filter			=	$request->get('bf');

		// User session
		$session = $request->getSession();
		$session_id = $session->getId();
		
		// The user himself
		//$user_id = $em_upv6->getRepository('App\Entity\Upv6\UsersMiddleware')->findOneBy(array('sessionId' => $session_id));
		$user_id = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
		$listUserDevices = $em_upv6->getRepository('App\Entity\Upv6\Devices')
						->findDevicesByLocation($idBuilding, $idFloor, $idRoomyType, $idRoom);
		
		// Filter the device in function of the user
		$listDevices = array();
		foreach($listUserDevices as $device)
		{
			// Check the link between the device and the user
			$user_has_device = $em_upv6->getRepository('App\Entity\Upv6\UserHasDevice')
							->findOneBy(array('userId' => $user_id, 'deviceId' => $device->getId()));
			if($user_has_device == true)
			{
				$listDevices[] = $device;
			}
		}

		// Get actions list
		$listActions = $this->getListActions($listDevices, $filter);
		
		// Get devices list ("device1, device1, ...")
		$devicesID = $this->getDevicesList($listDevices);
		
		// If no common action
		if(count($listActions) <= 0) {
			$data["error"] = $this->get('translator')->trans('msg.no_common_action');
		}
		
		// Get units
		$units = array();
		foreach($listActions as $action) {
			//\Doctrine\Common\Util\Debug::dump($action);die();
			if($action->getUnit() != "") {
				if(!in_array($action->getUnit(),$units)) {
					array_push($units, $action->getUnit());
				}
			}
		}
		asort($units);
		
		// Construct URL for QR Code
		/*$pos = strpos($this->getRequest()->getUri(), "?");
		$baseUrl = $this->getRequest()->getUri();
		$url = $baseUrl;
		
		if($pos != "") {
			$url = substr($baseUrl, 0 , strpos($baseUrl, "?"));
		}
		
		$url .= "?buildings=". $idBuilding . "&floors=". $idFloor ."&roomTypes=". $idRoomyType ."&rooms=". $idRoom;
		*/
		
		// Construct URL for QR Code
		$url = $this->get('router')->generate('getResultsSimple', array(), true);
		$url .= "?bf=". $filter ."&buildings=". $idBuilding . "&floors=". $idFloor ."&roomTypes=". $idRoomyType ."&rooms=". $idRoom;
		
		$data["actions"] = $listActions;
		$data["devices"] = $listDevices;
		$data["devicesID"] = $devicesID;
		//$data["url"] = urlencode($url);
		$data["url"] = $url;
		$data["units"] = $units;
		
		if($idBuilding != -1) {
			$data["building"]= $em_upv6->getRepository('App\Entity\Upv6\Buildings')->findOneById($idBuilding);
		}
		
		if($idFloor != -1) {
			$data["floor"] = $em_upv6->getRepository('App\Entity\Upv6\Floors')->findOneById($idFloor);
		}
		
		if($idRoomyType != -1) {
			$data["roomType"] = $em_upv6->getRepository('App\Entity\Upv6\RoomTypes')->findOneById($idRoomyType);
		}
		
		if($idRoom != -1) {
			$data["room"] = $em_upv6->getRepository('App\Entity\Upv6\Rooms')->findOneById($idRoom);
		}
		
		return $this->render('interact/getResultsSimple.html.twig', $data);
	}

	private function getListActions($listDevices, $filter)
	{
		if($filter == '0')
		{
			$array = array();
			
			// Get an array with actions arrays
			$pos = 0;
			
			foreach($listDevices as $device)
			{
				//$array[] = $device->getModule()->getActions()->toArray();
				
				//don't get event actions
				$array[$pos] = array();
				foreach ($device->getModule()->getActions() as $action) {
					if($action->getKind() != 0)
						$array[$pos][] = $action;
				}
				
				//add family's actions
				if($device->getFamily()) { //has a family?
					foreach ($device->getFamily()->getActions() as $action) {
						if($action->getKind() != 0) { //don't get event actions
							if(!in_array($action, $array[$pos])) {
								$array[$pos][] = $action;
							}
						}
					}
				}
				$pos++;
			}
			
			$listActions = array();
			
			// s'il y a plusieurs appareils
			if(count($array) > 1)
			{
				$array[] = array($this, 'object_compare');
				$listActions = call_user_func_array(
						'array_uintersect',
						$array
				);
			}
			// s'il y a un seul appareil
			else if(count($array) == 1)
			{
				$listActions = $array[0];
			}
		}
		else 
		{
			$listActions = array();
				
			// Get an array with actions arrays
			foreach($listDevices as $device) {
				foreach ($device->getModule()->getActions() as $action) {
					if($action->getKind() != 0) { //don't get event actions
						if(!in_array($action, $listActions)) {
							$listActions[] = $action;
						}
					}
				}
				//add family's actions
				if($device->getFamily()) { //has a family?
					foreach ($device->getFamily()->getActions() as $action) {
						if($action->getKind() != 0) { //don't get event actions
							if(!in_array($action, $listActions)) {
								$listActions[] = $action;
							}
						}
					}
				}
			}
		}
		
		usort($listActions, array("App\Entity\Upv6\Actions", "cmp_ActionsName"));
		
		return $listActions;
	}
	
	// string des devicesId sous form id1, id2
	private function getDevicesList($listDevices)
	{
		$devicesID = "";
		
		foreach($listDevices as $device)
		{
			$devicesID .= $device->getId() . ",";
		}
		
		$devicesID = rtrim($devicesID, ",");
		
		return $devicesID;
	}
	
	// Compare objects
	public static function object_compare($obj1, $obj2)
	{
		$md5 = function($obj){
			return md5(serialize($obj));
		};
		return strcmp($md5($obj1), $md5($obj2));
	}

	/**************  Webservice Execute  ****************/
	
	// Execute Action (webservice)
	public function execute(Request $request)
	{
		
		$action_id = $request->get('action_id');
		$devices_ids = $request->get('device_id_list');
		$params_list = $request->get('params_list');
		$session = $request->getSession();
		$session_id = $session->getId();
	
		$em_gui = $this->getDoctrine()->getManager("gui");
		$webserviceParam = $em_gui->getRepository('App\Entity\Gui\WebserviceParam')->findOneByParam('kernelUrl');
			
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$setting = $em_upv6->getRepository('App\Entity\Upv6\Settings')->findOneById(1);
			
		$url = WebserviceParam::getActionUrl($webserviceParam->getValue(), $setting->getKernelSharedKey(), $action_id, $devices_ids, $params_list, $session_id);
		$response = WebserviceParam::file_get_contents_curl($url);
	
		return new Response($response);
	}
}
