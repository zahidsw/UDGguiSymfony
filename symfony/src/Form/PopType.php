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
			->add( 'name', TextType::class, [
				'help' => 'Any name for the POP',
			] )
			->add( 'auth', TextType::class, [
				'help' => 'Give Authorization string ',
			] )
			->add( 'tenant', TextType::class, [
				'help' => 'tenant name must to be provide by administrator',
			] )
			->add( 'username', TextType::class, [
				'help' => 'User name for the pop',
			] )
			->add( 'Password', TextType::class, [
				'help' => 'Password for the creation of Pop',
			] )
			->add( 'type', ChoiceType::class, [
				'choices' => [
					'openstack' => 'openstack',
				],
				'help'    => 'type of cloud server to be connected',
			] )
			->add( 'securitygroup', EntityType::class, [// drop down multiple selection
				'class'        => Securitygroup::class,
				'choice_label' => function ( Securitygroup $user ) {
					return sprintf( $user->getName() );
				},
				'help'         => 'Available security groups',
				'expanded'     => false,
				'multiple'     => true,
			] )
			->add( 'keypair', TextType::class, [
					'required' => false,
					'help'     => 'available security keys can be attached to connection',
				]
			)
			->add( 'locationname', TextType::class, [
				'required' => false,
				'help'     => 'Location Name available',
			] )
			->add( 'latitude', TextType::class, [
				'required' => false,
				'help'     => 'Location Latitude',
			] )
			->add( 'longitude', TextType::class, [
				'required' => false,
				'help'     => 'Location Longitude',
			] );
	}

}
