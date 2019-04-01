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

        if($response->getStatusCode() != "201"){
            $message = (string)$response->getBody();
            throw new \Exception($message);
        }

        return $response;
    }


    public function getUsers():object
    {
        $headers = ['X-Auth-token' => $this->authToken,'Content-Type'=>'application/json'];
        $request = $this->messageFactory->createRequest('GET', $this->baseUrl . '/v1/users', $headers);
        $response = $this->httpMethodsClient->sendRequest($request);

        if($response->getStatusCode() != "200"){
            $message = (string)$response->getBody();
            throw new \Exception($message);
        }

        return $response;
    }

    public function deleteUser(String $userId):object
    {
        $headers = ['X-Auth-token' => $this->authToken,'Content-Type'=>'application/json'];
        $request = $this->messageFactory->createRequest('DELETE', $this->baseUrl . '/v1/users/' . $userId, $headers);
        $response = $this->httpMethodsClient->sendRequest($request);

        if($response->getStatusCode() != "204"){
            $message = (string)$response->getBody();
            throw new \Exception($message);
        }

        return $response;
    }

    public function updateUser(String $userId): object
    {
        $headers = ['X-Auth-token' => $this->authToken,'Content-Type'=>'application/json'];
        $request = $this->messageFactory->createRequest('PATCH', $this->baseUrl . '/v1/users/' . $userId, $headers);
        $response = $this->httpMethodsClient->sendRequest($request);
        dd($response);

        if($response->getStatusCode() != "204"){
            $message = (string)$response->getBody();
            throw new \Exception($message);
        }

        return $response;
    }

    

    public function createToken(String $email, String $password):object
    {
        $body  = array (
                'name' => $email,
                'password' => $password
        );
           
        $headers = ['Content-Type'=>'application/json'];
        $request = $this->messageFactory->createRequest('POST', $this->baseUrl . '/v1/auth/tokens/', $headers, json_encode($body));
        $response = $this->httpMethodsClient->sendRequest($request);

        if($response->getStatusCode() != "201"){
            $message = (string)$response->getBody();
            throw new \Exception($message);
        }

        return $response;
    }

    public function getOrganizations():object
    {
        $headers = ['X-Auth-token' => $this->authToken,'Content-Type'=>'application/json'];
        $request = $this->messageFactory->createRequest('GET', $this->baseUrl . '/v1/organizations', $headers);
        $response = $this->httpMethodsClient->sendRequest($request);

        if($response->getStatusCode() != "201"){
            $message = (string)$response->getBody();
            throw new \Exception($message);
        }

        return $response;
    }

    public function addUserToOrganization(String $userId, String $organizationId, String $roleId)
    {
        // v1/organizations/organization_id/users/user_id/organization_roles/organization_role_id

        $url = $this->baseUrl . '/v1/organizations/' . $organizationId . '/users/' . $userId . '/organization_roles/' . $roleId;
        $headers = ['X-Auth-token' => $this->authToken,'Content-Type'=>'application/json'];
        $request = $this->messageFactory->createRequest('POST', $url, $headers);
        $response = $this->httpMethodsClient->sendRequest($request);

        if($response->getStatusCode() != "201"){
            $message = (string)$response->getBody();
            throw new \Exception($message);
        }
        
        return $response;
    }

    public function removeUserFromOrganization(String $userId, String $organizationId, String $roleId): object
    {
        //v1/organizations/organization_id/users/user_id/organization_roles/organization_role_id
        $url = $this->baseUrl . '/v1/organizations/' . $organizationId . '/users/' . $userId . '/organization_roles/' . $roleId;
        $headers = ['X-Auth-token' => $this->authToken,'Content-Type'=>'application/json'];
        $request = $this->messageFactory->createRequest('DELETE', $url, $headers);
        $response = $this->httpMethodsClient->sendRequest($request);

        if($response->getStatusCode() != "204"){
            $message = (string)$response->getBody();
            throw new \Exception($message);
        }

        return $response;
    }


}
