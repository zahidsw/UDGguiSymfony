<?php

namespace App\Controller;

use App\Service\UserManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Gui\User;
use App\Entity\Gui\Purchase;
use Symfony\Component\HttpFoundation\Request;
use \Firebase\JWT\JWT;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SetPassword;




class PurchaseController extends AbstractController
{

    private $userManagement;


    public function __construct(UserManagement $userManagement)
    {
		$this->userManagement = $userManagement;

	}


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
    public function add(Request $request, \Swift_Mailer $mailer)
    {
        try
        {
            $key = "9yUCrlExdstKquikKe7hO44O1ze1wyWUIHp9ZjQY"; // need to move in .env file
            $headers = $request->headers->all();
            $jwt = substr($headers['authorization'][0], strpos($headers['authorization'][0], 'Bearer') + 7);
            $decoded = JWT::decode($jwt, $key, array('HS256'));
            $data = $this->decode(json_encode($decoded), true);

            $data = $this->validate($data);

            $user = $this->userManagement->addUser($data['customer']['email'],$data['customer']['city']);

            $entityManager = $this->getDoctrine()->getManager("gui");
            $userDb = $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $user['dbUser']['id']]);

            if(!$userDb->isEnabled())
            {
                $userRegistrationToken = $this->userManagement->generateRegistrationToken();
                $userDb->setRegistrationToken($userRegistrationToken);
                $entityManager->persist($userDb);
                $entityManager->flush();
                $message = (new \Swift_Message('UDG: you got mail!'))
                    ->setFrom('demo@mandint.org')
                    ->setTo($data['customer']['email'])
                    ->setBody(
                        $this->renderView('emails/registration.html.twig',['name' => $data['customer']['first_name'],'token'=>$userRegistrationToken]), 'text/html'
                    );
                $mailer->send($message);
            }

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
            $purchase->setUser($userDb);
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
            $data['customer']['email'] = $this->checkEmail($this->cleanInput($this->isEmpty($data['customer']['email'],'mail')));
            $data['order_number'] = $this->cleanInput($this->isEmpty($data['order_number'],'order number'));
            $data['service_id'] = $this->cleanInput($this->isEmpty($data['service_id'],'service id'));
            $data['service_description'] = $this->cleanInput($this->isEmpty($data['service_description'],'service description'));
            $data['timestamp'] = $this->cleanInput($this->isEmpty($data['timestamp'],'time stamp'));
            $data['request_url'] = $this->cleanInput($this->isEmpty($data['request_url'],'request url'));
            $data['product']['sku'] = $this->cleanInput($this->isEmpty($data['product']['sku'],'product sku'));
            $data['product']['name'] = $this->cleanInput($this->isEmpty($data['product']['name'],'product name'));
            $data['product']['quantity'] = $this->cleanInput($this->isEmpty($data['product']['quantity'],'product quantity'));
            $data['product']['price'] = $this->cleanInput($this->isEmpty($data['product']['price'],'product price'));
            $data['product']['currency'] = $this->cleanInput($this->isEmpty($data['product']['currency'],'product currency'));
            $data['product']['fields']['callback_url'] = $this->cleanInput($this->isEmpty($data['product']['fields']['callback_url'],'callback url'));
            $data['customer']['city'] = $this->cleanInput($this->isEmpty($data['customer']['city'],'city'));
            $data['customer']['country'] = $this->cleanInput($this->isEmpty($data['customer']['country'],'country'));
            $data['customer']['first_name'] = $this->cleanInput($this->isEmpty($data['customer']['first_name'],'first name'));

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


    /**
     * @Route("/enc", name="enc",methods={"POST"})
     */
    public function encrypt()
    {
        $key = "9yUCrlExdstKquikKe7hO44O1ze1wyWUIHp9ZjQY";

        $token = array(
            'timestamp' => 12121212,
            'service_id' => 'SIPM',
            'service_description' => 'Synchronicity IoT Product Marketplace',
            'request_url' => 'https://0.0.0.0',
            'order_number' => '39',
            'customer' =>
                array(
                    'first_name' => 'John',
                    'last_name' => 'Client',
                    'email' => 'softlifedev@gmail.com',
                    'street' => 'Buchanan St. 21',
                    'city' => 'bologna',
                    'state' => 'Country',
                    'zip_code' => '94147',
                    'country' => 'Country',
                    'country_code' => 'US',
                    'phone' => '',
                ),
            'product' =>
                array(
                    'sku' => 'udgaas-basic', //'udgaas-basic'
                    'name' => 'udgaas-basic',
                    'quantity' => 1,
                    'price' => 0.0,
                    'currency' => 'CHF',
                    'fields' =>
                        array(
                            'callback_url' => 'http://10.4.17.161/purchase',
                            'callback_field_1' => 'Field 1 value',
                            'callback_field_2' => 'Field 2 value',
                        ),
                ),
        );

        $jwt = JWT::encode($token, $key, 'HS256');
        dd($jwt);
    }
}
