<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FamiliesType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('internalName',	'text', array('attr' => array('class' => 'long')))
			->add('file',			'file', array('required' => false))
			;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'iot6\InteractBundle\Entity\Families'
		));
	}
	
	public function getName()
	{
		return 'iot6_interactBundle_familiesType';
	}
}
