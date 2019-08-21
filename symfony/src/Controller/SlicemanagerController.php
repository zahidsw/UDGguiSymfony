<?php

namespace App\Controller;

use App\Entity\Gui\Flavourkeys;
use App\Entity\Gui\Slicemanager;
use App\Entity\Gui\Virtuallink;
use App\Form\FlavourkeysType;
use App\Form\SlicemanagerType;
use App\Form\VirtuallinkType;
use App\Repository\Gui\SlicemanagerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Process\Process;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\HttpFoundation\JsonResponse;

class SlicemanagerController extends AbstractController {
	private $logger;
	private $translator;

	public function __construct( LoggerInterface $logger, DataCollectorTranslator $translator ) {
		$this->logger     = $logger;
		$this->translator = $translator;
	}

	/**
	 * @Route("/{_locale}/slicemanager", name="slice_list")
	 */
	public function index( SlicemanagerRepository $slices ): Response {

		$slices = $slices->findAll();

		return $this->render( 'slicemanager/index.html.twig', [ 'slices' => $slices ] );
	}

	/**
	 * Creates a new slice entity.
	 *
	 * @Route("/{_locale}/new", methods={"GET", "POST"}, name="slice_new")
	 *
	 * NOTE: the Method annotation is optional, but it's a recommended practice
	 * to constraint the HTTP methods each controller responds to (by default
	 * it responds to all methods).
	 */
	public function new( Request $request ): Response {
		$slice = new Slicemanager();
		$form  = $this->createForm( SlicemanagerType::class, $slice );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$slice->setStatus( 0 );
			$slice->setSliceid( "null" );
			$em = $this->getDoctrine()->getManager();
			$em->persist( $slice );
			$em->flush();

			// Flash messages are used to notify the user about the result of the
			// actions. They are deleted automatically from the session as soon
			// as they are accessed.
			// See https://symfony.com/doc/current/book/controller.html#flash-messages
			$this->addFlash( 'success', 'slice created_successfully' );

			return $this->redirectToRoute( 'slice_list' );
		}

