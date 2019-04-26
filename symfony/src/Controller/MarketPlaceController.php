<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


class MarketPlaceController extends AbstractController
{

	

    public function __construct()
    {
		
    }
    

    public function list()
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
		$purchase = $user->getPurchases();
		// $city = $user->getCity();
		// $users = $city->getUsers();
		// $users = $users->getValues();
        // dd($purchase);
        $data ['purchases'] = $purchase;



    	return $this->render('marketplace/list.html.twig', $data);
    }
}