<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use iot6\SmartItBundle\Form\Type\RuleType;
use iot6\SmartItBundle\Form\Type\Image;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class BuildingsType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name',			TextType::class, array('attr' => array('class' => 'long')))
			->add('file',			FileType::class, array('required' => false))
			->add('importanceLevel',TextType::class)
			->add('energyLevel',	TextType::class)
			->add('espId',			TextType::class)
			->add('buildingState',	TextType::class)
			;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'App\Entity\Upv6\Buildings'
		));
	}
	
	public function getName()
	{
		return 'iot6_interactBundle_buildingsType';
	}
}
