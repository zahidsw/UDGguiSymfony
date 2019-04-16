<?php
namespace App\EventListener;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\HttpKernel;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Gui\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Listener that updates the last activity of the authenticated user
 */
class ActivityListener
{
    protected $securityContext;
    protected $entityManager;
    protected $securityToken;

    public function __construct( TokenStorageInterface $securityToken, EntityManagerInterface $entityManager)
    {
        $this->securityToken = $securityToken;
        $this->entityManager = $entityManager;
    }

    /**
    * Update the user "lastActivity" on each request
    * @param FilterControllerEvent $event
    */
    public function onKernelRequest(GetResponseEvent $event)
    {
        // Check that the current request is a "MASTER_REQUEST"
        // Ignore any sub-request
        if ($event->getRequestType() !== HttpKernel::MASTER_REQUEST) {
            return;
        }

        // Check token authentication availability
        if ($this->securityToken->getToken()) {
            $user = $this->securityToken->getToken()->getUser();
            

            if ( ($user instanceof User) ) {
                $user->setLastActivityAt(new \DateTime());
                $this->entityManager->flush($user);
            }
        }
    }
}