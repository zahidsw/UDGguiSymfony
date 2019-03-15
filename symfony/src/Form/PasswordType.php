<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use iot6\SmartItBundle\Form\Type\RuleType;
use iot6\SmartItBundle\Form\Type\Image;

class PasswordType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('password',			'text')
			;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'iot6\UserBundle\Entity\User'
		));
	}
	
	public function getName()
	{
		return 'iot6_configBundle_passwordType';
	}
}
