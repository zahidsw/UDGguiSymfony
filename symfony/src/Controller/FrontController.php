<?php

namespace App\Controller;

use App\Entity\Gui\Pop;


use App\Entity\Gui\Securitygroup;
use App\Form\PopType;
use App\Form\SecuritygroupType;
use App\Repository\Gui\PopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Process\Exception\ProcessFailedException;


class FrontController extends AbstractController {
	private $logger;
	private $translator;


	public function __construct( LoggerInterface $logger, DataCollectorTranslator $translator ) {
		$this->logger     = $logger;
		$this->translator = $translator;
	}


	public function list() {
		return $this->render( 'frontpage/front.html.twig' );
	}


	/**
	 * @IsGranted("ROLE_USER")
	 */
	public function customerSupport() {

		$user      = $this->container->get( 'security.token_storage' )->getToken()->getUser();
		$purchases = $user->getPurchases()->toArray();

		$em_gui   = $this->getDoctrine()->getManager( "gui" );
		$products = $em_gui->getRepository( "App\Entity\Gui\Product" )->findAll();

		foreach ( $purchases as $purchase ) {
			foreach ( $products as $product ) {
				if ( strtolower( $purchase->getProductSku() ) == strtolower( $product->getsku() ) ) {
					$purchase->users    = $product->getMaxUsers();
					$purchase->devices  = $product->getMaxDevices();
					$purchase->duration = $product->getDuration();
					$purchase->product  = $product->getSku();
				}
			}
		}

		$data ['purchases'] = $purchases;


		return $this->render( 'frontpage/customerSupport.html.twig', $data );
	}

	public function serviceSubscription( Request $request ) {
		$form = $this->createFormBuilder()
		             ->add( 'name', TextType::class, array( 'attr' => array( 'class' => 'long' ) ) )
		             ->add( 'email', TextType::class, array( 'attr' => array( 'class' => 'long' ) ) )
		             ->add( 'message', TextType::class, array( 'attr' => array( 'class' => 'comments' ) ) )
		             ->getForm();


		if ( $request->getMethod() == 'POST' ) {
			$form->handleRequest( $request );

			if ( $form->isValid() ) {
				try {
					$formData = $form->getData();
					$subject  = $this->translator->trans( 'about.mail_subject' );

					$message = ( new \Swift_Message() )
						->setSubject( $subject )
						->setFrom( $formData['email'] )
						->setTo( 'morard.francois@gmail.com' )
						->setBody( $formData['message'] );

					if ( ! $this->get( 'mailer' )->send( $message ) ) {
						$message = $this->translator->trans( 'about.error.message' );
						$this->get( 'session' )->getFlashBag()->add( 'ko', $message );
					} else {
						$message = $this->translator->trans( 'about.conf.message_send' );
						$this->get( 'session' )->getFlashBag()->add( 'ok', $message );
					}
				} catch ( \Exception $e ) {
					$message = $this->translator->trans( 'about.error.message' );
					$this->get( 'session' )->getFlashBag()->add( 'ko', $message );
				}


			}
		}

		$data['form'] = $form->createView();


		return $this->render( 'frontpage/serviceSubscription.html.twig', $data );
	}

	public function contact() {
		return $this->render( 'frontpage/contact.html.twig' );
	}

	public function service() {
		return $this->render( 'frontpage/service.html.twig' );
	}

	//////////////////////////////////////////////

	/**
	 * Finds and displays a Post entity.
	 *
	 * @Route("/{_locale}/{id<\d+>}/show", methods={"GET"}, name="pop_show")
	 */
	public function show( Pop $pop ): Response {
		// This security check can also be performed
		// using an annotation: @IsGranted("show", subject="post")
		// $this->denyAccessUnlessGranted('show', $post, 'Posts can only be shown to their authors.');

		return $this->render( 'frontpage/vnoShow.html.twig', [
			'pop' => $pop,
		] );
	}

	/**
	 * Lists all Post entities.
	 *
	 * @Route(" /{_locale}/iotList", methods={"GET", "POST"}, name="iot_list")
	 */

	public function iotVno() {
		return $this->render( 'frontpage/iotFront.html.twig' );

	}

	/**
	 * Lists all Post entities.
	 *
	 * @Route(" /{_locale}/listVno", methods={"GET", "POST"}, name="pop_list")
	 */

