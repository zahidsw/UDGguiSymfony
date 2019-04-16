<?php

namespace App\Security;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SessionIdleHandler
{

    protected $session;
    protected $securityToken;
    protected $router;
    protected $maxIdleTime;

    public function __construct($maxIdleTime, SessionInterface $session, TokenStorageInterface $securityToken, RouterInterface $router)
    {
        $this->session = $session;
        $this->securityToken = $securityToken;
        $this->router = $router;
        $this->maxIdleTime = $maxIdleTime;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST != $event->getRequestType()) {

            return;
        }



        if ($this->maxIdleTime > 0) {

            $this->session->start();
            $lapse = time() - $this->session->getMetadataBag()->getLastUsed();
            
            if ($lapse > $this->maxIdleTime) {
                // $user = $this->securityToken->getToken()->getUser();
                // $user->setStatus(false);

                $this->securityToken->setToken(null);
                $this->session->getFlashBag()->set('info', 'You have been logged out due to inactivity.');

                // logout is defined in security.yaml.  See 'Logging Out' section here:
                // https://symfony.com/doc/4.1/security.html
                $event->setResponse(new RedirectResponse($this->router->generate('logout')));
            }
        }
    }
}
