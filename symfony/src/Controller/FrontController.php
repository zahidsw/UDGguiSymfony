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



class FrontController extends AbstractController
{
    private $logger;



    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function list()
    {

    	return $this->render('frontpage/front.html.twig');
    }


    /**
     * @IsGranted("ROLE_ADMIN")
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
                }
            }
        }

        $data ['purchases'] = $purchases;

    	return $this->render('frontpage/customerSupport.html.twig',$data);
    }

    public function discussion()
    {
        return $this->render('frontpage/discussion.html.twig');
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
                'password' => '65S8xLcC57',
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
            $this->logger->info('Creating file: /tmp/pop.json');
            
            $filesystem->dumpFile('/tmp/pop.json', json_encode($pop));
            $this->logger->info('Executing: slice-manager --pop-descriptor /tmp/pop.json');
            $process = new Process(['slice-manager', '--pop-descriptor','/tmp/pop.json']);
            $process->run();

            // executes after the command finishes

            try {
                if (!$process->isSuccessful()) 
                {
                    throw new ProcessFailedException($process);
                }
            }
            catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
           

            $this->logger->info($process->getOutput());
            
            return $this->redirectToRoute('vnocreate');
        }




        $data['form'] = $form->createView();
    
    	return $this->render('frontpage/vnoAdd.html.twig', $data);


    }







}