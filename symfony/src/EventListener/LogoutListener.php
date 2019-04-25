<?php

namespace App\EventListener;
 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Gui\User;
 
class LogoutListener implements LogoutHandlerInterface
{
 
    protected $entityManager;

    public function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @{inheritDoc}
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $user = $token->getUser();
        if ( ($user instanceof User) ) {
            $user->setLastManualLogoutAt(new \DateTime());
            $this->entityManager->flush($user);
        }
        
    }
}