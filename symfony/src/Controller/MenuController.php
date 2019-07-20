<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Security;


class MenuController extends AbstractController
{
	private $translator;
	private $security;

    public function __construct(DataCollectorTranslator $translator, Security $security)
    {
		$this->translator = $translator;
		$this->security = $security;
	}
	
	public function menuAction()
	{
		$translator = $this->translator;
		
		$em_gui = $this->getDoctrine()->getManager("gui");
		
		$menus = $em_gui->getRepository('App\Entity\Gui\Menu')->findAll();
		
		if (strrpos(get_class($this->container->get('security.token_storage')->getToken()), "AnonymousToken") === false)
		{
			$idUser = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
			$currentUser = $em_gui->getRepository('App\Entity\Gui\User')->find($idUser);
			
			$userMenus = $em_gui->getRepository('App\Entity\Gui\UserMenu')->findByUser($currentUser);
			
			
			foreach ($menus as $menu) {
				foreach($userMenus as $userMenu) {
					if($menu == $userMenu->getMenu()) {
						$menu->setColor($userMenu->getColor());
					}
				}
			}
		}

		

		$request = Request::createFromGlobals()->getRequestUri();
		
		$data["backgroundColor"] = $this->getBackgroundColor();
		$data["route"] = $request;
		$data["menus"] = $menus;
		
	
		
		return $this->render('menu/Menu/menu.html.twig', $data);
	}
	
	public function setBackgroundAction(Request $request)
	{
		/*
		$currentRoute = $request->attributes->get('_route');
		
		$routeName='';
		
		if($currentRoute == 'fos_user_security_login')
		{
			$routeName = 'iot6_HomeBundle';
		}
		else
		{
			$routeName = $currentRoute;
		}

		$em_gui = $this->getDoctrine()->getManager("gui");
		$route = $em_gui->getRepository('App\Entity\Gui\Route')->findOneBy(array('route' => $routeName));
		$menu = $em_gui->getRepository('App\Entity\Gui\Menu')->findOneById($route->getMenu()->getId());

		$data["color"] = $menu->getColor();
		*/
		
		$data["color"] = $this->getBackgroundColor();		
		return $this->render('menu/Menu/background.html.twig', $data);
	}
	
private function getBackgroundColor()
	{
		$em_gui = $this->getDoctrine()->getManager("gui");
		
		$user = null;
		
		//$securityContext = $this->container->get('security.token_storage');
		if($this->security->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
			$user = $this->container->get('security.token_storage')->getToken()->getUser();
		}
		
		return $em_gui	->getRepository('App\Entity\Gui\ConfigParam')
						->getParamValue('background', $user,$em_gui);
	}
	
	public function subMenuInteract()
	{
		
		$translator = $this->translator;
		$listeSubMenu = array(
				array(	'name' => $translator->trans('subMenu.interact.iotNavigator'),
						'href' => 'iot6_InteractBundle_iotNavigator'),
				array(	'name' => $translator->trans('subMenu.interact.mapList'),
						'href' => 'iot6_InteractBundle_mapList'),
                array(	'name' => $translator->trans('subMenu.interact.locationsList'),
						'href' => 'iot6_location_list'),
				array(	'name' => $translator->trans('User Devices'),// make accessible only to normal user
						'href' => 'iot6_InteractBundle_userDevices'),
		);

		$user = $this->container->get('security.token_storage')->getToken()->getUser();
		
		if(in_array( "ROLE_ADMIN",$user->getRoles()))
		{
			array_push($listeSubMenu, array('name' => $translator->trans('Available Device'),
			'href' => 'iot6_InteractBundle_availabledevices',
			'privileges' => 'admin'));

			array_push($listeSubMenu, array('name' => $translator->trans('City Device Profiles'),
			'href' => 'iot6_InteractBundle_devices',
			'privileges' => 'admin'));

			array_push($listeSubMenu,  array(	'name' => 'Devices Privileges',
			'href' => 'iot6_InteractBundle_privileges',
			'privileges' => 'admin'));
		}




	
		$request = Request::createFromGlobals()->getRequestUri();
	
		return $this->render('menu/SubMenu/subMenu.html.twig',
				array('liste_subMenu' => $listeSubMenu, 'route' => $request));
	}
	
	public function subMenuSmartIt()
	{
		
		$translator = $this->translator;
		$listeSubMenu = array(
				array(	'name' => $translator->trans('subMenu.smartIt.scenarios'),
						'href' => 'iot6_SmartItBundle_Scenarios'),
				array(	'name' => $translator->trans('subMenu.smartIt.rulesManager'),
						'href' => 'iot6_SmartItBundle_RulesManager'),
				array(	'name' => $translator->trans('subMenu.smartIt.scheduler'),
						'href' => 'iot6_SmartItBundle_Scheduler'),
				array(	'name' => $translator->trans('subMenu.smartIt.triggers'),
						'href' => 'iot6_SmartItBundle_Triggers'),
				array(	'name' => $translator->trans('subMenu.smartIt.ifThisThenThat'),
						'href' => 'iot6_SmartItBundle_Ittt')
		);
	
		$request = Request::createFromGlobals()->getRequestUri();
	
		return $this->render('menu/SubMenu/subMenu.html.twig',
				array('liste_subMenu' => $listeSubMenu, 'route' => $request));
	}
	
	public function subMenuConfig()
	{
		$translator = $this->translator;
		
		$listeSubMenu = array(
				array(	'name' 	=> $translator->trans('subMenu.config.generalParam'),
						'href' 	=> 'iot6_ConfigBundle_GeneralParameters'),
				array(	'name' => $translator->trans('subMenu.config.locations'),
						'href' => 'iot6_ConfigBundle_Locations_Buildings'),
				array(	'name' => $translator->trans('subMenu.config.devices'),
						'href' => 'iot6_ConfigBundle_Devices_waitingApproval'),
				array(	'name' => $translator->trans('subMenu.config.udgModules'),
						'href' => 'iot6_ConfigBundle_UdgModules_Protocols'),
				array(	'name' => $translator->trans('subMenu.config.configSet'),
						'href' => 'iot6_ConfigBundle_ConfigSet'),
				array(	'name' => $translator->trans('subMenu.config.accessAndSecurity'),
						'href' => 'iot6_ConfigBundle_AccessSecurity_Profil')
		);
		
		$request = Request::createFromGlobals()->getRequestUri();
		
		return $this->render('menu/SubMenu/subMenu.html.twig',
				array('liste_subMenu' => $listeSubMenu, 'route' => $request));
	}
	
	public function subMenuAbout()
	{
		
		$translator = $this->translator;
		$listeSubMenu = array(
				array(	'name' => $translator->trans('subMenu.about.about'),
						'href' => 'iot6_AboutBundle_About'),
				array(	'name' => $translator->trans('subMenu.about.contact'),
						'href' => 'iot6_AboutBundle_Contact')
		);
	
		$request = Request::createFromGlobals()->getRequestUri();
	
		return $this->render('menu/SubMenu/subMenu.html.twig',
				array('liste_subMenu' => $listeSubMenu, 'route' => $request));
	}
}
	
