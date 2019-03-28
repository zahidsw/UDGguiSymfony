<?php

namespace App\Service;
use Http\Message\MessageFactory;
use Http\Client\Common\HttpMethodsClient;



class KeyRockAPI
{
    private $messageFactory;
    private $httpMethodsClient;
    private $authToken;
    private $baseUrl = 'https://keyrock.cityreport.org';
    private $applicationId;

    public function __construct(MessageFactory $messageFactory, HttpMethodsClient $httpMethodsClient)
    {
        $this->messageFactory = $messageFactory;
        $this->httpMethodsClient = $httpMethodsClient;
    }

    public function setApplicationId(String $applicationId)
    {
        $this->applicationId = $applicationId;
    }

    public function getApplicationId()
    {
        return $this->applicationId;
    }

    public function setAuthToken(String $authToken)
    {
        $this->authToken = $authToken;
    }

    public function getAuthToken()
    {
        return $this->authToken;
    }

    public function registerUser(String $userName, String $email, String $password):object
    {
        $body = array (
                'user' => 
                    array (
                    'username' => $userName,
                    'email' => $email,
                    'password' => $password
                    ),
                );
        
        $headers = ['X-Auth-token' => $this->authToken,'Content-Type'=>'application/json'];
        $request = $this->messageFactory->createRequest('POST', $this->baseUrl . '/v1/users', $headers, json_encode($body));
        $response = $this->httpMethodsClient->sendRequest($request);

        return $response;
    }


    public function getUsers():object
    {
        $headers = ['X-Auth-token' => $this->authToken,'Content-Type'=>'application/json'];
        $request = $this->messageFactory->createRequest('GET', $this->baseUrl . '/v1/users', $headers);
        $response = $this->httpMethodsClient->sendRequest($request);

        return $response;
    }

    public function assignRole(String $userId, String $roleId):object
    {
       
        $url = $this->baseUrl . '/v1/applications/' . $this->applicationId . '/users/' . $userId . '/roles/' . $roleId;
        $headers = ['X-Auth-token' => $this->authToken,'Content-Type'=>'application/json'];
        $request = $this->messageFactory->createRequest('POST', $url, $headers);
        $response = $this->httpMethodsClient->sendRequest($request);
        
        return $response;
    }

    public function getRoleId(String $roleName): String
    {
        
        $url = $this->baseUrl . '/v1/applications/' . $this->applicationId .'/roles';
        $headers = ['X-Auth-token' => $this->authToken,'Content-Type'=>'application/json'];
        $request = $this->messageFactory->createRequest('GET', $url, $headers);
        $response = $this->httpMethodsClient->sendRequest($request);

        $roles = (string)$response->getBody();
        $roles = json_decode($roles,TRUE);

        foreach($roles['roles'] as $key=>$role){
            if(strtolower($role['name']) == strtolower($roleName)) return $role['id'];
        }
        
        return false;
    }




}
