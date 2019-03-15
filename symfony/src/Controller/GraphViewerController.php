<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
//use iot6\InteractBundle\VariablesHistoryRepository;

class GraphViewerController extends AbstractController
{
    public function index()
    {
		return $this->render('graph/viewer/index.html.twig');
    }
	
	public function viewer()
    {
		return $this->render('graph/viewer/viewer.html.twig');
    }
	
	public function getGraphDataForVariables()
    {
		//get parameters
		$devices_id = $this->getRequest()->query->get('devices_id');
		$variables_name = $this->getRequest()->query->get('variables_name');
		$from =  $this->getRequest()->query->get('from');
		$to =  $this->getRequest()->query->get('to');
		$granularity = filter_var($this->getRequest()->query->get('granularity'), FILTER_VALIDATE_INT, array("options"=>array("default"=>0, "min_range"=>0, "max_range"=>5)));
		
		//get list of devices and variables (separated by comma)
		$array_devices_id = explode(",", $devices_id);
		$array_variables_name = explode(",", $variables_name);
		
		//global variables
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$nbItems = 200;
		$json = array();
		$u;
		$v;
		$returned = 0;
		
		//for regroup
		$min=0;
		$max=0;
		$total=0;
		$nbElements=0;
		$groupDate=null;
		
		//go through each device/variable
		for($j=0; $j < sizeof($array_devices_id); $j++) {
			$page = 1;
			$returned = 0;
			
			//get all variables history for the current device
			//to avoid to have to much record at the same time, we paginate the results
			do {
				$returned = 0;
				
				$em_upv6->clear(); //to avoid memory leak
				//gc_collect_cycles(); //could be used if memory leaks persists		
				
				$listVariablesPaginator = $em_upv6->getRepository('App\Entity\Upv6\VariablesHistory')->findVariablesForGraph($array_devices_id[$j], $array_variables_name[$j], $from, $to, $nbItems, $page);

				foreach($listVariablesPaginator as $varHistory) {
					++$returned;
					if($granularity == 0) { //no regroup, add each value
						$u = intval($varHistory->getCreatedAt()->format('U'));
						if(array_key_exists($u, $json) === false) {
							$json[$u] = array(sizeof($array_devices_id)+1);
							$json[$u][0] = $u;
							for($k=1;$k<sizeof($array_devices_id)+1;$k++)
								$json[$u][$k] = null;
						}
						$json[$u][$j+1] = floatval($varHistory->getStringValue());
					} else {
						//need to regroup values (min, max, avg)
						//is same regroup date as previous values ?
						if($groupDate == $this->getStartPeriodForGranularity($varHistory->getCreatedAt(), $granularity)) {
						//if($this->isSamePeriodForGranularity($groupDate, $varHistory->getCreatedAt(), $granularity)) {
							//same period - increase/calculate each values
							$v = floatval($varHistory->getStringValue());
							if($v < $min) $min = $v;
							if($v > $max) $max = $v;
							$total += $v;
							$nbElements++;
						} else {
							//add values to array if necessary
							if($nbElements > 0) {
								//$u = intval($varHistory->getCreatedAt()->format('U'));
								$u = $groupDate->format('U');
								if(array_key_exists($u, $json) === false) {
									$json[$u] = array(sizeof($array_devices_id)+1);
									$json[$u][0] = $u;
									for($k=1;$k<sizeof($array_devices_id)+1;$k++)
										$json[$u][$k] = null;
								}
								$json[$u][$j+1] = array(3);
								$json[$u][$j+1][0] = $min;
								$json[$u][$j+1][1] = $total/$nbElements;
								$json[$u][$j+1][2] = $max;
							}
							
							//set the new values
							$v = floatval($varHistory->getStringValue());
							$min = $v;
							$max = $v;
							$total = $v;
							$nbElements = 1;
							$groupDate = $this->getStartPeriodForGranularity($varHistory->getCreatedAt(), $granularity);
						}
					}
				}
			
				$page++;
			}
			while($returned >= $nbItems);
			
			//dump remaining values if necessary
			if($granularity !== 0 && $nbElements > 0) {
				//$u = intval($varHistory->getCreatedAt()->format('U'));
				$u = $groupDate->format('U');
				if(array_key_exists($u, $json) === false) {
					$json[$u] = array(sizeof($array_devices_id)+1);
					$json[$u][0] = $u;
					for($k=1;$k<sizeof($array_devices_id)+1;$k++)
						$json[$u][$k] = null;
				}
				
				$json[$u][$j+1] = array(3);
				$json[$u][$j+1][0] = $min;
				$json[$u][$j+1][1] = $total/$nbElements;
				$json[$u][$j+1][2] = $max;
			}
			
			//reset variables for next variable
			$total = 0;
			$nbElements = 0;
		}
		
		//sort the array to have it ordered by the datetime (= key)
		ksort($json);
		
		//remove keys in order to have a consecutive numbered array
		$out = array_values($json);
		
		/*print(sizeof($out));
		print("<br>");
		print(json_encode($out));
		//print_r($out);
		die();*/
		
		return new Response(json_encode($out));
		//return $this->render('iot6GraphBundle:Viewer:index.html.twig');
	}
	
	
	//return for a datetime, the first datetime corresponding to the regroupement filter choosed (granularity)
	//i.e. : if we choose to group by day, this function will take '05 august 2014, 14h35' and return '05-08-2014-00h00'
	//it is used to compare if 2 dates are on the same period depending on the granularity
	//i.e. : if we choose to regroup by minutes, '2014-05-01 14:31:25' is true with '2014-05-01 14:31:59' and false with '2015-05-01 14:31:25'
	function getStartPeriodForGranularity($date, $granularity) {
		$d = clone $date;
		
		switch($granularity) {
			case 1: //minute
				$d->setTime($d->format('H'),$d->format('i'),0);
				break;
			case 2: //hour
				$d->setTime($d->format('H'),0,0);
				break;
			case 3: //day
				$d->setTime(0,0,0);
				break;
			case 4: //month
				$d->setTime(0,0,0);
				$d->setDate($d->format('Y'), $d->format('m'), 1);
				break;
			case 5: //year
				$d->setTime(0,0,0);
				$d->setDate($d->format('Y'), 1, 1);
				break;
		}
		//print_r($date);print("<br>");print_r($d);print("<br>");die();
		return $d;
	}
	
