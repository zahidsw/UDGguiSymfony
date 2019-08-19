<?php

namespace App\Controller;

use App\Entity\Gui\IotConfiguration;
use App\Form\IotConfigurationType;
use App\Repository\Gui\IotConfigurationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Yaml\Yaml;
use App\Entity\Gui\Securitygroup;
use App\Form\PopType;
use App\Form\SecuritygroupType;
use App\Repository\Gui\PopRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Process\Exception\ProcessFailedException;

class IotConfigurationController extends AbstractController {
	private $logger;
	private $translator;


	public function __construct( LoggerInterface $logger, DataCollectorTranslator $translator ) {
		$this->logger     = $logger;
		$this->translator = $translator;
	}

	/**
	 * @Route("/{_locale}/listiot", name="iot_configuration")
	 */
	public function index( IotConfigurationRepository $iotconfig ): Response {

		$iotconfig = $iotconfig->findAll();

		return $this->render( 'iot_configuration/index.html.twig', [ 'iotconfigs' => $iotconfig ] );
	}

	/**
	 * Creates a new iot configration entity.
	 *
	 * @Route("/{_locale}/newconfig", methods={"GET", "POST"}, name="configuration_new")
	 *
	 * NOTE: the Method annotation is optional, but it's a recommended practice
	 * to constraint the HTTP methods each controller responds to (by default
	 * it responds to all methods).
	 */

	public function new( Request $request ): Response {
		$iotconfig = new IotConfiguration();
		$form      = $this->createForm( IotConfigurationType::class, $iotconfig );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$iotconfig->setStatus(0);
			$em = $this->getDoctrine()->getManager();
			$em->persist( $iotconfig );
			$em->flush();

			// Flash messages are used to notify the user about the result of the
			// actions. They are deleted automatically from the session as soon
			// as they are accessed.
			// See https://symfony.com/doc/current/book/controller.html#flash-messages
			$this->addFlash( 'success', 'slice created_successfully' );

			return $this->redirectToRoute( 'iot_configuration' );
		}

