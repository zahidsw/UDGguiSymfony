<?php

namespace App\Controller;

use App\Entity\Gui\Slicemanager;
use App\Form\SlicemanagerType;
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



class SlicemanagerController extends AbstractController
{
	private $logger;
	private $translator;

	public function __construct( LoggerInterface $logger, DataCollectorTranslator $translator ) {
		$this->logger     = $logger;
		$this->translator = $translator;
	}
    /**
     * @Route("/{_locale}/slicemanager", name="slice_list")
     */
	public function index(SlicemanagerRepository $slices): Response
	{

		$slices = $slices->findAll();

		return $this->render('slicemanager/index.html.twig', ['slices' => $slices]);
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

	public function new(Request $request): Response
	{
		$slice = new Slicemanager();
		$form = $this->createForm( SlicemanagerType::class, $slice );

		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$slice->setStatus(0);
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
			'form' => $form->createView(),
		] );
	}

	/**
	 * Displays a form to edit an existing Slicemanager entity.
	 *
	 * @Route("/{id<\d+>}/register",methods={"GET", "POST"}, name="slice_register")
	 */
	public function register(Request $request, Slicemanager $slice): Response
	{

		$ar = Yaml::parseFile("../tosca_file/TOSCA-Metadata/Metadata.yaml");
		$ar["name"] = $slice->getSlicename();
		$ar["description"] = $slice->getSlicedescription();
		$ar["provider"] = $slice->getSlcieprovider();
		$yaml = Yaml::dump($ar);
		file_put_contents('../tosca_file/TOSCA-Metadata/Metadata.yaml', $yaml);

		$ar = Yaml::parseFile("../tosca_file/Definitions/IoT_slice.yaml");
		$ar['tosca_definitions_version'] = $slice->getSlicename();
		$ar['description'] = $slice->getSlicedescription();
		$ar['metadata']['vendor'] = $slice->getSlcieprovider();
		$ar['topology_template']['node_templates']['UDGaaF']['properties']['vendor'] =$slice->getSlcieprovider();
		$i=0;
		foreach ($slice->getFlavourkeys() as $keys)
		{
			$ar['topology_template']['node_templates']['UDGaaF']['properties']['deploymentFlavour'][$i]['flavour_key']= $keys->getName();
			$i++;
		}
		$i=0;
		foreach ($slice->getVirtuallink() as $keys)
		{
			var_dump($keys->getNeworkname());
			var_dump($ar['topology_template']['node_templates']['UDGaaF']['requirements'][$i]['virtualLink']);
			$ar['topology_template']['node_templates']['UDGaaF']['requirements'][$i]['virtualLink']=  $keys->getNeworkname();

			$i++;
		}
		$ar['topology_template']['node_templates']['CP_UDG']['requirements'][1]['virtualLink']=  $keys->getNeworkname();
		$i=0;
		foreach ($slice->getPopinstance() as $keys)
		{
			$ar['topology_template']['node_templates']['VDU_UDG']['properties']['vim_instance_name'][$i]= $keys->getName();
			$i++;
		}

		$yaml = Yaml::dump($ar);
		file_put_contents('../tosca_file/Definitions/IoT_slice.yaml', $yaml);
		$output = shell_exec('cd ../tosca_file && zip -r IoT_slice.csar . -x ".*" -x "*/.*"');
		$process = Process::fromShellCommandline( '/home/mandint/slice-manager/slice_manager.py --tosca-file ../tosca_file/IoT_slice.csar');
		$process->run( function ( $type, $buffer ) {
			$this->logger->info( $buffer );
			$this->addFlash(
				'notice',
				$buffer
			);
		} );

		return $this->redirectToRoute('slice_list');
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
			$this->getDoctrine()->getManager()->flush();
			$this->addFlash( 'success', 'Slice updated successfully' );
			return $this->redirectToRoute( 'slice_list',  ['id' => $slice->getId()]);
		}
		return $this->render( 'slicemanager/edit.html.twig', [
			'slice' => $slice,
			'form' => $form->createView(),
		] );
	}

	//////////////////////////////////////////////
	/**
	 * Finds and displays a slice entity.
	 *
	 * @Route("/{id<\d+>}/show", methods={"GET","POST"}, name="slice_show")
	 */

	public function show(Slicemanager $slice): Response
	{

		// This security check can also be performed
		// using an annotation: @IsGranted("show", subject="post")
		// $this->denyAccessUnlessGranted('show', $post, 'Posts can only be shown to their authors.');

		return $this->render('slicemanager/show.html.twig', [
			'slice' => $slice,
		]);
	}

	/**
	 * Deletes a Pop entity.
	 *
	 * @Route("/{id<\d+>}/delete", methods={"GET","POST"}, name="slice_delete")
	 */

	public function deleteVno(Request $request, Slicemanager $slice, AuthenticationUtils $authenticationUtils): Response
	{

		if (!$authenticationUtils->getLastUsername()=='') {

			// Delete the popinstance associated with this blog post. This is done automatically
			// by Doctrine, except for SQLite (the database used in this application)
			// because foreign key support is not enabled by default in SQLite
			$slice->getPopinstance()->clear();
			$slice->getVirtuallink()->clear();
			$em = $this->getDoctrine()->getManager();
			$em->remove($slice);
			$em->flush();
			$this->addFlash('success', 'post.deleted_successfully');
			return $this->redirectToRoute('slice_list');
		}
		return $this->redirectToRoute('slice_list');
	}


}
