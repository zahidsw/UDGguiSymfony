<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use iot6\SmartItBundle\Form\Type\RuleType;
use iot6\SmartItBundle\Form\Type\Image;

class RoomsType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name',			'text', array('attr' => array('class' => 'long')))
			->add('floor',			'entity', array('class' => 'iot6InteractBundle:Floors', 'property' => 'name'))
			->add('roomType',		'entity', array('class' => 'iot6InteractBundle:RoomTypes', 'property' => 'name'))
			->add('roomState',		'text')
			->add('importanceLevel','text')
			->add('energyLevel',	'text')
			->add('espId',			'text')
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
