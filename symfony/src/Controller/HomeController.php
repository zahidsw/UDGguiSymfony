<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
//namespace iot6\HomeBundle\Controller;
use SunCat\MobileDetectBundle\DeviceDetector\MobileDetector;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Controller\MenuController;
use App\Entity\Gui\Route;
use App\Entity\Gui\WebserviceParam;



class HomeController extends AbstractController
{
	private $mb;

    public function __construct(MobileDetector $detector)
    {
        $this->mb = $detector;
    }

	public function home(Request $request)
    {
    	//phpinfo();
    	$em_gui = $this->getDoctrine()->getManager("gui");
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
		//var_dump($this->container->get('security.token_storage')->getToken()->getUser());die;
		//var_dump($user = $this->getUser());die;
    	$idUser = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
    	$currentUser = $em_gui->getRepository('App\Entity\Gui\User')->find($idUser);

	// Session

	$session = $request->getSession();
	$session_id = $session->getId();

	// Send user + session
	$webserviceParam = $em_gui->getRepository('App\Entity\Gui\WebserviceParam')->findOneByParam('kernelUrl');   	
    $setting = $em_upv6->getRepository('App\Entity\Upv6\Settings')->findOneById(1);  	
    $url = WebserviceParam::sendUser($webserviceParam->getValue(), $setting->getKernelSharedKey(), $currentUser, $session_id);
	$response = WebserviceParam::file_get_contents_curl($url);
  	
    	$data['shortcuts'] = $currentUser->getRoutes();
  	
    	$scenarios = $em_upv6->getRepository('App\Entity\Upv6\Rules')->findBy(
    			array(),
    			array('updatedAt' => 'desc'),
    			3
    			);
    	 
    	$data['scenarios'] = $scenarios;
    	
    	//$mb = $this->get('mobile_detect.mobile_detector');
		$mb = $this->mb;
    	$ext = $mb->isMobile() ? $mb->isTablet() ? "" : "Mobile" : "";
        return $this->render('home/home.html.twig', $data);
    }
    
    public function remoteServerMsg()
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	
    	// Get Alerts
    	$listMessage = $em_upv6
				    	->getRepository('App\Entity\Upv6\SystemLogs')
				    	->findBy(
				    			array(),
				    			array('createdAt' => 'DESC'),
				    			'3');
    	 
    	 
    	$data["messages"] = $listMessage;
    	
    	return $this->render('home/remoteServerMsg.html.twig', $data);
    }
    
    public function lastAlerts()
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	 
    	// Get Alerts
    	$listAlerts = $em_upv6
    					->getRepository('App\Entity\Upv6\Alerts')
    					->findBy(
    							array(),
    							array('createdAt' => 'DESC'),
    							'3');
    	
    	
    	$data["alerts"] = $listAlerts;
    	
    	return $this->render('home/lastAlerts.html.twig', $data);
    }

	public function bookmarkAddAction(Route $route)
	{
		$em_gui = $this->getDoctrine()->getManager("gui");
		
		$idUser = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
		$currentUser = $em_gui->getRepository('iot6UserBundle:User')->find($idUser);
		
		$currentUser->addRoute($route);
		
		$em_gui->persist($currentUser);
		$em_gui->flush();
		
		return new Response();
	}
	
	public function bookmarkDelAction(Route $route)
	{
		$this->removeRoute($route);
	
		return new Response();
	}

	public function showAddBookmarkAction($route)
	{
		$em_gui = $this->getDoctrine()->getManager("gui");
		$routeResult = $em_gui->getRepository('App\Entity\Gui\Route')->findOneBy(array('route' => $route, 'visibleForBookmark' => true));
		
		$data['display'] = false;

		if(!is_null($routeResult))
		{
			$idUser = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
			$currentUser = $em_gui->getRepository('App\Entity\Gui\User')->find($idUser);
			$userRoutes = $currentUser->getRoutes();
			
			$add = true;
			
			foreach ($userRoutes as $routeItem)
			{
				if($routeItem->getRoute() == $route)
					$add = false;
			}
			
			$data["add"] = $add;
			$data['display'] = true;
			$data['idRoute'] = $routeResult->getId();
		}
		
		return $this->render('home/bookmark.html.twig', $data);
	}
	
	public function bookmarkManagementAction()
	{
		$em_gui = $this->getDoctrine()->getManager("gui");
		 
		$idUser = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
		$currentUser = $em_gui->getRepository('iot6UserBundle:User')->find($idUser);
		 
		$data['shortcuts'] = $currentUser->getRoutes();
		
		return $this->render('iot6HomeBundle:Home:bookmarkManagement.html.twig', $data);
	}

	/*public function bookmarkDeleteAction(Route $route)
	{
		$this->removeRoute($route);
		
		$message = $this->get('translator')->trans('msg.bookmark_deleted');
    	$this->get('session')->getFlashBag()->add('ok', $message);
	
		return $this->redirect($this->generateUrl('iot6_HomeBundle_bookmarkManagement'));
	}
	
	private function removeRoute(Route $route)
	{
		$em_gui = $this->getDoctrine()->getManager("gui");
		
		$idUser = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
		$currentUser = $em_gui->getRepository('iot6UserBundle:User')->find($idUser);
		
		$currentUser->removeRoute($route);
		
		$em_gui->persist($currentUser);
		$em_gui->flush();
    }*/
}
