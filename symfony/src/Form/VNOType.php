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
			->add('AuthorisationURL',  TextType::class)
			->add('Tenant',	           TextType::class)
            ->add('Username',          TextType::class)
			->add('Password',          TextType::class)            
            ->add('Type', ChoiceType::class, [
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
			;
	}
	
	
}
