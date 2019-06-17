<?php

namespace App\Form;

use App\Entity\Gui\IotConfiguration;
use App\Entity\Gui\Slicemanager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\PasswordType;


class IotConfigurationType extends AbstractType {


	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'targetTempSensName', TextType::class ,[
				'help' => 'Target temperature sensor name',
			])
		->add( 'targetTempSensURL', TextType::class,[
			'help' => 'URL of Target temperature resource ',
		] )
			->add( 'tempThreshold', TextType::class,[
				'help' => 'Temperature threshold value in (℃)',
			] )

			->add( 'emergencySliceName', TextType::class,[
				'help' => 'Name of the emergency slice',
			] )
			->add( 'cameraIP', TextType::class ,[
				'help' => 'Camera Port Number',
			])
			->add( 'cameraPort', TextType::class ,[
				'help' => 'Camera Port Number',
			])

			->add( 'cameraUser', TextType::class ,[
				'help' => 'Camera User Name',
			])
			->add( 'cameraPassword', PasswordType::class ,[
				'help' => 'Camera User Password',
			])
			->add( 'minimumBandwidth', TextType::class ,[
				'help' => 'Minimun bandwidth (bits/s)',
			])

			->add( 'maxBandwidth', TextType::class ,[
				'help' => 'Maximum bandwidth (bits/s)',
			])
		->add( 'slicemanager', EntityType::class, [// drop down multiple selection
			'class'        => Slicemanager::class,
			'choice_label' => function ( Slicemanager $slicemanager ) {
				return sprintf( $slicemanager->getSlicename());
			},
			'help' => 'please select the slice which suppose to be updated with these parameters',
			'placeholder' => 'Available Slices',
			'expanded'     => false,
			'multiple'     => false,
		]);
	}



}
