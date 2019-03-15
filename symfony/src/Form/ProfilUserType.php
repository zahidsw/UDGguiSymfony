<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProfilUserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('email', TextType::class, array('attr' => array('class' => 'long')))
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
		return 'iot6_configBundle_profilUserType';
	}
}
