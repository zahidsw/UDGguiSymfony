<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DevicesType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('assignedName',	'text', array('attr' => array('class' => 'long')))
			->add('description',	'textarea', array('attr' => array('class' => 'comments')))
			->add('comments',		'textarea', array('attr' => array('class' => 'comments')))
			->add('family',			'entity', array('class' => 'iot6InteractBundle:Families', 'property' => 'internalName'))
			->add('category',		'entity', array('class' => 'iot6InteractBundle:Categories', 'property' => 'internalName'))
			->add('protocol',		'entity', array('class' => 'iot6InteractBundle:Protocols', 'property' => 'name'))
			->add('module',			'entity', array('class' => 'iot6InteractBundle:Modules', 'property' => 'name'))
			->add('card',			'entity', array('class' => 'iot6InteractBundle:Cards', 'property' => 'name'))
			->add('model',			'entity', array('class' => 'iot6InteractBundle:Models', 'property' => 'name'))
			->add('room',			'entity', array('class' => 'iot6InteractBundle:Rooms', 'property' => 'name'))
			->add('positionX',		'text')
			->add('positionY',		'text')
			->add('positionZ',		'text')
			;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'iot6\InteractBundle\Entity\Devices'
		));
	}
	
	public function getName()
	{
		return 'iot6_interactBundle_devicesType';
	}
}
