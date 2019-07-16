<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Upv6\Rules;
use App\Entity\Upv6\Schedules;
use App\Entity\Upv6\EventHasRule;
use iot6\SmartItBundle\Util\UADSUtility;
use App\Entity\Gui\ConfigParameters;
use App\Entity\Gui\WebserviceParam;
use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RulesType;
use App\Form\SchedulesType;
use Symfony\Component\Translation\DataCollectorTranslator;



class SmartItController extends AbstractController
{

	private $translator;
	

    public function __construct(DataCollectorTranslator $translator)
    {
		$this->translator = $translator;
	}

    public function scenarios()
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	$rules = $em_upv6->getRepository('App\Entity\Upv6\Rules')->findBy(
    				array('ruleType' => '2', 'isActive' => true), 
    				array('name' => 'asc'));
    	
    	$data['rules'] = $rules;
    	
    	return $this->render('smartit/scenarios.html.twig', $data);
    }
    
    public function executeScenario(Request $request)
    {

    	$scenario_id = $request->get('scenario_id');
    	$target_id = $request->get('target_id');
	$session = $request->getSession();
	$session_id = $session->getId();

    	$em_gui = $this->getDoctrine()->getManager("gui");
    	$webserviceParam = $em_gui->getRepository('App\Entity\Gui\WebserviceParam')->findOneByParam('kernelUrl');
    	
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	$setting = $em_upv6->getRepository('App\Entity\Upv6\Settings')->findOneById(1);
    	
    	$url = WebserviceParam::getScenarioUrl($webserviceParam->getValue(), $setting->getKernelSharedKey(), $scenario_id, $target_id, $session_id);
    	
    	$response = WebserviceParam::file_get_contents_curl($url);
 
   		return new Response($response);
    }

    public function ittt(Request $request)
    {

    	if ($request->getMethod() == 'POST')
    	{
    		if($request->get('btnSaveITTT'))
    		{
    			$em_upv6 = $this->getDoctrine()->getManager("upv6");
    			
    			// Get POST variables
    			$building_source 	= $request->get('buildings_source');
    			$floor_source 		= $request->get('floors_source');
    			$roomType_source 	= $request->get('roomTypes_source');
    			$room_source 		= $request->get('rooms_source');
    			$category_source 	= $request->get('categories_source');
    			$family_source 		= $request->get('families_source');
    			$device_source 		= $request->get('devices_source');
    			
    			$event_source 		= $request->get('events_source');
    			$param_source 		= $request->get('parameters_source');
    			$relation_source 	= $request->get('relation_source');
    			$value_source 		= $request->get('value_source');
    			
    			$building_target 	= $request->get('buildings_target');
    			$floor_target 		= $request->get('floors_target');
    			$roomType_target	= $request->get('roomTypes_target');
    			$room_target		= $request->get('rooms_target');
    			$category_target 	= $request->get('categories_target');
    			$family_target		= $request->get('families_target');
    			$device_target 		= $request->get('devices_target');
    			
    			$action_target 		= $request->get('actions_target');
    			$action = $em_upv6->getRepository('App\Entity\Upv6\Actions')->findOneById($action_target);
    			if(!is_null($action)) {
    				$parameters = $action->getParameters();
    			}
    			
    			$trsl = $this->translator;
    			
    			// Check required fields
    			if($building_source == -1 && $floor_source == -1 && $roomType_source == -1 && $room_source == -1 && 
    					$category_source == -1 && $family_source == -1 && $device_source == -1) {
    				$this->get('session')->getFlashBag()->add('ko', $trsl->trans('error.select_one_source'));
    				return $this->render('smartit/ittt.html.twig', $data);
    			}
    			
    			if($building_target == -1 && $floor_target == -1 && $roomType_target == -1 && $room_target == -1 &&
    					$category_target == -1 && $family_target == -1 && $device_target == -1) {
    				$this->get('session')->getFlashBag()->add('ko', $trsl->trans('error.select_one_target'));
    				return $this->render('smartit/ittt.html.twig', $data);
    			}
    			
    			if($event_source == -1) {
    				$this->get('session')->getFlashBag()->add('ko', $trsl->trans('error.select_one_event'));
    				return $this->render('smartit/ittt.html.twig', $data);
    			}
    			
    			if($param_source == -1) {
    				$this->get('session')->getFlashBag()->add('ko', $trsl->trans('error.select_one_param'));
    				return $this->render('smartit/ittt.html.twig', $data);
    			}
    			
    			if($relation_source == -1) {
    				$this->get('session')->getFlashBag()->add('ko', $trsl->trans('error.select_one_comparator'));
    				return $this->render('smartit/ittt.html.twig', $data);
    			}
    			
    			if($value_source == "") {
    				$this->get('session')->getFlashBag()->add('ko', $trsl->trans('error.fill_source_value'));
    				return $this->render('smartit/ittt.html.twig', $data);
    			}
    			
    			if($action_target == -1) {
    				$this->get('session')->getFlashBag()->add('ko', $trsl->trans('error.select_one_action'));
    				return $this->render('smartit/ittt.html.twig', $data);
    			}
    			
    			// Table Rules
    			/*
    			echo "<b>TABLE RULE</b><br/>";
    			echo "action_id: " . $action_target . "<br/>";
    			echo "name: " . $action->getInternalName() . "<br/>";
    			echo "isActive: " . "1" . "<br/>";
    			echo "ruleType: " . "1" . "<br/><br/>";
    			
    			echo "regle:<br/>";
    			*/
    			$parameterSource = $em_upv6->getRepository('App\Entity\Upv6\Parameters')->findOneById($param_source);
    			if(!is_null($parameterSource)) {
	    			if($parameterSource->getKind() == 2) {
	    				$value_source = "'" . $value_source . "'";
	    			}
    			}
    			
    			$comparator = "";
    			if($relation_source == 1) {
    				$comparator = "==";
    			}
    			else if($relation_source == 2) {
    				$comparator = ">";
    			}
    			else if($relation_source == 3) {
    				$comparator = "<";
    			}	
    				
    			$rule  = "if(" . $parameterSource->getName() . " " . $comparator . " " . $value_source . ")";
    			$rule .= "\n{\n";
    			$rule .= "\ttarget = new java.util.HashMap();";
    			$rule .= "\n";
    			$rule .= ($building_target != -1) 	? "\n\ttarget.put('building', new java.lang.Integer(" . $building_target . "));" : "";
    			$rule .= ($floor_target != -1) 		? "\n\ttarget.put('floor', new java.lang.Integer(" . $floor_target . "));" : "";
    			$rule .= ($roomType_target != -1) 	? "\n\ttarget.put('roomType', new java.lang.Integer(" . $roomType_target . "));" : "";
    			$rule .= ($room_target != -1) 		? "\n\ttarget.put('room', new java.lang.Integer(" . $room_target . "));" : "";
    			$rule .= ($category_target != -1) 	? "\n\ttarget.put('category', new java.lang.Integer(" . $category_target . "));" : "";
    			$rule .= ($family_target != -1) 	? "\n\ttarget.put('family', new java.lang.Integer(" . $family_target . "));" : "";
    			$rule .= ($device_target != -1) 	? "\n\ttarget.put('device', new java.lang.Integer(" . $device_target . "));" : "";
    			
    			$rule .= "\n\n\tparams = java.lang.reflect.Array.newInstance(java.lang.Object, " . count($parameters) . ");\n";
    			
    			for($i=0; $i<count($parameters); $i++) {
    				$valueParam = $request->get('textboxTarget_' . $parameters[$i]->getId());
    				
    				if($parameters[$i]->getKind() == 0) {
    					$type = "Long";
    				}
    				else if($parameters[$i]->getKind() == 1) {
    					$type = "Double";
    				}
    				else if($parameters[$i]->getKind() == 2) {
    					$type = "String";
    					$valueParam = "'" . $valueParam . "'";
    				}
    				else if($parameters[$i]->getKind() == 3) {
    					$type = "ByteArray";
    				}
    				else if($parameters[$i]->getKind() == 4) {
    					$type = "Float";
    				}
    				$rule .= "\tparams[" . $parameters[$i]->getPosition() . "] = new java.lang." . $type . "(" . $valueParam . ");\n";
    			}
    			
    			$rule .= "\n\tscriptFactory.getTarget(target).execute(scriptFactory.create('" . $action_target . "', params));";
    			$rule .= "\n}";
    			
    			/*
    			echo "<pre>" . $rule . "</pre><br/><br/>";
    			
    			// Table Event_Has_Rule
    			echo "<b>TABLE EVENT_HAS_RULE</b><br/>";
    			echo "action_id: " . $event_source . "<br/>";
    			echo "building_id: " . $building_source . "<br/>";
    			echo "floor_id: " . $floor_source . "<br/>";
    			echo "roomType_id: " . $roomType_source . "<br/>";
    			echo "room_id: " . $room_source . "<br/>";
    			echo "category_id: " . $category_source . "<br/>";
    			echo "family_id: " . $family_source . "<br/>";
    			echo "device_id: " . $device_source . "<br/>";
    			echo "rule_id: id de la regle ci-dessus<br/>";
    			echo "override: ??<br/>";
    			echo "forced: ??<br/>";
    			echo "save_variable: ??<br/>";
    			echo "is_active: 1<br/>";
    			*/
    			
    			// Rule creation
    			$newRule = new Rules();
    			$newRule->setAction($em_upv6->getRepository('App\Entity\Upv6\Actions')->findOneById($action_target));
    			$newRule->setName($action->getInternalName());
    			$newRule->setIsActive(true);
    			$newRule->setRuleType(1);
    			$newRule->setRule($rule);
    			$newRule->setPriorityLevel(0);
    			
    			// Event Has Rule creation
    			$newEventHasRule = new EventHasRule();
    			$newEventHasRule->setAction($em_upv6->getRepository('App\Entity\Upv6\Actions')->findOneById($event_source));
    			$newEventHasRule->setRule($newRule);
    			$newEventHasRule->setOverride(false);
    			$newEventHasRule->setForced(false);
    			$newEventHasRule->setSaveVariable(false);
    			$newEventHasRule->setIsActive(true);
    			
    			if($building_source != -1) {
    				$newEventHasRule->setBuilding($em_upv6->getRepository('App\Entity\Upv6\Buildings')->findOneById($building_source));
    			}
    			if($floor_source != -1) {
    				$newEventHasRule->setFloor($em_upv6->getRepository('App\Entity\Upv6\Floors')->findOneById($floor_source));
    			}
    			if($roomType_source != -1) {
    				$newEventHasRule->setRoomType($em_upv6->getRepository('App\Entity\Upv6\RoomTypes')->findOneById($roomType_source));
    			}
    			if($room_source != -1) {
    				$newEventHasRule->setRoom($em_upv6->getRepository('App\Entity\Upv6\Rooms')->findOneById($room_source));
    			}
    			if($category_source != -1) {
    				$newEventHasRule->setCategory($em_upv6->getRepository('App\Entity\Upv6\Categories')->findOneById($category_source));
    			}
    			if($family_source != -1) {
    				$newEventHasRule->setFamily($em_upv6->getRepository('App\Entity\Upv6\Families')->findOneById($family_source));
    			}
    			if($device_source != -1) {
    				$newEventHasRule->setDevice($em_upv6->getRepository('App\Entity\Upv6\Devices')->findOneById($device_source));
    			}
    	
    			// Persist
    			$em_upv6->persist($newRule);
    			$em_upv6->persist($newEventHasRule);
    			$em_upv6->flush();
    			
    			$message = $this->translator->trans('msg.ittt_added');
    			$this->get('session')->getFlashBag()->add('ok', $message);
    	
    			return $this->redirect($this->generateUrl('iot6_SmartItBundle_Ittt'));
    		}
    	}
    	
    	return $this->render('smartit/ittt.html.twig');
    }
    
    /*---------------- Rules --------------- */
    
    public function rules($pageActive, $pageInactive)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	$em_gui = $this->getDoctrine()->getManager("gui");
    	
    	$user = $this->container->get('security.token_storage')->getToken()->getUser();
    	$nbActivesRulesProPage = $em_gui	->getRepository('App\Entity\Gui\ConfigParam')
    										->getParamValue('nbActivesRules', $user, $em_gui);
    	
    	$nbInactiveRulesProPage = $em_gui	->getRepository('App\Entity\Gui\ConfigParam')
    										->getParamValue('nbInactivesRules', $user, $em_gui);
    	
    	$activesRules = $em_upv6	->getRepository('App\Entity\Upv6\Rules')
    								->getRules($nbActivesRulesProPage, $pageActive, true);
    	
    	$inactivesRules = $em_upv6	->getRepository('App\Entity\Upv6\Rules')
    								->getRules($nbInactiveRulesProPage, $pageInactive, false);
    	
    	$data['activesRules']		= $activesRules;
    	$data['inactivesRules']		= $inactivesRules;
    	$data['pageActive'] 		= $pageActive;
    	$data['pageInactive'] 		= $pageInactive;
    	$data['nbPagesActives']		= ceil(count($activesRules)/$nbActivesRulesProPage);
    	$data['nbPagesInactives']	= ceil(count($inactivesRules)/$nbInactiveRulesProPage);
    	$data['nbRulesActives']		= count($activesRules);
    	$data['nbRulesInactives']	= count($inactivesRules);
    	$data['nbActivesProPage']	= $nbActivesRulesProPage;
    	$data['nbInactivesProPage']	= $nbInactiveRulesProPage;
    	
    	return $this->render('smartit/rules.html.twig', $data);
    }
    
    public function rulesAdd(Request $request)
    {
    	$rule = new Rules();
    	$form = $this->createForm(RulesType::class, $rule);
    

    	if ($request->getMethod() == 'POST')
    	{
    		$form->handleRequest($request);
    
    		if ($form->isValid())
    		{
    			$rule->setUpdatedAt(new \DateTime('NOW'));
    			$rule->setPriorityLevel(1);
    			 
    			$em_upv6 = $this->getDoctrine()->getManager("upv6");
    			$em_upv6->persist($rule);
    			$em_upv6->flush();
    			
    			$message = $this->translator->trans('msg.rule_added');
    			$this->get('session')->getFlashBag()->add('ok', $message);
    			
    			return $this->redirect($this->generateUrl('iot6_SmartItBundle_RulesManager'));
    		}
    	}
    
    	$data['backUrl'] = $request->server->get("HTTP_REFERER");
    	$data['rule'] = $rule;
    	$data['form'] = $form->createView();
    
    	return $this->render('smartit/rulesAdd.html.twig', $data);
    }
    
    public function rulesActDesact(Request $request,Rules $rule)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	
    	$currentState = $rule->getIsActive();
    	$rule->setIsActive(!$currentState);
    	
    	$em_upv6->flush();
    	
    	$trsl = $this->translator;
    	
    	$message = ($rule->getIsActive()) ? $trsl->trans('msg.rule_activated') : $trsl->trans('msg.rule_deactivated');
    	$this->get('session')->getFlashBag()->add('ok', $message);
    	
    	$referer = $request->headers->get('referer');
    	return $this->redirect($referer);
    }
    
    public function rulesEdit(Request $request,Rules $rule)
    {
    	$form = $this->createForm(RulesType::class, $rule);
    	 

    	if ($request->getMethod() == 'POST')
    	{
    		$form->handleRequest($request);
    
    		if ($form->isValid())
    		{
    			$rule->setUpdatedAt(new \DateTime('NOW'));
    			 
    			$em_upv6 = $this->getDoctrine()->getManager("upv6");
    			$em_upv6->persist($rule);
    			$em_upv6->flush();
    			
    			$message = $this->translator->trans('msg.rule_edited');
    			$this->get('session')->getFlashBag()->add('ok', $message);
    			
    			//return $this->redirect($request->get('backUrl'));
				return $this->redirectToRoute('iot6_SmartItBundle_RulesManager');

    		}
    	}
    	
    	$data['backUrl'] = $request->server->get("HTTP_REFERER");
    	$data['rule'] = $rule;
    	$data['form'] = $form->createView();
    	 
    	return $this->render('smartit/rulesEdit.html.twig', $data);
    }
    
    public function rulesDelete(Rules $rule,Request $request)
    {
    	try {
    		$em_upv6 = $this->getDoctrine()->getManager("upv6");
    		$em_upv6->remove($rule);
    		$em_upv6->flush();
    		
    		$message = $this->translator->trans('msg.rule_deleted');
    		$this->get('session')->getFlashBag()->add('ok', $message);
    	}
    	catch(\Exception $e) {
    		$message = $this->translator->trans('error.rule_delete');
    		$this->get('session')->getFlashBag()->add('ko', $message);
    	}
    	
    	$referer = $request->headers->get('referer');
    	return $this->redirect($referer);
    }
    
    /*---------------- Scheduler --------------- */
    
    public function scheduler($regular, $punctual)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	$em_gui = $this->getDoctrine()->getManager("gui");
    	$user = $this->container->get('security.token_storage')->getToken()->getUser();
    	
    	$nbRegularSchedulesProPage = $em_gui	->getRepository('App\Entity\Gui\ConfigParam')
    											->getParamValue('nbRegularSchedulers', $user, $em_gui);
    	
    	$nbPunctualSchedulesProPage = $em_gui	->getRepository('App\Entity\Gui\ConfigParam')
    											->getParamValue('nbPonctualSchedulers', $user, $em_gui);
    	
    	$regularSchedules = $em_upv6	->getRepository('App\Entity\Upv6\Schedules')
							    		->getSchedules($nbRegularSchedulesProPage, $regular, true);
    	 
    	$punctualSchedules = $em_upv6	->getRepository('App\Entity\Upv6\Schedules')
							    		->getSchedules($nbPunctualSchedulesProPage, $punctual, false);
    	
    	$data['regularSchedules']		= $regularSchedules;
    	$data['punctualSchedules']		= $punctualSchedules;
    	$data['pageRegular'] 			= $regular;
    	$data['pagePunctual'] 			= $punctual;
    	$data['nbPagesRegular']			= ceil(count($regularSchedules)/$nbRegularSchedulesProPage);
    	$data['nbPagesPunctual']		= ceil(count($punctualSchedules)/$nbPunctualSchedulesProPage);
    	$data['nbScheduleRegular']		= count($regularSchedules);
    	$data['nbSchedulePunctual']		= count($punctualSchedules);
    	$data['nbRegularProPage']		= $nbRegularSchedulesProPage;
    	$data['nbPunctualProPage']		= $nbPunctualSchedulesProPage;
    	
    	//var_dump($regularSchedules);
    	
    	/*
    	$cron = CronExpression::factory('1 2 3 4 * 2014');
    	var_dump($cron->getNextRunDate()->format('Y-m-d H:i:s'));
    	*/
    	
    	return $this->render('smartit/scheduler.html.twig', $data);
    }
    
    public function scheduleActDesact(Schedules $schedule, Request $request)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	 
    	$currentState = $schedule->getIsActive();
    	$schedule->setIsActive(!$currentState);
    	 
    	$em_upv6->flush();
    	
    	$trsl = $this->translator;
    	
    	$message = ($schedule->getIsActive()) ? $trsl->trans('msg.schedule_activated') : $trsl->trans('msg.schedule_deactivated');
    	$this->get('session')->getFlashBag()->add('ok', $message);
    	 
    	$referer = $request->headers->get('referer');
    	return $this->redirect($referer);
    }
    
    public function schedulesAdd(Request $request)
    {
    	$schedule = new Schedules();
    	$form = $this->createForm(SchedulesType::class, $schedule);
    

    	if ($request->getMethod() == 'POST')
    	{
    		$form->handlerequest($request);
    
    		if ($form->isValid())
    		{
    			$em_upv6 = $this->getDoctrine()->getManager("upv6");
    			$em_upv6->persist($schedule);
    			$em_upv6->flush();

			$schedule_id = $schedule->getId();
			$action = 'NEW';
			
			$em_gui = $this->getDoctrine()->getManager("gui");
			$webserviceParam = $em_gui->getRepository('App\Entity\Gui\WebserviceParam')->findOneByParam('kernelUrl');
			    	
			$em_upv6 = $this->getDoctrine()->getManager("upv6");
			$setting = $em_upv6->getRepository('App\Entity\Upv6\Settings')->findOneById(1);

			// Session
			$session = $request->getSession();
			$session_id = $session->getId();
			    	
			$url = WebserviceParam::getScheduleUrl($webserviceParam->getValue(), $setting->getKernelSharedKey(), $schedule_id, $action, $session_id);
			    	
			$response = WebserviceParam::file_get_contents_curl($url);
    			
    			$message = $this->translator->trans('msg.schedule_added');
    			$this->get('session')->getFlashBag()->add('ok', $message);
    
    			return $this->redirect($this->generateUrl('iot6_SmartItBundle_Scheduler'));
    		}
    	}
    	
    	$data['form'] = $form->createView();
    
    	return $this->render('smartit/schedulerAdd.html.twig', $data);
    }
    
    public function schedulesEdit(Request $request,Schedules $schedule)
    {
    	$form = $this->createForm(SchedulesType::class, $schedule);
    

    	if ($request->getMethod() == 'POST')
    	{
    		$form->handlerequest($request);
    
    		if ($form->isValid())
    		{
    			$em_upv6 = $this->getDoctrine()->getManager("upv6");
    			$em_upv6->persist($schedule);
    			$em_upv6->flush();

			$schedule_id = $schedule->getId();
			$action = 'EDIT';
			
			$em_gui = $this->getDoctrine()->getManager("gui");
			$webserviceParam = $em_gui->getRepository('App\Entity\Gui\WebserviceParam')->findOneByParam('kernelUrl');
			    	
			$em_upv6 = $this->getDoctrine()->getManager("upv6");
			$setting = $em_upv6->getRepository('App\Entity\Upv6\Settings')->findOneById(1);

			// Session
			$session = $request->getSession();
			$session_id = $session->getId();			
			    	
			$url = WebserviceParam::getScheduleUrl($webserviceParam->getValue(), $setting->getKernelSharedKey(), $schedule_id, $action, $session_id);
			    	
			$response = WebserviceParam::file_get_contents_curl($url);
    			
    			$message = $this->translator->trans('msg.schedule_edited');
    			$this->get('session')->getFlashBag()->add('ok', $message);
    			
    			return $this->redirect($request->get('backUrl'));
    		}
    	}
    	
    	$data['backUrl'] = $request->server->get("HTTP_REFERER");
    	$data['schedule'] = $schedule;
    	$data['form'] = $form->createView();
    
    	return $this->render('smartit/schedulerEdit.html.twig', $data);
    }
    
    public function schedulesDelete(Schedules $schedule, Request $request)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	$em_upv6->remove($schedule);
    	$em_upv6->flush();

	$schedule_id = $schedule->getId();
	$action = 'DELETE';
			
	$em_gui = $this->getDoctrine()->getManager("gui");
	$webserviceParam = $em_gui->getRepository('App\Entity\Gui\WebserviceParam')->findOneByParam('kernelUrl');
			    	
	$em_upv6 = $this->getDoctrine()->getManager("upv6");
	$setting = $em_upv6->getRepository('App\Entity\Upv6\Settings')->findOneById(1);

	// Session
	$session = $request->getSession();
	$session_id = $session->getId();
			    	
	$url = WebserviceParam::getScheduleUrl($webserviceParam->getValue(), $setting->getKernelSharedKey(), $schedule_id, $action, $session_id);
			    	
	$response = WebserviceParam::file_get_contents_curl($url);
    	
    	$message = $this->translator->trans('msg.schedule_deleted');
    	$this->get('session')->getFlashBag()->add('ok', $message);
    	
    	return $this->redirect($this->generateUrl('iot6_SmartItBundle_Scheduler'));
    }
    
    /*---------------- Triggers --------------- */
    
    public function triggers($pageActive, $pageInactive)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	$em_gui = $this->getDoctrine()->getManager("gui");
    	
    	$user = $this->container->get('security.token_storage')->getToken()->getUser();
    	
    	
    	$nbActiveTriggerProPage = $em_gui	->getRepository('App\Entity\Gui\ConfigParam')
    										->getParamValue('nbActivesTriggers', $user, $em_gui);
    	 
    	$nbInactiveTriggerProPage = $em_gui->getRepository('App\Entity\Gui\ConfigParam')
    										->getParamValue('nbInactivesTriggers', $user, $em_gui);
    	 
    	$activesTriggers = $em_upv6		->getRepository('App\Entity\Upv6\EventHasRule')
    									->getTriggers($nbActiveTriggerProPage, $pageActive, true);
    
    	$inactivesTriggers = $em_upv6	->getRepository('App\Entity\Upv6\EventHasRule')
    									->getTriggers($nbInactiveTriggerProPage, $pageInactive, false);
    	
    	$data['activesTriggers']		= $activesTriggers;
    	$data['inactivesTriggers']		= $inactivesTriggers;
    	$data['pageActive'] 			= $pageActive;
    	$data['pageInactive'] 			= $pageInactive;
    	$data['nbPagesActives']			= ceil(count($activesTriggers)/$nbActiveTriggerProPage);
    	$data['nbPagesInactives']		= ceil(count($inactivesTriggers)/$nbInactiveTriggerProPage);
    	$data['nbTriggersActives']		= count($activesTriggers);
    	$data['nbTriggersInactives']	= count($inactivesTriggers);
    	$data['nbActivesProPage']		= $nbActiveTriggerProPage;
    	$data['nbInactivesProPage']		= $nbInactiveTriggerProPage;
    	 
    	return $this->render('smartit/triggers.html.twig', $data);
    }
    
    public function triggersActDesact(Request $request,EventHasRule $eventHasRule)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	
    	$currentState = $eventHasRule->getIsActive();
    	$eventHasRule->setIsActive(!$currentState);
    	
    	$em_upv6->flush();
    	
    	$trsl = $this->translator;
    	
    	$message = ($eventHasRule->getIsActive()) ? $trsl->trans('msg.trigger_activated') : $trsl->trans('msg.trigger_deactivated');
    	$this->get('session')->getFlashBag()->add('ok', $message);
    	
    	$referer = $request->headers->get('referer');
    	return $this->redirect($referer);
    }
    
    public function triggersAdd(Request $request)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	 
    	$error = null;
    	$message = "";

    	if ($request->getMethod() == 'POST')
    	{
    		$idEvent 	= $request->get('event');
    		$idBuilding = $request->get('buildings');
    		$idFloor 	= $request->get('floors');
    		$idRoomType = $request->get('roomTypes');
    		$idRoom 	= $request->get('rooms');
    		$idCategory = $request->get('categories');
    		$idFamily 	= $request->get('families');
    		$idDevice 	= $request->get('devices');
    		$idRule 	= $request->get('rules');
    	
    		if($idEvent == -1 || is_null($idEvent)) {
    			$error = $this->translator->trans('error.select_one_event');
    			$this->get('session')->getFlashBag()->add('ko', $message);
    		}
    	
    		if($idRule == -1 || is_null($idRule)) {
    			$error = $this->translator->trans('error.select_one_rule');
    			$this->get('session')->getFlashBag()->add('ko', $message);
    		}
    	
    		if($idBuilding == -1 && $idFloor == -1 && $idRoomType == -1 && $idRoom == -1 && $idCategory == -1 && $idFamily == -1 && $idDevice == -1 ) {
    			$error = $this->translator->trans('error.select_one_source');
    			$this->get('session')->getFlashBag()->add('ko', $message);
    		}
    	
    		if(is_null($error))
    		{
    			$event 		= $em_upv6->getRepository('App\Entity\Upv6\Actions')->find($idEvent);
    			$building 	= $em_upv6->getRepository('App\Entity\Upv6\Buildings')->find($idBuilding);
    			$floor 		= $em_upv6->getRepository('App\Entity\Upv6\Floors')->find($idFloor);
    			$roomType 	= $em_upv6->getRepository('App\Entity\Upv6\RoomTypes')->find($idRoomType);
    			$room 		= $em_upv6->getRepository('App\Entity\Upv6\Rooms')->find($idRoom);
    			$category 	= $em_upv6->getRepository('App\Entity\Upv6\Categories')->find($idCategory);
    			$family 	= $em_upv6->getRepository('App\Entity\Upv6\Families')->find($idFamily);
    			$device 	= $em_upv6->getRepository('App\Entity\Upv6\Devices')->find($idDevice);
    			$rule 		= $em_upv6->getRepository('App\Entity\Upv6\Rules')->find($idRule);
    			 
    	
    			$saveVariable 	= ($request->get('saveVariable') == 1) ? true : false;
    			$override 		= ($request->get('override') == 1) ? true : false;
    			$forced 		= ($request->get('forced') == 1) ? true : false;
    			$isActive 		= ($request->get('isActive') == 1) ? true : false;
    			$comment 		= $request->get('comments');
    			
    			$trigger = new EventHasRule();
    			
    			$trigger->setAction($event);
    			$trigger->setRule($rule);
    			$trigger->setBuilding($building);
    			$trigger->setFloor($floor);
    			$trigger->setRoomType($roomType);
    			$trigger->setRoom($room);
    			$trigger->setCategory($category);
    			$trigger->setFamily($family);
    			$trigger->setDevice($device);
    			$trigger->setSaveVariable($saveVariable);
    			$trigger->setOverride($override);
    			$trigger->setForced($forced);
    			$trigger->setIsActive($isActive);
    			$trigger->setComment($comment);
    			 
    			$em_upv6->persist($trigger);
    			$em_upv6->flush();
    			
    			$message = $this->translator->trans('msg.trigger_added');
    			$this->get('session')->getFlashBag()->add('ok', $message);
    	
    			return $this->redirect($this->generateUrl('iot6_SmartItBundle_Triggers'));
    		}	
    	}
    	 
    	$actions = $em_upv6->getRepository('App\Entity\Upv6\Actions')->findBy(array(), array('internalName' => 'ASC'));
    	$rules = $em_upv6->getRepository('App\Entity\Upv6\Rules')->findBy(array(), array('name' => 'ASC'));
    	 
    	$data["actions"] = $actions;
    	$data["rules"] = $rules;
    	 
    	return $this->render('smartit/triggersAdd.html.twig', $data);
    }
    
    public function triggersEdit(Request $request,EventHasRule $trigger)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	
		$error = null;
		$message = null;
    	

    	if ($request->getMethod() == 'POST')
    	{

    		
    		$idEvent 	= $request->get('event');
    		$idBuilding = $request->get('buildings');
    		$idFloor 	= $request->get('floors');
    		$idRoomType = $request->get('roomTypes');
    		$idRoom 	= $request->get('rooms');
    		$idCategory = $request->get('categories');
    		$idFamily 	= $request->get('families');
    		$idDevice 	= $request->get('devices');
    		$idRule 	= $request->get('rules');
    		
    		if($idEvent == -1 || is_null($idEvent)) {
				$error = $this->translator->trans('error.select_one_event');
				$message = "One event must be selected";
    			$this->get('session')->getFlashBag()->add('ko', $message);
    		}
    	
    		if($idRule == -1 || is_null($idRule)) {
				$error = $this->translator->trans('error.select_one_rule');
				$message = "One rule must be selected";
    			$this->get('session')->getFlashBag()->add('ko', $message);
    		}
    	
    		if($idBuilding == -1 && $idFloor == -1 && $idRoomType == -1 && $idRoom == -1 && $idCategory == -1 && $idFamily == -1 && $idDevice == -1 ) {
				$error = $this->translator->trans('error.select_one_source');
				$message = "Select a source, cannot be all";
    			$this->get('session')->getFlashBag()->add('ko', $message);
    		}
    		
    		if(is_null($error))
    		{
	    		$event 		= $em_upv6->getRepository('App\Entity\Upv6\Actions')->find($idEvent);
	    		$building 	= $em_upv6->getRepository('App\Entity\Upv6\Buildings')->find($idBuilding);
	    		$floor 		= $em_upv6->getRepository('App\Entity\Upv6\Floors')->find($idFloor);
	    		$roomType 	= $em_upv6->getRepository('App\Entity\Upv6\RoomTypes')->find($idRoomType);
	    		$room 		= $em_upv6->getRepository('App\Entity\Upv6\Rooms')->find($idRoom);
	    		$category 	= $em_upv6->getRepository('App\Entity\Upv6\Categories')->find($idCategory);
	    		$family 	= $em_upv6->getRepository('App\Entity\Upv6\Families')->find($idFamily);
	    		$device 	= $em_upv6->getRepository('App\Entity\Upv6\Devices')->find($idDevice);
	    		$rule 		= $em_upv6->getRepository('App\Entity\Upv6\Rules')->find($idRule);
	    		
	
	    		$saveVariable 	= ($request->get('saveVariable') == 1) ? true : false;
	    		$override 		= ($request->get('override') == 1) ? true : false;
	    		$forced 		= ($request->get('forced') == 1) ? true : false;
	    		$isActive 		= ($request->get('isActive') == 1) ? true : false;
	    		$comment 		= $request->get('comments');
	    		
	    		if($event != null) {
	    			$trigger->setAction($event);
	    		}
	    		if($rule != null) {
	    			$trigger->setRule($rule);
	    		}
	    		
    			$trigger->setBuilding($building);
    			$trigger->setFloor($floor);
    			$trigger->setRoomType($roomType);
    			$trigger->setRoom($room);
    			$trigger->setCategory($category);
    			$trigger->setFamily($family);
    			$trigger->setDevice($device);
	    		
	    		$trigger->setSaveVariable($saveVariable);
	    		$trigger->setOverride($override);
	    		$trigger->setForced($forced);
	    		$trigger->setIsActive($isActive);
	    		$trigger->setComment($comment);
	    		
				$em_upv6->persist($trigger);
				$em_upv6->flush();
				
				$message = $this->translator->trans('msg.trigger_edited');
				$this->get('session')->getFlashBag()->add('ok', $message);
				
	    		return $this->redirect($this->generateUrl('iot6_SmartItBundle_Triggers'));
    		}
    	}
    	
    	$actions = $em_upv6->getRepository('App\Entity\Upv6\Actions')->findBy(array(), array('internalName' => 'ASC'));
    	$rules = $em_upv6->getRepository('App\Entity\Upv6\Rules')->findBy(array(), array('name' => 'ASC'));
    	
    	$data["actions"] = $actions;
    	$data["rules"] = $rules;
    	$data["object"] = $trigger;
    	
    	return $this->render('smartit/triggersEdit.html.twig', $data);
    }
    
    public function triggersDelete(Request $request,EventHasRule $eventHasRule)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	$em_upv6->remove($eventHasRule);
    	$em_upv6->flush();
    	
    	$message = $this->translator->trans('msg.trigger_deleted');
    	$this->get('session')->getFlashBag()->add('ok', $message);
    	
    	$referer = $request->headers->get('referer');
    	return $this->redirect($referer);
    }
    
    // AJAX
    public function getsForDevices(Request $request)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	
    	$kind = $request->get('kind');
    	$strListDeviceId = $request->get('devicesList');
    	$devicesId = explode(',', $strListDeviceId);
    	
    	$json = array();
    
    	// events
    	if($kind == 0) {
    		$actions = $em_upv6	->getRepository('App\Entity\Upv6\EventFilters')
    							->findEventsForDevices($devicesId, $kind);
    		
    		$json[-1] = $this->translator->trans('msg.select_event');
    	}
    	// actions
    	else if($kind == 1) {
    		$actions = $em_upv6	->getRepository('App\Entity\Upv6\Devices')
    							->findActionsForDevices($devicesId, $kind);
    		
    		$json[-1] = $this->translator->trans('msg.select_action');
    	}
    	
    	foreach ($actions as $action) {
    		$json[$action['id']][] = utf8_encode($action['internalName']);
    	}
    	
    	return new Response(json_encode($json));
    }
    
    public function getParametersFor(Request $request)
    {
    	$em_upv6 = $this->getDoctrine()->getManager("upv6");
    	 
    	$actionId = $request->get('actionId');
    	$default = $request->get('default');
    	 
    	$json = array();
    
    	$parameters = $em_upv6	->getRepository('App\Entity\Upv6\Parameters')
    							->findBy(array('action' => $actionId), array('position' => 'ASC'));
    	
    	if($default == 1) {
    		$json[-1] = $this->translator->trans('msg.select_param');
    	}
    
    	foreach ($parameters as $parameter) {
    		$json[$parameter->getId()][] = utf8_encode($parameter->getName());
    	}
    	 
    	return new Response(json_encode($json));
    }

}
