<?php

namespace App\Service;

use App\Entity\Gui\City;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Gui\User;




class UserManagement extends AbstractController
{


    private $keyRockAPI;
    private $params;

    public function __construct(KeyRockAPI $keyRockAPI, ParameterBagInterface $params)
    {
        $this->keyRockAPI = $keyRockAPI;
        $this->params = $params;
    }


    // need to add parameter for user privileges
    public function addUser(String $email, String $city): array
    {


        $keyrockUser = $this->getKeyRockUser($email);

        $password = 'password'; // need to be generated by email

        if (empty($keyrockUser)) {
            $keyrockUser = $this->userRegistrationKeyRock($email, $email, $password);
        }

        $organization = $this->keyRockAPI->getOrganizationByName($city);

        if (empty($organization)) {
            $organization = $this->createOrganization($city);
        }


        $this->keyRockAPI->addUserToOrganization($keyrockUser['id'], $organization['id'], 'owner');

        $dbUser = $this->getDbUser($email);

        if($dbUser == NULL)
        {
            $dbUser = $this->userRegistrationDatabase($email, $keyrockUser['id']);
        }

        $entityManager = $this->getDoctrine()->getManager("gui");
        $cityDb = $entityManager->getRepository(City::class)->findOneBy(['name' => $city]);

        if(empty($cityDb))
        {

            $cityDb = new City();
            $cityDb->setName($city);
            $cityDb->setCountry('country');
            $entityManager->persist($cityDb);
        }

        $dbUser->setCity($cityDb);
        $dbUser->setKeyrockId($keyrockUser['id']);
        $entityManager->persist($dbUser);
        $entityManager->flush();



        $user = array();
        $user['dbUser']['id'] = $dbUser->getId();
        $user['keyrockUser']['id'] = $keyrockUser['id'];

        return $user;
    }




    public function createOrganization(String $city)
    {
        $response = $this->keyRockAPI->createOrganization($city, 'City of ' . $city);
        $organization = (string)$response->getBody();
        $organization = json_decode($organization, true);

        return $organization['organization'];

    }


    public function userRegistrationKeyRock($username, $email, $password):array
    {

         $response = $this->keyRockAPI->createToken($this->getParameter('keyrock.admin.user'),$this->getParameter('keyrock.admin.password'));
         $headers = $response->getHeaders();
         $this->keyRockAPI->setAuthToken($headers['X-Subject-Token'][0]);

         $response = $this->keyRockAPI->registerUser($username,$email,$password);
         $newUser = (string)$response->getBody();
         $newUser =json_decode($newUser,true);

         return $newUser['user'];
    }
    // todo return type string (status code) and test
    public function deleteUserKeyRock($userId)
    {

        $response = $this->keyRockAPI->createToken($this->getParameter('keyrock.admin.user'),$this->getParameter('keyrock.admin.password'));
        $headers = $response->getHeaders();
        $this->keyRockAPI->setAuthToken($headers['X-Subject-Token'][0]);

        $response = $this->keyRockAPI->deleteUser($userId);
        /*$newUser = (string)$response->getBody();
        $newUser =json_decode($newUser,true);

        return $newUser;*/
    }

    public function userRegistrationDatabase(String $email, String $keyRockId): object
    {
        $entityManager = $this->getDoctrine()->getManager("gui");
        $user = new User();
        $user->setUserName($email);
        $user->setEmail($email);
        $user->setEnabled(true);
        $userRole = ['ROLE_USER','ROLE_ADMIN'];
        $user->addRole(implode(",",$userRole));
        $user->setKeyrockId($keyRockId);
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }
    
    
    public function getKeyRockUser(String $email): array
    {
        $response = $this->keyRockAPI->createToken($this->params->get('keyrock.admin.user'),$this->params->get('keyrock.admin.password'));
        $headers = $response->getHeaders();
        $this->keyRockAPI->setAuthToken($headers['X-Subject-Token'][0]);
        $user = $this->keyRockAPI->getUserByMail($email);

        return $user;
    }

    public function getDbUser(String $email): ?object
    {
        $entityManager = $this->getDoctrine()->getManager("gui");
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        return $user;
    }







   

}
