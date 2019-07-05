<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use JMS\SecurityExtraBundle\Annotation\Secure;
use App\Entity\Upv6\Buildings;
use App\Entity\Upv6\Floors;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Upv6\Devices;
use App\Entity\Upv6\Actions;
use App\Entity\Upv6\Modules;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Gui\User;
use App\Entity\Gui\CityDevice;
use App\Entity\Gui\City;
use App\Entity\Upv6\UserHasDevice;


class InteractController extends AbstractController
{
	private $translator;

    public function __construct(DataCollectorTranslator $translator)
    {
        $this->translator = $translator;
	}


	public function iotNavigator(Request $request)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	
    	// Posted Form
    	$postedButton = $request->request->get('searchBtn');
    	
    	if(!is_null($postedButton))
    	{
    		$idBuilding = $request->request->get('buildings');
    		$building = $em_upv6->getRepository('App\Entity\Upv6\Buildings')->find($idBuilding);
    		
    		$idFloor = $request->request->get('floors');
    		$floor = $em_upv6->getRepository('App\Entity\Upv6\Floors')->find($idFloor);
    		
    		$idRoomType = $request->request->get('roomTypes');
    		$roomType = $em_upv6->getRepository('App\Entity\Upv6\RoomTypes')->find($idRoomType);
    		
    		$idRoom = $request->request->get('rooms');
    		$room= $em_upv6->getRepository('App\Entity\Upv6\Rooms')->find($idRoom);
    		
    		$listDevices = $em_upv6->getRepository('App\Entity\Upv6\Devices')->findDevicesByLocation($building, $floor, $roomType, $room);
    		
    		$array = array();
    		
    		// Get an array with actions arrays
    		foreach($listDevices as $device)
    		{
    			$array[] = $device->getModule()->getActions()->toArray();
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
    		
    		if(count($listActions) <= 0) {
    			$data["error"] = $this->get('translator')->trans('msg.no_common_action');
    		}
    		
    		
    		$data["actions"] = $listActions;
    		$data["devices"] = $listDevices;
    	}
    	
        return $this->render('interact/iotNavigator.html.twig');
    }
    
    public function devices()
    {
    	return $this->render('interact/devices.html.twig');
    }
  	
    public function deviceShow(Request $request,Devices $device)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	
    	$variables = $em_upv6	->getRepository('App\Entity\Upv6\Variables')
    							->findBy(
    									array('device' => $device),
    									array('name' => 'ASC')
    							);
    	$data["variables"] = $variables;
    	$data['device'] = $device;
    	
    	$data['url'] = $request->getUri();
    	
