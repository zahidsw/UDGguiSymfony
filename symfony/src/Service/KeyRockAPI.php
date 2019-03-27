<?php

namespace App\Service;
use Http\Message\MessageFactory;
use Http\Client\Common\HttpMethodsClient;



class KeyRockAPI
{
    private $messageFactory;
    private $httpMethodsClient;
    private $authToken;
    private $baseUrl;

    public function __construct(MessageFactory $messageFactory, HttpMethodsClient $httpMethodsClient)
    {
        $this->messageFactory = $messageFactory;
        $this->httpMethodsClient = $httpMethodsClient;
        $this->authToken = '275e4067-5f87-4b55-a3c2-15a9b1e86eb6'; // to be set and get with setter and getter
        $this->baseUrl = 'https://keyrock.cityreport.org'; 
    }

    public function registerUser(String $userName, String $email, String $password):String
    {
        $body = array (
                'user' => 
                    array (
                    'username' => $userName,
                    'email' => $email,
                    'password' => $password,
                    ),
                );
        
        $headers = ['X-Auth-token' => $this->authToken,'Content-Type'=>'application/json'];
        $request = $this->messageFactory->createRequest('POST', $this->baseUrl . '/v1/users', $headers, json_encode($body));
        $response = $this->httpMethodsClient->sendRequest($request);
        return $response->getStatusCode();
    }


    public function getUsers():String
    {
        $headers = ['X-Auth-token' => $this->authToken,'Content-Type'=>'application/json'];
        $request = $this->messageFactory->createRequest('GET', $this->baseUrl . '/v1/users', $headers);
        $response = $this->httpMethodsClient->sendRequest($request);

        return (string) $response->getBody();
    }

    public function assignRole(String $applicationId, String $userId, String $roleId): String
    {
       
        $url = $this->baseUrl . '/v1/applications/' . $applicationId . '/users/' . $userId . '/roles/' . $roleId;
        $headers = ['X-Auth-token' => $this->authToken,'Content-Type'=>'application/json'];
        $request = $this->messageFactory->createRequest('POST', $url, $headers);
        $response = $this->httpMethodsClient->sendRequest($request);
        
        return $response->getStatusCode();


    }






}