	public function listVno( PopRepository $pops ) {
		$pops = $pops->findAll();

		return $this->render( 'frontpage/vnoList.html.twig', [ 'pops' => $pops ] );

	}

///////////////////////////
	/**
	 * Displays a form to edit an existing pop entity.
	 *
	 * @Route("/{_locale}/{id<\d+>}/register",methods={"GET", "POST"}, name="pop_register")
	 */
	public function register( Request $request, Pop $pop ): Response {
		$encoders    = [ new XmlEncoder(), new JsonEncoder() ];
		$normalizers = [ new ObjectNormalizer() ];
		$serializer = new Serializer( $normalizers, $encoders );
		$aVal = array(
			'name'           => $pop->getName(),
			'authUrl'        => $pop->getAuth(),
			'tenant'         => $pop->getTenant(),
			'username'       => $pop->getUsername(),
			'password'       => $pop->getPassword(),
			'keyPair'        => $pop->getKeypair(),
			'securityGroups' => $pop->getSecuritygroup()->getValues(),
			'type'           => $pop->getType(),
			'location'       => array(
				'name'      => $pop->getLocationname(),
				'latitude'  => $pop->getLatitude(),
				'longitude' => $pop->getLongitude()
			)
		);

		file_put_contents( '../tosca_file/my.json', json_encode( $aVal ) );
		$this->logger->info( 'Executing: slice-manager --pop-descriptor /home/mandint/tmp/pop.json' );
		$command = '/home/mandint/slice-manager/slice_manager.py  --pop-descriptor ../tosca_file/my.json';
		$process = New Process($command);
		try {
			$process->mustRun( function ( $type, $buffer ) {
				$this->logger->info( $buffer );
				$this->addFlash(
					'notice',
					$buffer
				);
			} );

			if (substr($process->getOutput(), 18, 7) =='FAILURE'){

			}else{
				$pop->setStatus(1);
				$em = $this->getDoctrine()->getManager();
				$em->persist( $pop );
				$em->flush();
			}
			return $this->redirectToRoute( 'pop_list' );

		} catch (ProcessFailedException $exception) {
			echo $exception->getMessage();
		}
	}


	/**
	 * Displays a form to edit an existing Post entity.
	 *
	 * @Route("/{id<\d+>}/editVno",methods={"GET", "POST"}, name="vnoedit")
	 */

	public function editVno( Request $request, Pop $pop ): Response {

		$form = $this->createForm( PopType::class, $pop );
		$form->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {
			$pop->setStatus(0);
			$this->getDoctrine()->getManager()->flush();
			$this->addFlash( 'success', 'pop updated successfully' );

			return $this->redirectToRoute( 'vnolist', [ 'id' => $pop->getId() ] );
		}

		return $this->render( 'frontpage/vnoEdit.html.twig', [
			'pop'  => $pop,
			'form' => $form->createView(),
		] );
	}

	/**
	 * Deletes a Pop entity.
	 *
	 * @Route("/{id}/deleteVno", methods={"GET"}, name="vnodelete")
	 */

	public function deleteVno( Request $request, Pop $pop, AuthenticationUtils $authenticationUtils ): Response {
		if ( ! $authenticationUtils->getLastUsername() == '' ) {
			$pop->getSecuritygroup()->clear();
			$em = $this->getDoctrine()->getManager();
			$em->remove( $pop );
			$em->flush();
			$this->addFlash( 'success', 'post.deleted_successfully' );

			return $this->redirectToRoute( 'pop_list' );
		}

		return $this->redirectToRoute( 'pop_list' );
	}


	public function createVno( Request $request ): Response {
		$pop  = new Pop();
		$form = $this->createForm( PopType::class, $pop );

		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$pop->setStatus(0);
			$em = $this->getDoctrine()->getManager();
			$em->persist( $pop );
			$em->flush();
			$this->addFlash( 'success', 'post.created_successfully' );

			return $this->redirectToRoute( 'vnolist' );
		}