		return $this->render( 'slicemanager/new.html.twig', [
			'slice' => $slice,
			'form'  => $form->createView(),
		] );
	}

	/**
	 * Displays a form to edit an existing Slicemanager entity.
	 *
	 * @Route("/{id<\d+>}/registerslice",methods={"GET", "POST"}, name="slice_register")
	 */
	public function register( Request $request, Slicemanager $slice ): Response {
		$ar                = Yaml::parseFile( "../tosca_file/TOSCA-Metadata/Metadata.yaml" );
		$ar["name"]        = $slice->getSlicename();
		$ar["description"] = $slice->getSlicedescription();
		$ar["provider"]    = $slice->getSlcieprovider();
		$yaml              = Yaml::dump( $ar );
		file_put_contents( '../tosca_file/TOSCA-Metadata/Metadata.yaml', $yaml );

		$ar                                                                          = Yaml::parseFile( "../tosca_file/Definitions/IoT_slice.yaml" );
		$ar['tosca_definitions_version']                                             = $slice->getSlicename();
		$ar['description']                                                           = $slice->getSlicedescription();
		$ar['metadata']['vendor']                                                    = $slice->getSlcieprovider();
		$ar['topology_template']['node_templates']['UDGaaF']['properties']['vendor'] = $slice->getSlcieprovider();
		$i                                                                           = 0;
		foreach ( $slice->getFlavourkeys() as $keys ) {
			$ar['topology_template']['node_templates']['UDGaaF']['properties']['deploymentFlavour'][ $i ]['flavour_key'] = $keys->getName();
			$i ++;
		}
		$i = 0;
		foreach ( $slice->getVirtuallink() as $keys ) {

			$ar['topology_template']['node_templates']['UDGaaF']['requirements'][ $i ]['virtualLink'] = $keys->getNeworkname();
			$i ++;
		}
		$ar['topology_template']['node_templates']['CP_UDG']['requirements'][1]['virtualLink'] = $keys->getNeworkname();
		$i                                                                                     = 0;
		foreach ( $slice->getPopinstance() as $keys ) {
			$ar['topology_template']['node_templates']['VDU_UDG']['properties']['vim_instance_name'][ $i ] = $keys->getName();
			$i ++;
		}

		$yaml = Yaml::dump( $ar );
		file_put_contents( '../tosca_file/Definitions/IoT_slice.yaml', $yaml );
		 $command ='cd ../tosca_file && zip -r IoT_slice.csar . -x ".*" -x "*/.*"' ;
		$this->process = New Process($command);
		$this->process->start();
		// waits until the given anonymous function returns true
		$this->process->waitUntil(function ($type, $output) {
			$output === 'Ready. Waiting for commands...';
		});
		$command       = '/home/mandint/slice-manager/slice_manager.py --tosca-file ../tosca_file/IoT_slice.csar';
		$this->process = New Process( $command );
		$this->process->start();
		try {
			$this->process->waitUntil( function ( $type, $buffer ) {
				$this->logger->info( $buffer );
			} );
			if ( substr( $this->process->getOutput(), 18, 7 ) == 'FAILURE' ) {
				return new JsonResponse( $this->process->getOutput() );

			} else {
				$pos = strpos( $this->process->getOutput(), 'nsr_id:' );
				$slice->setSliceid( substr( $this->process->getOutput(), $pos + 8, 36 ) );
				$slice->setStatus( 1 );
				$em = $this->getDoctrine()->getManager();
				$em->persist( $slice );
				$em->flush();

				return new JsonResponse( $this->process->getOutput() );
			}

		} catch ( ProcessFailedException $exception ) {
			echo $exception->getMessage();

			return new JsonResponse( $this->process->getOutput() );
		}
	}

	/**
	 * Displays a form to edit an existing Slicemanager entity.
	 *
	 * @Route("/{id<\d+>}/slice_status",methods={"GET", "POST"}, name="slice_status")
	 */
	public function status( Request $request, Slicemanager $slice ): Response {
		$command = '/home/mandint/slice-manager/slice_manager.py --get-states ' . $slice->getSliceid();
		$process = New Process( $command );
		$process->start();
		$process->waitUntil( function ( $type, $buffer ) {
			$this->logger->info( $buffer );
		} );

		return new JsonResponse( $process->getOutput() );
	}

	/**
	 * Displays a form to edit an existing slice entity.
	 *
	 * @Route("/{_locale}/{id<\d+>}/editslice",methods={"GET", "POST"}, name="slice_edit")
	 */

	public function edit( Request $request, Slicemanager $slice ): Response {

		$form = $this->createForm( SlicemanagerType::class, $slice );
		$form->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {
			$slice->setStatus( 0 );
			$this->getDoctrine()->getManager()->flush();
			$this->addFlash( 'success', 'Slice updated successfully' );

			return $this->redirectToRoute( 'slice_list', [ 'id' => $slice->getId() ] );
		}

		return $this->render( 'slicemanager/edit.html.twig', [
			'slice' => $slice,
			'form'  => $form->createView(),
		] );
	}

	//////////////////////////////////////////////

	/**
	 * Finds and displays a slice entity.
	 *
	 * @Route("/{id<\d+>}/sliceshow", methods={"GET","POST"}, name="slice_show")
	 */

	public function show( Slicemanager $slice ): Response {

		// This security check can also be performed
		// using an annotation: @IsGranted("show", subject="post")
		// $this->denyAccessUnlessGranted('show', $post, 'Posts can only be shown to their authors.');

		return $this->render( 'slicemanager/show.html.twig', [
			'slice' => $slice,
		] );
	}

	/**
	 * Deletes a slice entity.
	 *
	 * @Route("/{id<\d+>}/delete", methods={"GET","POST"}, name="slice_delete")
	 */

	public function deleteVno( Request $request, Slicemanager $slice, AuthenticationUtils $authenticationUtils ): Response {
		if ( ! $authenticationUtils->getLastUsername() == '' ) {
			$command       = '/home/mandint/slice-manager/slice_manager.py --delete-slice ' . $slice->getSliceid();
			$this->process = New Process( $command );
			$this->process->start();
			try {
				$this->process->waitUntil( function ( $type, $buffer ) {
					$this->logger->info( $buffer );
				} );
			} catch ( ProcessFailedException $exception ) {
				echo $exception->getMessage();
				return new JsonResponse( $this->process->getOutput() );
			}
			// Delete the popinstance associated with this blog post. This is done automatically
			// by Doctrine, except for SQLite (the database used in this application)
			// because foreign key support is not enabled by default in SQLite
			$slice->getPopinstance()->clear();
			$slice->getVirtuallink()->clear();
			$em = $this->getDoctrine()->getManager();
			$em->remove( $slice );
			$em->flush();
			$this->addFlash( 'success', 'post.deleted_successfully' );

			return $this->redirectToRoute( 'slice_list' );
		}else{
			$this->logger->info( "please login before delete any VNF" );
			return new JsonResponse( $this->logger->info( "please login before delete any VNF" ));

		}
	}

	/**
	 * Deletes a Security entity.
	 *
	 * @Route("/{_locale}/createflavour", methods={"GET", "POST"}, name="slicecreateflavour")
	 */

	public function createFlavour( Request $request ): Response {

		$flavourkeys = new Flavourkeys();
		$form        = $this->createForm( FlavourkeysType::class, $flavourkeys );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$em = $this->getDoctrine()->getManager();
			$em->persist( $flavourkeys );
			$em->flush();

			// Flash messages are used to notify the user about the result of the
			// actions. They are deleted automatically from the session as soon
			// as they are accessed.
			// See https://symfony.com/doc/current/book/controller.html#flash-messages
			$this->addFlash( 'success', 'Flavour key created successfully' );

			return $this->redirectToRoute( 'slicecreateflavour' );
		}

		return $this->render( 'slicemanager/flavourkeysAdd.html.twig', [
			'post'   => $flavourkeys,
			'form'   => $form->createView(),
			'backId' => $request->query->get( 'backId' )
		] );
	}

	/**
	 * Deletes a links entity.
	 *
	 * @Route("/{_locale}/createnetworks", methods={"GET", "POST"}, name="slicecreatenetworks")
	 */

	public function createNetowrks( Request $request ): Response {
		$virtuallink = new Virtuallink();
		$form        = $this->createForm( VirtuallinkType::class, $virtuallink );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$em = $this->getDoctrine()->getManager();
			$em->persist( $virtuallink );
			$em->flush();

			// Flash messages are used to notify the user about the result of the
			// actions. They are deleted automatically from the session as soon
			// as they are accessed.
			// See https://symfony.com/doc/current/book/controller.html#flash-messages
			$this->addFlash( 'success', 'Security group created successfully' );

			return $this->redirectToRoute( 'slicecreatenetworks' );
		}

		return $this->render( 'slicemanager/virtuallinkAdd.html.twig', [
			'post'   => $virtuallink,
			'form'   => $form->createView(),
			'backId' => $request->query->get( 'backId' )
		] );
	}

	/**
	 * Deletes a Security entity.
	 *
	 * @Route("/slicemanager/ajax"),  name="ajax")
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


}
