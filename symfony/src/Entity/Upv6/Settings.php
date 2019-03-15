<?php

namespace App\Entity\Upv6;

use Doctrine\ORM\Mapping as ORM;

/**
 * Settings
 *
 * @ORM\Table(name="settings")
 * @ORM\Entity
 */
class Settings
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
     * @ORM\Column(name="kernel_shared_key", type="string", length=45, nullable=false)
     */
    private $kernelSharedKey;



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
     * Set kernelSharedKey
     *
     * @param string $kernelSharedKey
     * @return Settings
     */
    public function setKernelSharedKey($kernelSharedKey)
    {
        $this->kernelSharedKey = $kernelSharedKey;
    
        return $this;
    }

    /**
     * Get kernelSharedKey
     *
     * @return string 
     */
    public function getKernelSharedKey()
    {
        return $this->kernelSharedKey;
    }
}