		return $this->render( 'frontpage/vnoAdd.html.twig', [
			'post' => $pop,
			'form' => $form->createView(),
		] );
		if ( $form->isSubmitted() && $form->isValid() ) {
			$task = $form->getData();

			/*      $pop = array (
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
	  */
			$filesystem = new Filesystem();
			$this->logger->info( 'Creating file: /home/mandint/tmp/pop.json' );
			$filesystem->dumpFile( '/home/mandint/tmp/pop.json', json_encode( $pop ) );
			$this->logger->info( 'Executing: slice-manager --pop-descriptor /home/mandint/tmp/pop.json' );
			$process = Process::fromShellCommandline( '/home/mandint/slice-manager/slice_manager.py  --pop-descriptor /home/mandint/tmp/pop.json' );

			$process->run( function ( $type, $buffer ) {
				$this->logger->info( $buffer );
				$this->addFlash(
					'notice',
					$buffer
				);
			} );

			return $this->redirectToRoute( 'vnocreate' );
		}
		$data['form'] = $form->createView();

		return $this->render( 'frontpage/vnoAdd.html.twig', $data );
	}

	/**
	 * Deletes a Security entity.
	 *
	 * @Route("/{_locale}/createsecurity", methods={"GET", "POST"}, name="vnosecuritygroup")
	 */

	public function createSecuritygroup( Request $request ): Response {

		$securitygroup = new Securitygroup();
		$form          = $this->createForm( SecuritygroupType::class, $securitygroup );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$em = $this->getDoctrine()->getManager();
			$em->persist( $securitygroup );
			$em->flush();

			// Flash messages are used to notify the user about the result of the
			// actions. They are deleted automatically from the session as soon
			// as they are accessed.
			// See https://symfony.com/doc/current/book/controller.html#flash-messages
			$this->addFlash( 'success', 'Security group created successfully' );

			return $this->redirectToRoute( 'vnosecuritygroup' );
		}

		return $this->render( 'frontpage/securitygroupAdd.html.twig', [
			'post' => $securitygroup,
			'form' => $form->createView(),
		] );
	}

	/**
	 * Deletes a Security entity.
	 *
	 * @Route("/groupsecurity/ajax"),  name="ajax")
	 */

	public function ajaxAction( Request $request ) {

		if ( $request->isXmlHttpRequest() || $request->query->get( 'template_id' ) == 1 ) {
			$template_ids = $request->request->get( 'template_id' );
			$total        = count( $template_ids );
			for ( $i = 0; $i < $total; $i ++ ) {
				$repo = $this->getDoctrine()->getRepository( Securitygroup::class )->find( $template_ids[ $i ] );
				$em   = $this->getDoctrine()->getManager();
				$em->remove( $repo );
				$em->flush();
			}
			$this->addFlash( 'success', 'SecurityGroups deleted successfully' );
			$securitygroups = $this->getDoctrine()
			                       ->getRepository( Securitygroup::class )
			                       ->findAll();
			$jsonData       = array();
			$idx            = 0;
			foreach ( $securitygroups as $student ) {
				$temp                = array(
					'name' => $student->getName(),
					'ids'  => $student->getId(),
				);
				$jsonData[ $idx ++ ] = $temp;
			}

			return new JsonResponse();
		}
	}

	/**
	 * Deletes a Securitygroupo entity.
	 *
	 * @Route("/{_locale}/deletesecuiritygroup", methods={"POST"}, name="securitydelete")
	 */

	public function deleteSecuirtygroup( Request $request, Securitygroup $securitygroup ): Response {
		if ( ! $this->isCsrfTokenValid( 'delete', $request->request->get( 'token' ) ) ) {
			return $this->redirectToRoute( 'vnolist' );
		}

		// Delete the tags associated with this blog post. This is done automatically
		// by Doctrine, except for SQLite (the database used in this application)
		// because foreign key support is not enabled by default in SQLite
		$pop->getSecuritygroup()->clear();

		$em = $this->getDoctrine()->getManager();

		$em->remove( $pop );
		$em->flush();

		$this->addFlash( 'success', 'post.deleted_successfully' );

		return $this->redirectToRoute( 'pop_list' );
	}


	public function controlMonitoring() {
		return $this->render( 'frontpage/controlMonitoring.html.twig' );
	}

	public function designPlan() {
		return $this->render( 'frontpage/designplan.html.twig' );
	}

	public function integration() {
		return $this->render( 'frontpage/integration.html.twig' );
	}

	public function fiware() {
		return $this->render( 'frontpage/fiware.html.twig' );
	}

	public function privacy() {
		return $this->render( 'frontpage/privacy.html.twig' );
	}

	public function registry() {
		return $this->render( 'frontpage/registry.html.twig' );
	}


}