		return $this->render( 'iot_configuration/new.html.twig', [
			'slice' => $iotconfig,
			'form'  => $form->createView(),
		] );
	}

	/**
	 * Displays a form to edit an existing IotConfiguration entity.
	 *
	 * @Route("/{id<\d+>}/iotregister",methods={"GET", "POST"}, name="iot_register")
	 */
	public function register(Request $request, IotConfiguration $iot_configuration): Response
	{
		$iot_configuration->getSlicemanager()->getSliceid();

		$ar = Yaml::parseFile("../tosca_file/Definitions/IoT_slice.yaml");
		$ar['topology_template']['node_templates']['UDGaaF']['properties']['configurations']['configurationParameters'][6]['target_temp_Sens_name']= $iot_configuration->getTargetTempSensName();
		$ar['topology_template']['node_templates']['UDGaaF']['properties']['configurations']['configurationParameters'][7]['target_temp_sens_URL']= $iot_configuration->getTargetTempSensURL();
		$ar['topology_template']['node_templates']['UDGaaF']['properties']['configurations']['configurationParameters'][8]['temp_threshold']= $iot_configuration->getTempThreshold();
		$ar['topology_template']['node_templates']['UDGaaF']['properties']['configurations']['configurationParameters'][9]['emergency_slice_name']= $iot_configuration->getEmergencySliceName();
		$ar['topology_template']['node_templates']['UDGaaF']['properties']['configurations']['configurationParameters'][10]['camera_IP']= $iot_configuration->getCameraIP();
		$ar['topology_template']['node_templates']['UDGaaF']['properties']['configurations']['configurationParameters'][11]['camera_user']= $iot_configuration->getCameraUser();
		$ar['topology_template']['node_templates']['UDGaaF']['properties']['configurations']['configurationParameters'][12]['camera_password']= $iot_configuration->getCameraPassword();
		$ar['topology_template']['node_templates']['UDGaaF']['properties']['configurations']['configurationParameters'][13]['minimum_bandwidth']= $iot_configuration->getMinimumBandwidth();
		$ar['topology_template']['node_templates']['UDGaaF']['properties']['configurations']['configurationParameters'][14]['max_bandwidth']= $iot_configuration->getMaxBandwidth();
		$iot_configuration->setStatus(1);
		var_dump($ar['topology_template']['node_templates']['UDGaaF']['properties']['configurations']['configurationParameters']);



		$yaml = Yaml::dump($ar);

		file_put_contents('../tosca_file/Definitions/IoT_slice.yaml', $yaml);
		$output = shell_exec('cd ../tosca_file && zip -r IoT_slice.csar . -x ".*" -x "*/.*"');
		$command ='/home/mandint/slice-manager/slice_manager.py --tosca-file ../tosca_file/IoT_slice.csar';
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
				$iot_configuration->setStatus(1);
				$em = $this->getDoctrine()->getManager();
				$em->persist( $iot_configuration );
				$em->flush();
			}

		} catch (ProcessFailedException $exception) {
			echo $exception->getMessage();
			return $this->redirectToRoute( 'iot_configuration' );
		}

		return $this->redirectToRoute('iot_configuration');
	}


	/**
	 * Deletes a Security entity.
	 *
	 * @Route("/iotconfig/ajax"), methods={"POST"},  name="iotajax")
	 */

	public function ajaxAction( Request $request ) {

		if ( $request->isXmlHttpRequest() ) {
			$template_ids = $request->request->get( 'template_id' );
			//		var_dump($template_ids);
		}

		if ( $request->isXmlHttpRequest() || $template_ids == 'temp' ) {
			//	var_dump("asdfsd");
			//	die("sdfsd");
			//	$iotconfig = new IotConfiguration();
			//	$form = $this->createForm( IotConfigurationType::class, $iotconfig );
			//	$form->handleRequest( $request );
			/*
						if ( $form->isSubmitted() && $form->isValid() ) {
							$em = $this->getDoctrine()->getManager();
							$em->persist( $iotconfig );
							$em->flush();

							// Flash messages are used to notify the user about the result of the
							// actions. They are deleted automatically from the session as soon
							// as they are accessed.
							// See https://symfony.com/doc/current/book/controller.html#flash-messages
							$this->addFlash( 'success', 'slice created_successfully' );

							return $this->redirectToRoute( 'slice_list' );
						}
			*/
			//	return $this->JsonResponse();

			/*	$template_ids = $request->request->get( 'template_id' );
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
			*/

			return $this->json( [ 'username' => $template_ids ] );
		}

	}

	/**
	 * Displays a form to edit an existing slice entity.
	 *
	 * @Route("/{_locale}/{id<\d+>}/editiotconfig",methods={"GET", "POST"}, name="iot_edit")
	 */

	public function edit( Request $request, IotConfiguration $iot ): Response {

		$form = $this->createForm( IotConfigurationType::class, $iot );
		$form->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {
			$iot->setStatus(0);
			$this->getDoctrine()->getManager()->flush();
			$this->addFlash( 'success', 'Slice updated successfully' );

			return $this->redirectToRoute( 'iot_configuration', [ 'id' => $iot->getId() ] );
		}

		return $this->render( 'iot_configuration/edit.html.twig', [
			'slice' => $iot,
			'form'  => $form->createView(),
		] );
	}

	//////////////////////////////////////////////

	/**
	 * Finds and displays a slice entity.
	 *
	 * @Route("/{id<\d+>}/iotconfigshow", methods={"GET","POST"}, name="iot_show")
	 */

	public function show( IotConfiguration $iot ): Response {

		// This security check can also be performed
		// using an annotation: @IsGranted("show", subject="post")
		// $this->denyAccessUnlessGranted('show', $post, 'Posts can only be shown to their authors.');

		return $this->render( 'iot_configuration/show.html.twig', [
			'iotconfig' => $iot,
		] );
	}

	/**
	 * Deletes a Pop entity.
	 *
	 * @Route("/{id<\d+>}/iotdelete", methods={"GET","POST"}, name="iot_delete")
	 */

	public function deleteVno( Request $request, IotConfiguration $slice, AuthenticationUtils $authenticationUtils ): Response {

		if ( ! $authenticationUtils->getLastUsername() == '' ) {

			// Delete the popinstance associated with this blog post. This is done automatically
			// by Doctrine, except for SQLite (the database used in this application)
			// because foreign key support is not enabled by default in SQLite
			//	$slice->getPopinstance()->clear();
			//	$slice->getVirtuallink()->clear();
			$em = $this->getDoctrine()->getManager();
			$em->remove( $slice );
			$em->flush();
			$this->addFlash( 'success', 'post.deleted_successfully' );

			return $this->redirectToRoute( 'slice_list' );
		}

		return $this->redirectToRoute( 'slice_list' );
	}


}
