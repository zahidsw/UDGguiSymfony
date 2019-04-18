<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use iot6\SmartItBundle\Form\Type\RuleType;
use iot6\SmartItBundle\Form\Type\Image;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SchedulesType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('scheduleCron',	TextType::class)
			->add('rule',			EntityType::class, array('class' => 'App\Entity\Upv6\Rules', 'choice_label' => 'name'))
			->add('target',			EntityType::class, array('class' => 'App\Entity\Upv6\Targets', 'choice_label' => 'targetName', 'required' => false))
			->add('isActive',		CheckboxType::class, array('required' => false))
			->add('comment',		TextareaType::class, array('required' => false, 'attr' => array('class' => 'comments')))
			;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'App\Entity\Upv6\\Schedules'
		));
	}
	
	public function getName()
	{
		return 'iot6_smartItBundle_schedulesType';
	}
}
