<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaginatorController extends AbstractController
{
    public function paginator($nbItems, $nbItemPerPage, $route, $nbPagesDisplayed, $currentPage, $arg)
    {
    	$args = array();
    	
    	$data['nbItems'] 			= $nbItems;
    	$data['nbItemPerPage'] 		= $nbItemPerPage;
    	$data['nbPages']			= ceil($nbItems/$nbItemPerPage);
    	$data['nbPagesDisplayed'] 	= $nbPagesDisplayed;
    	$data['currentPage'] 		= $currentPage;
    	$data['route'] 				= $route;
    	$data['arg']				= $arg;
    	
    	return $this->render('paginator/paginator.html.twig', $data);
    }
}
