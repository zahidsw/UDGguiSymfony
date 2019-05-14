<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Gui\Product;
use App\Form\VNOType;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class FrontController extends AbstractController
{
    private $logger;
    private $translator;



    public function __construct(LoggerInterface $logger,DataCollectorTranslator $translator)
    {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    public function list()
    {

    	return $this->render('frontpage/front.html.twig');
    }


    /**
     * @IsGranted("ROLE_USER")
     */
    public function customerSupport()
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $purchases = $user->getPurchases()->toArray();

        $em_gui = $this->getDoctrine()->getManager("gui");
        $products = $em_gui->getRepository("App\Entity\Gui\Product")->findAll();
        
        foreach($purchases as $purchase)
        {
            foreach($products as $product)
            {
                if(strtolower($purchase->getProductSku()) == strtolower($product->getsku()))
                {
                    $purchase->users = $product->getMaxUsers();
                    $purchase->devices = $product->getMaxDevices();
                    $purchase->duration = $product->getDuration();
                    $purchase->product = $product->getSku();
                }
            }
        }

        $data ['purchases'] = $purchases;
        

    	return $this->render('frontpage/customerSupport.html.twig',$data);
    }

    public function serviceSubscription(Request $request)
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
    	
    	
        return $this->render('frontpage/serviceSubscription.html.twig', $data);
    }

    public function contact()
    {
        return $this->render('frontpage/contact.html.twig');
    }

    public function service()
    {
        return $this->render('frontpage/service.html.twig');
    }




    public function createVno(Request $request)
    {
        $form = $this->createForm(VNOType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            
            $task = $form->getData();

            $pop = array (
                'name' => $task['name'],
                'authUrl' => $task['AuthorisationURL'],
                'tenant' => $task['Tenant'],
                'username' => $task['Username'],
                'password' => $task['Password'],
                'keyPair' => $task['KeyPair'],
                'securityGroups' => $task['SecurityGroups'],
                'type' => $task['Type'],
                'location' => 
                array (
                  'name' => 'Aarhus',
                  'latitude' => '56.162939',
                  'longitude' => '10.203921',
                ),
            );

            $filesystem = new Filesystem();
            $this->logger->info('Creating file: /home/mandint/tmp/pop.json');
            
            $filesystem->dumpFile('/home/mandint/tmp/pop.json', json_encode($pop));
	        $this->logger->info('Executing: slice-manager --pop-descriptor /home/mandint/tmp/pop.json');
	        $process = Process::fromShellCommandline('/home/mandint/slice-manager/slice_manager.py  --pop-descriptor /home/mandint/tmp/pop.json');
            
            $process->run(function ($type, $buffer) 
            {
                $this->logger->info($buffer); 
                $this->addFlash(
                    'notice',
                    $buffer
                );
            });
            
            return $this->redirectToRoute('vnocreate');
        }




        $data['form'] = $form->createView();
    
    	return $this->render('frontpage/vnoAdd.html.twig', $data);


    }

    public function controlMonitoring()
    {
        return $this->render('frontpage/controlMonitoring.html.twig');
    }

    public function designPlan()
    {
        return $this->render('frontpage/designplan.html.twig');
    }

    public function integration()
    {
        return $this->render('frontpage/integration.html.twig');
    }

    public function fiware()
    {
        return $this->render('frontpage/fiware.html.twig');
    }

    public function privacy()
    {
        return $this->render('frontpage/privacy.html.twig');
    }

    public function registry()
    {
        return $this->render('frontpage/registry.html.twig');
    }







}
