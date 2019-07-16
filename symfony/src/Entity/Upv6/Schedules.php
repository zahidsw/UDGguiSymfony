<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;
use Cron\CronExpression;


/**
 * Schedules
 *
 * @ORM\Table(name="schedules")
 * @ORM\Entity(repositoryClass="App\Repository\SchedulesRepository")
 */
class Schedules
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Rules", fetch="EAGER")
     */
    private $rule;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Upv6\Targets", fetch="EAGER")
     */
    private $target;

    /**
     * @var string
     *
     * @ORM\Column(name="schedule_cron", type="string", length=20, nullable=false)
     */
    private $scheduleCron;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;
    
    /**
     * @var DateTime
     */
    private $nextRunDate;
    
    
    public function __construct()
    {
    	$this->setScheduleCron('* * * * * ? *');
    }
    
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
     * Set scheduleCron
     *
     * @param string $scheduleCron
     * @return Schedules
     */
    public function setScheduleCron($scheduleCron)
    {
        $this->scheduleCron = $scheduleCron;
    
        return $this;
    }

    /**
     * Get scheduleCron
     *
     * @return string 
     */
    public function getScheduleCron()
    {
        return $this->scheduleCron;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Schedules
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Schedules
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set rule
     *
     * @param \App\Entity\Upv6\Rules $rule
     * @return Schedules
     */
    public function setRule(\App\Entity\Upv6\Rules $rule)
    {
        $this->rule = $rule;
    
        return $this;
    }

    /**
     * Get rule
     *
     * @return \App\Entity\Upv6\Rules 
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * Set target
     *
     * @param \App\Entity\Upv6\Targets $target
     * @return Schedules
     */
    public function setTarget(\App\Entity\Upv6\Targets $target)
    {
        $this->target = $target;
    
        return $this;
    }

    /**
     * Get target
     *
     * @return \App\Entity\Upv6\Targets 
     */
    public function getTarget()
    {
        return $this->target;
    }
    
    /**
     * Get next run date
     *
     * @return \DateTime
     */
    public function getNextRunDate()
    {
    	$cron = $this->getScheduleCron();
    	
    	$tabCron = explode(' ', $cron);
    	array_shift($tabCron);
    	
    	$cron = implode(' ', $tabCron);
    	$cron = str_replace('?', '*', $cron);
    	
    	$cronExp = CronExpression::factory($cron);
    	
    	$nextRunDate = null;
    	
    	try {
    		$nextRunDate = $cronExp->getNextRunDate()->format('d-m-Y H:i:s');
    	}
    	catch(\Exception $e) {
    		$nextRunDate = null;
    	}
    	
    	return $nextRunDate;
    }
}