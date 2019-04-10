<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Gui\User;
use App\Entity\Gui\Purchase;
use Symfony\Component\HttpFoundation\Request;


class PurchaseController extends AbstractController
{
    /**
     * @Route("/purchase", name="verify",methods={"GET","HEAD"})
     */
    public function index()
    {
        $response = new JsonResponse(JsonResponse::HTTP_OK,JsonResponse::HTTP_OK);
        return $response; 
    }


    /**
     * @Route("/purchase", name="purchase",methods={"POST"})
     */
    public function add(Request $request)
    {
        try
        {
            $data = $this->decode($request->getContent());
            $data = $this->validate($data);

            $entityManager = $this->getDoctrine()->getManager("gui");
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['customer']['email']]);
           
            if($user == null) throw new \Exception("User not found");

            $purchase = new Purchase();
            $purchase->setTimestamp($data['timestamp']);
            $purchase->setServiceId($data['service_id']);
            $purchase->setServiceDescription($data['service_description']);
            $purchase->setRequestUrl($data['request_url']);
            $purchase->setOrderNumber($data['order_number']);
            $purchase->setProductSku($data['product']['sku']);
            $purchase->setProductName($data['product']['name']);
            $purchase->setProductQuantity($data['product']['quantity']);
            $purchase->setProductPrice($data['product']['price']);
            $purchase->setProductCurrency($data['product']['currency']);
            $purchase->setCallbackUrl($data['product']['fields']['callback_url']);
            $purchase->setUser($user);
            $entityManager->persist($purchase);
            $entityManager->flush();

        } catch (\Exception $e)
        {
            $response = array("error" => $e->getMessage());
            $response = new JsonResponse($response,JsonResponse::HTTP_BAD_REQUEST);
            return $response;
        }
            
        $response = array("customer" => $data['customer']['email'], "order number" => $data['order_number']);
        $response = new JsonResponse($response,JsonResponse::HTTP_CREATED);

        return $response;
    }

    
    public function validate(Array $data)
    {

        try
        {
            $email = $this->checkEmail($this->cleanInput($this->isEmpty($data['customer']['email'],'mail')));
            $orderNumber = $this->cleanInput($this->isEmpty($data['order_number'],'order number'));
            $serviceId = $this->cleanInput($this->isEmpty($data['service_id'],'service id'));
            $serviceDescription = $this->cleanInput($this->isEmpty($data['service_description'],'service description'));
            $timestamp = $this->cleanInput($this->isEmpty($data['timestamp'],'time stamp'));
            $requestUrl = $this->cleanInput($this->isEmpty($data['request_url'],'request url'));
            $productSku = $this->cleanInput($this->isEmpty($data['product']['sku'],'product sku'));
            $productName = $this->cleanInput($this->isEmpty($data['product']['name'],'product name'));
            $productQuantity = $this->cleanInput($this->isEmpty($data['product']['quantity'],'product quantity'));
            $productPrice = $this->cleanInput($this->isEmpty($data['product']['price'],'product price'));
            $productCurrency = $this->cleanInput($this->isEmpty($data['product']['currency'],'product currency'));
            $callbackUrl = $this->cleanInput($this->isEmpty($data['product']['fields']['callback_url'],'callback url'));

        } catch (\Exception $e)
        {
            throw $e;
        }
        
        return $data;
    }
    


    function isEmpty($data, $name)
    {
        $data = trim($data);
        if(isset($data) === true && $data === '') 
        {
            throw new \Exception($name . " parameter cannot be empty");
        }
        return $data;
    }

    function cleanInput($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    function checkEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
            throw new \Exception("Invalid email format");
        }

        return $email;
    }

    function decode($data)
    {
        $data = json_decode($data, true);
        if ($data === null && json_last_error() !== JSON_ERROR_NONE)
        {
            throw new \Exception("Incorrect json data");
        }
        return $data;
    }







}
