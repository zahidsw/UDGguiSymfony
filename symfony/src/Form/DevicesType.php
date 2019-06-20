<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DevicesType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('assignedName',	TextType::class, array('attr' => array('class' => 'long')))
			->add('description',	TextareaType::class, array('attr' => array('class' => 'comments')))
			->add('comments',		TextareaType::class, array('attr' => array('class' => 'comments')))
			->add('family',			EntityType::class, array('class' => 'App\Entity\Upv6\Families', 'choice_label' => 'internalName'))
			->add('category',		EntityType::class, array('class' => 'App\Entity\Upv6\Categories', 'choice_label' => 'internalName'))
			->add('protocol',		EntityType::class, array('class' => 'App\Entity\Upv6\Protocols', 'choice_label' => 'name'))
			->add('module',			EntityType::class, array('class' => 'App\Entity\Upv6\Modules', 'choice_label' => 'name'))
			->add('card',			EntityType::class, array('class' => 'App\Entity\Upv6\Cards', 'choice_label' => 'name'))
			->add('model',			EntityType::class, array('class' => 'App\Entity\Upv6\Models', 'choice_label' => 'name'))
			->add('room',			EntityType::class, array('class' => 'App\Entity\Upv6\Rooms', 'choice_label' => 'name'))
			->add('positionX',		TextType::class)
			->add('positionY',		TextType::class)
			->add('positionZ',		TextType::class)
			->add('longitude',		TextType::class)
			->add('latitude',		TextType::class)
			;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'App\Entity\Upv6\Devices'
		));
	}
	
	public function getName()
	{
		return 'iot6_interactBundle_devicesType';
	}
}
