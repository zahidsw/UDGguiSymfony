<?php

namespace App\Form;

use App\Entity\Gui\IotConfiguration;
use App\Entity\Gui\Slicemanager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
				'required' => false,
			])
		->add( 'targetTempSensURL', TextType::class,[
			'help' => 'URL of Target temperature resource ',
			'required' => false,
		] )
			->add( 'tempThreshold', TextType::class,[
				'help' => 'Temperature threshold value in (℃)',
				'required' => false,
			] )

			->add( 'emergencySliceName', ChoiceType::class,[
				'help' => 'Choose Trigger action',
				'placeholder' => 'Choose Slice type',
				'choices'  => [
					'Video slice' => 'video',
					'SMS alert' => 'sms',
					'Email alert' => 'email',
				],

			])
			->add( 'cameraIP', TextType::class ,[
				'help' => 'Camera Port Number',
				'required' => false,
				'attr'=> array('readonly' => true)

			])
			->add( 'cameraPort', TextType::class ,[
				'help' => 'Camera Port Number',
				'required' => false,
				'attr'=> array('readonly' => true)

			])

			->add( 'cameraUser', TextType::class ,[
				'help' => 'Camera User Name',
				'required' => false,
				'attr'=> array('readonly' => true)
			])
			->add( 'cameraPassword', PasswordType::class ,[
				'help' => 'Camera User Password',
				'required' => false,
				'attr'=> array('readonly' => true)
			])
			->add( 'minimumBandwidth', TextType::class ,[
				'help' => 'Minimun bandwidth (bits/s)',
				'required' => false,
				'attr'=> array('readonly' => true)
			])

			->add( 'maxBandwidth', TextType::class ,[
				'help' => 'Maximum bandwidth (bits/s)',
				'required' => false,
				'attr'=> array('readonly' => true)
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
