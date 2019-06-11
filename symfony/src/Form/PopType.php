<?php

namespace App\Form;

use App\Entity\Gui\Securitygroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class PopType extends AbstractType {


	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add('name', TextType::class, [
				'help' => 'The ZIP/Postal code for your credit card\'s billing address.',
			])

			->add( 'auth', TextType::class )
			->add( 'tenant', TextType::class )
			->add( 'username', TextType::class )
			->add( 'Password', TextType::class )
			->add( 'type', ChoiceType::class, [
				'choices' => [
					'openstack' => 'openstack',
				],
			] )
			->add( 'securitygroup', EntityType::class, [// drop down multiple selection
				'class'        => Securitygroup::class,
				'choice_label' => function ( Securitygroup $user ) {
					return sprintf( $user->getName() );
				},
				'expanded'     => false,
				'multiple'     => true,
			] )
			->add( 'keypair', TextType::class )
			->add( 'locationname', TextType::class )
			->add( 'latitude', TextType::class )
			->add( 'longitude', TextType::class);
	}

}
