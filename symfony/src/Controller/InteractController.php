<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use JMS\SecurityExtraBundle\Annotation\Secure;
use iot6\InteractBundle\Entity\Buildings;
use iot6\InteractBundle\Entity\Floors;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Upv6\Devices;
use iot6\InteractBundle\Entity\Actions;
use iot6\InteractBundle\Entity\Modules;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Routing\Annotation\Route;



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
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $city = $user->getCity();

        $devices = $city->getDevices();

        $devices = $devices->getValues();
        $devices_list = [];

        foreach ($devices as $device)
        {
            array_push($devices_list,$device->getUpv6DevicesId());
        }



        $em_upv6 = $this->getDoctrine()->getManager("upv6");
        $devices = $em_upv6->getRepository('App\Entity\Upv6\Devices')->findBy(['id' => $devices_list]);


        //dd($devices);
        $data['devices'] = $devices;


        return $this->render('interact/privileges.html.twig',$data);
    }


    public function privilegesUsers(Devices $device, Request $request)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $city = $user->getCity();
        $users = $city->getUsers()->getValues();

        $data['users'] = $users;







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


        //dd($users);




        return $this->render('interact/privilegesUsers.html.twig',$data);

    }


    public function setDeviceUserPrivileges(Request $request)
    {

        dd('ciao');
        if($request->request->get('some_var_name')){
            //make something curious, get some unbelieveable data
            $arrData = ['output' => 'here the result which will appear in div'];
            return new JsonResponse($arrData);
        }

        return $this->render('app/main/index.html.twig');
    }
}
