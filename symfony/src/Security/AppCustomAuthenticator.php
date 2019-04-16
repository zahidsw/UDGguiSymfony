<?php

namespace App\Security;

use App\Entity\Gui\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Service\KeyRockAPI;

class AppCustomAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $keyRockAPI;
    

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager,KeyRockAPI $keyRockAPI)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->keyRockAPI = $keyRockAPI;
    }

    public function supports(Request $request)
    {
        return 'login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }
        
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);
        
        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Email could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Check the user's password or other credentials and return true or false
        // If there are no credentials to check, you can just return true
        try {
            $response = $this->keyRockAPI->createToken($credentials['email'],$credentials['password']);
        } catch (\Exception $e){
             throw new CustomUserMessageAuthenticationException($e->getMessage());
        }

        $headers = $response->getHeaders();
        $user->setSubjectToken($headers['X-Subject-Token'][0]);
        $this->entityManager->persist($user);
        $this->entityManager->flush($user);

        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse('/en/home');
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('login');
    }
}