<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\Form\Type\RuleType;
use App\Form\Type\Image;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class RulesType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name',		TextType::class, array('attr' => array('class' => 'long')))
			->add('ruleType',	RuleType::class)
			->add('isActive',	CheckboxType::class, array('required' => false))
			->add('iconName',	Image::class, array('required' => false))
			->add('action',		EntityType::class, array('class' => 'App\Entity\Upv6\Actions', 'choice_label' => 'internalName'))
			->add('rule',		TextareaType::class, array('required' => false, 'attr' => array('class' => 'editor')))
			->add('comment',	TextareaType::class, array('required' => false, 'attr' => array('class' => 'comments')))
			;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'iot6\SmartItBundle\Entity\Rules'
		));
	}
	
	public function getName()
	{
		return 'iot6_smartItBundle_rulesType';
	}
}
