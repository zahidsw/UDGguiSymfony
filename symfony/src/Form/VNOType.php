<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class VNOType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name',			   TextType::class)
			->add('auth',  TextType::class)
			->add('tenant',	           TextType::class)
            ->add('username',          TextType::class)
			->add('Password',          \Symfony\Component\Form\Extension\Core\Type\PasswordType::class)
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'openstack' => 'openstack',
                ],
            ])
            ->add('KeyPair',	       TextType::class)
            ->add('SecurityGroups', ChoiceType::class, [// drop down multiple selection
                'choices'  => [
                    'default' => 'default',
                    'ICMP campus' => 'ICMP campus',
                    'ssh-fc00' => 'ssh-fc00',
                    'udg_network'=> 'udg_network',
                    'ssh-campus' => 'ssh-campus',
                    'icmp6' => 'icmp6',
                    'icmp10.0.0.0' => 'icmp10.0.0.0',
                    'openbaton' => 'openbaton',
                ],
                'multiple' => true,
                'expanded' => false,
            ])
			->add('locationname',			   TextType::class)
			->add('latitude',  TextType::class)
			->add('longitude',	           TextType::class)

		;
	}
	
}
