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

            //$this->sendMail('testname',$mailer);
            $message = (new \Swift_Message('You Got Mail!'))
                ->setFrom('demo@mandint.org')
                ->setTo('felpone84@gmail.com')
                ->setBody(
                    $this->renderView('emails/registration.html.twig',['name' => 'name']), 'text/html'
                );

            $mailer->send($message);
            

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

    public function userRegistrationKeyRock($username, $email, $password):integer
    {
         //impersonate admin user in order to create a new user
         $response = $this->keyRockAPI->createToken($this->getParameter('keyrock.admin.user'),$this->getParameter('keyrock.admin.password'));
         $headers = $response->getHeaders();
         $this->keyRockAPI->setAuthToken($headers['X-Subject-Token'][0]);
         // create the user in keyrock
         $response = $this->keyRockAPI->registerUser($username,$email,$password);
         $newUser = (string)$response->getBody();
         $newUser =json_decode($newUser,true);
         $newUserKeyrockId = $newUser['user']['id'];
    }

    public function userRegistrationDatabase($keyRockId, $city): object
    {
        $entityManager = $this->getDoctrine()->getManager("gui");
        $user = new User();
        $user->setUserName($email);
        $user->setEmail($email);
        $user->setEnabled(true);
        $user->setKeyrockId($keyRockId);
        $user->setCity($city);
        $userRole = ['ROLE_USER','ROLE_ADMIN'];
        $user->addRole(implode(",",$userRole));
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }


}