    	return $this->render('interact/deviceShow.html.twig', $data);
    }

	public function mapList($devices)
    {
        $data["devices"] = $devices;
        
        return $this->render('interact/mapList.html.twig', $data);
    }

	public static function object_compare($obj1, $obj2)
	{
		$md5 = function($obj){
			return md5(serialize($obj));
		};
		return strcmp($md5($obj1), $md5($obj2));
	}

	public function privileges()
    {
        $devices_list = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $city = $user->getCity();
        $cityDevices = $city->getCityDevices();

        foreach ($cityDevices as $cityDevice)
        {
            $device = $cityDevice->getDevice();
            array_push($devices_list,$device->getUpv6DevicesId());
        }

        $em_upv6 = $this->getDoctrine()->getManager("upv6");
        $devices = $em_upv6->getRepository('App\Entity\Upv6\Devices')->findBy(['id' => $devices_list]);

        $data['devices'] = $devices;

        return $this->render('interact/privileges.html.twig',$data);
    }


    public function privilegesUsers(Devices $device)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $city = $user->getCity();
        $users = $city->getUsers()->getValues();

        $data['users'] = $users;
        $data['device'] = $device;



        $em_upv6 = $this->getDoctrine()->getManager("upv6");
        $user_has_device = $em_upv6->getRepository('App\Entity\Upv6\UserHasDevice')
            ->findBy(array('deviceId' => $device->getId()));

        foreach ($users as &$user)
        {
            foreach ($user_has_device as $has_device)
            {
                if($has_device->getUserId() ==  $user->getId())
                {
                    $user->setAccessProfile((integer)$has_device->getAccessProfile());
                }
            }
        }

        return $this->render('interact/privilegesUsers.html.twig',$data);
    }


    public function setDeviceUserPrivileges(User $user, Devices $device, String $accessProfile)
    {

        $userId = $user->getId();
        $deviceId = $device->getId();

        $em_upv6 = $this->getDoctrine()->getManager("upv6");
        $user_has_device = $em_upv6->getRepository('App\Entity\Upv6\UserHasDevice')
            ->findOneBy(array('deviceId' => $deviceId,'userId' => $userId));


        if(empty($user_has_device))
        {
            $newUserHasDevice = new UserHasDevice();
            $newUserHasDevice->setDeviceId($deviceId);
            $newUserHasDevice->setuserId($userId);
            $newUserHasDevice->setAccessProfile($accessProfile);
            $em_upv6->persist($newUserHasDevice);
            $em_upv6->flush();
        } else {
            $user_has_device->setAccessProfile($accessProfile);
            $em_upv6->persist($user_has_device);
            $em_upv6->flush();
        }

        $response = new JsonResponse('Privileges updated');

        return $response;
    }

    public function setDeviceAccessProfile(Devices $device, String $accessProfile)
    {

        $em_upv6 = $this->getDoctrine()->getManager("upv6");
        $device = $em_upv6->getRepository('App\Entity\Upv6\Devices')
            ->findOneBy(array('id' => $device->getId()));

        $device->setAccessProfile($accessProfile);
        $em_upv6->persist($device);
        $em_upv6->flush();


        $response = new JsonResponse('Privileges updated');

        return $response;

    }

    public function privilegesPublic(Devices $device)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $city = $user->getCity();
        $users = $city->getUsers()->getValues();
        $data['device'] = $device;

        return $this->render('interact/privilegesPublic.html.twig',$data);
    }

    public function privilegesAccredited(Devices $deviceU)
    {
        $devices_list = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $city = $user->getCity();
        $cityDevices = $city->getCityDevices();

        foreach ($cityDevices as $cityDevice)
        {
            $device = $cityDevice->getDevice();
            
            if($deviceU->getId() == $device->getUpv6DevicesId())
            {
                $deviceGui = $device;
            }
        }


        $em_gui = $this->getDoctrine()->getManager("gui");
        $cityDevices = $em_gui->getRepository('App\Entity\Gui\City')->findAccreditation($deviceGui,$city);
       
       
        $data['cityDevice'] = $cityDevices;
        $data['device'] = $deviceU;
        $data['deviceGui'] = $deviceGui;
        
        return $this->render('interact/privilegesAccredited.html.twig',$data);
    }


    public function setAccreditedDeviceAccessProfile(String $device, String $citytocredit, String $accessProfile)
    {
        $em_gui = $this->getDoctrine()->getManager("gui");
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $city = $user->getCity();
        $cityDevices = $em_gui->getRepository('App\Entity\Gui\CityDevice')
        ->findOneBy(['city' => $citytocredit, 'device'=> $device, 'accreditedByCityId' => $city->getId()]);

        if(empty($cityDevices))
        {
            $cityDevices = new CityDevice();
            $cityDevices->setAccreditedByCityId($city->getId());
            $city = $em_gui->getRepository('App\Entity\Gui\City')->findOneBy(['id' => $citytocredit]);
            $cityDevices->setCity($city);
            $device = $em_gui->getRepository('App\Entity\Gui\Device')->findOneBy(['id' => $device]);
            $cityDevices->setDevice($device);
        }

        $cityDevices->setAccreditedAccessProfile($accessProfile);
        $em_gui->persist($cityDevices);
        $em_gui->flush();
        

         $response = new JsonResponse('Privileges updated');

         return $response;

    }


}
