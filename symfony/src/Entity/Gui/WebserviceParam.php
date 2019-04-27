<?php

namespace App\Entity\Gui;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actions
 *
 * @ORM\Table(name="webservice_param")
 * @ORM\Entity
 */
class WebserviceParam
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="param", type="string", length=190, nullable=false, unique=true)
     */
    private $param;
    
    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=false)
     */
    private $value;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set param
     *
     * @param string $param
     * @return WebserviceParam
     */
    public function setParam($param)
    {
        $this->param = $param;
    
        return $this;
    }

    /**
     * Get param
     *
     * @return string 
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return WebserviceParam
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
    
    private static function getTimeStamp()
    {
    	return time();
    }
    
    private static function getToken($key, $action)
    {
    	return sha1($key . WebserviceParam::getTimeStamp() . $action);
    }
    
    public static function getActionUrl($kernelURL, $key, $action_id, $devices_ids, $session_id)
    {
    	$action = 'executeAction';
    	return $kernelURL . '/' . $action . '?action_id='. $action_id . '&device_id_list=' . $devices_ids . '&timestamp=' . WebserviceParam::getTimeStamp() . '&token=' . WebserviceParam::getToken($key, $action) . '&session_id=' . $session_id;
    }
    
    public static function getScenarioUrl($kernelURL, $key, $scenario_id, $target_id, $session_id)
    {
    	if(is_null($target_id))
    		$target_id = 1;
    	
    	$action = 'executeScenario';
    	
    	return $kernelURL . '/' . $action . '?scenario_id='. $scenario_id . '&target_id=' . $target_id . '&session_id=' . $session_id .  '&timestamp=' . WebserviceParam::getTimeStamp() . '&token=' . WebserviceParam::getToken($key, $action);
    }

    public static function getScheduleUrl($kernelURL, $key, $schedule_id, $actionSchedule, $session_id)
    {	
    	$action = 'editSchedules';
    	
    	return $kernelURL . '/' . $action . '?schedule_id='. $schedule_id . '&action='. $actionSchedule . '&timestamp=' . WebserviceParam::getTimeStamp() . '&token=' . WebserviceParam::getToken($key, $action) . '&session_id=' . $session_id;
    }
    
    public static function file_get_contents_curl($url)
    {
    	$ch = curl_init($url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Set curl to return the data instead of printing it to the browser.
    	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    	curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    	$data = curl_exec($ch);
    	
    	//var_dump(curl_getinfo($ch));
    	
    	curl_close($ch);
    	
    	return $data . "";
    }

    public static function sendUser($kernelURL, $key, $user, $session_id)
    {	
    	$action = 'sendUser';
    	
    	return $kernelURL . '/' . $action . '?user='. $user . '&session_id=' . $session_id . '&timestamp=' . WebserviceParam::getTimeStamp() . '&token=' . WebserviceParam::getToken($key, $action);
    }

    public static function validateDevice($kernelURL, $key, $device_id, $session_id)
    {	
    	$action = 'validateDevice';
    	
    	return $kernelURL . '/' . $action . '?device_id='. $device_id . '&session_id=' . $session_id . '&timestamp=' . WebserviceParam::getTimeStamp() . '&token=' . WebserviceParam::getToken($key, $action);
    }

    public static function validateRule($kernelURL, $key, $rule_id, $session_id)
    {	
    	$action = 'validateRule';
    	
    	return $kernelURL . '/' . $action . '?rule_id='. $rule_id . '&session_id=' . $session_id . '&timestamp=' . WebserviceParam::getTimeStamp() . '&token=' . WebserviceParam::getToken($key, $action);
    }
}
