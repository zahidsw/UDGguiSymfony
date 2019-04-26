<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use iot6\SmartItBundle\Form\Type\RuleType;
use iot6\SmartItBundle\Form\Type\Image;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RoomsType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name',			TextType::class, array('attr' => array('class' => 'long')))
			->add('floor',			EntityType::class, array('class' => 'App\Entity\Upv6\Floors', 'choice_label' => 'name'))
			->add('roomType',		EntityType::class, array('class' => 'App\Entity\Upv6\RoomTypes', 'choice_label' => 'name'))
			->add('roomState',		TextType::class)
			->add('importanceLevel',TextType::class)
			->add('energyLevel',	TextType::class)
			->add('espId',			TextType::class)
			;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'iot6\InteractBundle\Entity\Rooms'
		));
	}
	
	public function getName()
	{
		return 'iot6_interactBundle_roomsType';
	}
}
