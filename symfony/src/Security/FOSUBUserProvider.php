<?php


namespace App\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;


// Source: https://gist.github.com/danvbe/4476697

class FOSUBUserProvider extends BaseClass {

    public function connect(UserInterface $user, UserResponseInterface $response) {
        $property = $this->getProperty($response);
        
        $username = $response->getUsername();
        
        // On connect, retrieve the access token and the user id
        $service = $response->getResourceOwner()->getName();
        
        $setter = 'set' . ucfirst($service);
        $setter_id = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';
        
        // Disconnect previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
        
        // Connect using the current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response) {

        $data = $response->getData();
        $fiware_id = $response->getUsername();
        // TO DO need to be managed unauthorized users in fiware application
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $fiware_id));
        
        //when the user is new in the gui interface
        if (null === $user) {
            $user = $this->userManager->findUserBy(array('email'=>$response->getEmail()));
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            $user->$setter_id($fiware_id);
            $user->$setter_token($response->getAccessToken());
            $this->userManager->updateUser($user);
            return $user;
        }

        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
        //update access token
        $user->$setter($response->getAccessToken());

        return $user;
    }
    
    /**
     * Generates a random username with the given 
     * e.g 12345_github, 12345_facebook
     * 
     * @param string $username
     * @param type $serviceName
     * @return type
     */
    private function generateRandomUsername($username, $serviceName){
        if(!$username){
            $username = "user". uniqid((rand()), true) . $serviceName;
        }
        
        return $username. "_" . $serviceName;
    }
}