	/*function isSamePeriodForGranularity($date1,$date2,$granularity) {
		if(!isset($date1) ||  isset($date)) return false;
	
		//calculate difference between 2 dates (true = force to be positive)
		//$diff = $date1->diff($date2, true);
		$diff = $date1->getTimestamp() - $date2->getTimestamp();
		if($diff < 0) $diff *= -1; //make sure it's positive
		
		switch($granularity) {
			case 1: //day
				if($diff < 86401) //86400 seconds = 1 day
					return true;
				break;
		}
		
		return false;
	}*/
	
	///////BACKUP WITHOUT GRANULARITY
	/*public function getGraphDataForVariables()
    {
		//get parameters
		$devices_id = $this->getRequest()->query->get('devices_id');
		$variables_name = $this->getRequest()->query->get('variables_name');
		$from =  $this->getRequest()->query->get('from');
		$to =  $this->getRequest()->query->get('to');
		$granularity = filter_var($this->getRequest()->query->get('granularity'), FILTER_VALIDATE_INT, array("options"=>array("default"=>0, "min_range"=>0, "max_range"=>5)));
		
		
		//get list of devices and variables (separated by comma)
		$array_devices_id = explode(",", $devices_id);
		$array_variables_name = explode(",", $variables_name);
		
		//global variables
		$em_upv6 = $this->getDoctrine()->getManager("upv6");
		$nbItems = 500;
		$json = array();
		$u;

		//go through each device/variable
		for($j=0; $j < sizeof($array_devices_id); $j++) {
			$page = 1;
			$returned = 0;
			
			//get all variables history for the current device
			//to avoid to have to much record at the same time, we paginate the results
			do {
				$listVariablesPaginator = $em_upv6->getRepository('iot6InteractBundle:VariablesHistory')->findVariablesForGraph($array_devices_id[$j], $array_variables_name[$j], $from, $to, $nbItems, $page);

				foreach($listVariablesPaginator as $varHistory) {
					$u = intval($varHistory->getCreatedAt()->format('U'));
					if(array_key_exists($u, $json) === false) {
						$json[$u] = array(sizeof($array_devices_id));
						$json[$u][0] = $u;
					}
					$json[$u][$j+1] = floatval($varHistory->getStringValue());
					++$returned;
				}
			
				$page++;
				
				$returned = 0;
			}
			while($returned >= $nbItems);
		}

		//remove keys in order to have a consecutive numbered array
		$out = array_values($json);
		return new Response(json_encode($out));
		//return $this->render('iot6GraphBundle:Viewer:index.html.twig');
	}*/
}
