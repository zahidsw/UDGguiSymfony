<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use iot6\SmartItBundle\Form\Type\RuleType;
use iot6\SmartItBundle\Form\Type\Image;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FloorsType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name',		TextType::class, array('attr' => array('class' => 'long')))
			->add('building',	EntityType::class, array('class' => 'App\Entity\Upv6\Buildings', 'choice_label' => 'name'))
			->add('file',		FileType::class, array('required' => false))
			->add('positionZ',	TextType::class)
			;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'App\Entity\Upv6\Floors'
		));
	}
	
	public function getName()
	{
		return 'iot6_interactBundle_floorsType';
	}
}
