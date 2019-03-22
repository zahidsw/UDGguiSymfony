<?php


namespace App\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;


// Source: https://gist.github.com/danvbe/4476697

class FOSUBUserProvider extends BaseClass {

    public function connect(UserInterface $user, UserResponseInterface $response) {
        $property = $this->getProperty($response);
        dd($property);
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
        dd('testt');
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response) {
        dd($response);// not authorized user need to be managed
        $username = $response->getUsername();
        
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        
        //when the user is registrating
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            //I have set all requested data with the user's username
            //modify here with relevant data
            $user->setUsername($username);
            $user->setEmail($username);
            $user->setPassword($username);
            $user->setEnabled(true);
            $user->setSalt('todelete');
            
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