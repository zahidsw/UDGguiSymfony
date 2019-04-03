<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class PurchaseController extends AbstractController
{
    /**
     * @Route("/purchase", name="purchase",methods={"GET","HEAD"})
     */
    public function index()
    {
        $response = new JsonResponse(array('ping' => 'pong'));

        return $response;
    }

    /**
     * @Route("/purchase", name="purchase",methods={"POST"})
     */
    public function add()
    {
        $response = new JsonResponse(array('ping' => 'pongano'));

        return $response;
    }

    




}
