<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Gui\Product;


class FrontController extends AbstractController
{

	

    public function __construct()
    {
		
    }
    

    public function list()
    {

    	return $this->render('frontpage/front.html.twig');
    }


    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function customerSupport()
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $purchases = $user->getPurchases()->toArray();

        $em_gui = $this->getDoctrine()->getManager("gui");
        $products = $em_gui->getRepository("App\Entity\Gui\Product")->findAll();
        
        foreach($purchases as $purchase)
        {
            foreach($products as $product)
            {
                if(strtolower($purchase->getProductSku()) == strtolower($product->getsku()))
                {
                    $purchase->users = $product->getMaxUsers();
                    $purchase->devices = $product->getMaxDevices();
                    $purchase->duration = $product->getDuration();
                }
            }
        }

        $data ['purchases'] = $purchases;

    	return $this->render('frontpage/customerSupport.html.twig',$data);
    }

    public function discussion()
    {
        return $this->render('frontpage/discussion.html.twig');
    }

    public function contact()
    {
        return $this->render('frontpage/contact.html.twig');
    }

    public function service()
    {
        return $this->render('frontpage/service.html.twig');
    }

}