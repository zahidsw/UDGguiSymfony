<?php

namespace App\Form;

use App\Entity\Gui\Flavourkeys;
use App\Entity\Gui\Pop;
use App\Entity\Gui\Virtuallink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class EricssonSlicemanagerType extends AbstractType {


	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'slicename', TextType::class ,[
				'help' => 'please enter the slice name',
			])
			->add( 'slicedescription', TextType::class,[
				'help' => 'Slice description',
			] )
			->add( 'slcieprovider', TextType::class, [
			'help' => 'Provider of the slice',
			])

			->add( 'flavourkeys', EntityType::class, [// drop down multiple selection
				'class'        => Flavourkeys::class,
				'choice_label' => function ( Flavourkeys $flavourkeys ) {
					return sprintf( $flavourkeys->getName());
				},
				'expanded'     => false,
				'multiple'     => true,
				'help' => 'avaliable flavour for the image ',
			] )
			->add( 'virtuallink', EntityType::class, [// drop down multiple selection
				'class'        => Virtuallink::class,
				'choice_label' => function ( Virtuallink $virtuallink ) {
					return sprintf( $virtuallink->getNeworkname());
				},
				'expanded'     => false,
				'multiple'     => true,
				'help' => 'Avaliable Network links',
			]);

	}

}
