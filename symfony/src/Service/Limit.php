<?php


namespace App\Service;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Gui\City;

class Limit extends AbstractController
{

    public function isDeviceAvailable(): bool
    {
        // take number of devices added for city (interact devices)
        // take number of devices allowed in purchases/product
        // return if reached or not
        $emGui = $this->getDoctrine()->getManager("gui");
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $city = $user->getCity();
        $devices = $city->getDevices();

    }


    public function isUserAvailable(): bool
    {

    }

    public function isDurationAvailable(): bool
    {

    }






}