<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class AboutController extends AbstractController
{
	private $translator;
	

    public function __construct(DataCollectorTranslator $translator)
    {
		$this->translator = $translator;
	}
	
    public function aboutPage()
    {
    	return $this->render('about/about.html.twig');
    }
    
    public function contact(Request $request)
    {
    	$form = $this	->createFormBuilder()
				    	->add('name', TextType::class, array('attr' => array('class' => 'long')))
				    	->add('email', TextType::class, array('attr' => array('class' => 'long')))
				    	->add('message', TextType::class, array('attr' => array('class' => 'comments')))
				    	->getForm();
    	
    	
    	if ($request->getMethod() == 'POST')
    	{
    		$form->handleRequest($request);
    		
    		if ($form->isValid())
    		{
    			try {
    				$formData = $form->getData();
    				$subject = $this->translator->trans('about.mail_subject');
    				 
    				$message = (new \Swift_Message())
			    				->setSubject($subject)
			    				->setFrom($formData['email'])
			    				->setTo('morard.francois@gmail.com')
			    				->setBody($formData['message']);
    				 
    				if(!$this->get('mailer')->send($message)) {
    					$message = $this->translator->trans('about.error.message');
    					$this->get('session')->getFlashBag()->add('ko', $message);
    				}
    				else {
    					$message = $this->translator->trans('about.conf.message_send');
    					$this->get('session')->getFlashBag()->add('ok', $message);
    				}
    			}
    			catch(\Exception $e) {
    				$message = $this->translator->trans('about.error.message');
    				$this->get('session')->getFlashBag()->add('ko', $message);
    			}
    			
    			
    		}
    	}
    	 
    	$data['form'] = $form->createView();
    	
    	return $this->render('about/contact.html.twig', $data);
    }
}
