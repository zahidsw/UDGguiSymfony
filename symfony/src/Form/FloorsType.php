<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use iot6\SmartItBundle\Form\Type\RuleType;
use iot6\SmartItBundle\Form\Type\Image;

class FloorsType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name',		'text', array('attr' => array('class' => 'long')))
			->add('building',	'entity', array('class' => 'iot6InteractBundle:Buildings', 'property' => 'name'))
			->add('file',		'file', array('required' => false))
			->add('positionZ',	'text')
			;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'iot6\InteractBundle\Entity\Floors'
		));
	}
	
	public function getName()
	{
		return 'iot6_interactBundle_floorsType';
	}
}
