<?php

namespace App\Controller;

use App\Entity\Gui\User;
use App\Form\SetEmail;
use App\Form\SetPassword;
use App\Service\UserManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $userManagement;

    public function __construct(UserManagement $userManagement)
    {
        $this->userManagement = $userManagement;
    }
   
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function logoutAction()
    {
        throw new \RuntimeException('This should never be called directly.');
    }

    /**
     *
     * @Route("/resetpassword/{email}", defaults={"email"="mail@mail.com"}, name="resetpassword")
     */
    public function resetPassword(String $email, Request $request, \Swift_Mailer $mailer)
    {

        $entityManager = $this->getDoctrine()->getManager("gui");
        $form = $this->createForm(SetEmail::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $task = $form->getData();
            $userDb = $entityManager->getRepository(User::class)->findOneBy(['email' => $task['email']]);

            // generate reset token only if user was already enabled
            if($userDb == NULL || !$userDb->isEnabled())
            {
                return $this->render('security/resetPassword.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $token = $this->userManagement->generateRegistrationToken();
            $userDb->setRegistrationToken($token);
            $entityManager->persist($userDb);
            $entityManager->flush();

            $message = (new \Swift_Message('UDG: reset password!'))
            ->setFrom('demo@mandint.org')
            ->setTo($userDb->getEmail())
            ->setBody(
                $this->renderView('emails/resetPassword.html.twig',['token'=>$token]), 'text/html'
            );
            $mailer->send($message);

            $this->addFlash('success', 'Great! Check your mail!');
        }

        return $this->render('security/resetPassword.html.twig', [
            'form' => $form->createView()
        ]);

    }


    /**
     *
     * @Route("/setpassword/{token}", name="setpassword")
     */
    public function setpassword(String $token, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager("gui");
        $userDb = $entityManager->getRepository(User::class)->findOneBy(['registrationToken' => $token]);

        if($userDb == NULL) return $this->render('security/tokenExpired.html.twig');

        $form = $this->createForm(SetPassword::class);
        $form->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid())
            {
                $userDb = $entityManager->getRepository(User::class)->findOneBy(['registrationToken' => $token]);
                $userKeyrock = $this->userManagement->getKeyRockUser($userDb->getEmail());
                $task = $form->getData();
                $this->userManagement->updateKeyRockUser($userKeyrock['id'], $userKeyrock['username'], $userKeyrock['email'], $task['password']);
                $userDb->setRegistrationToken('');
                $userDb->setEnabled(1);
                $entityManager->persist($userDb);
                $entityManager->flush();
                $this->addFlash('success', 'Password correctly set, you can now login!');
                return $this->redirectToRoute('login');
            }
        }catch(\Throwable $e){

            return $this->render('security/setPassword.html.twig', [
                'form' => $form->createView(), 'token' => $token
            ]);
        }

        $data['form'] = $form->createView();
        $data['token'] = $token;

        return $this->render('security/setPassword.html.twig', [
            'form' => $form->createView(), 'token' => $token
        ]);
    }

}
