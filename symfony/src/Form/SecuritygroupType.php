<?php

namespace App\Form;

use App\Entity\Gui\Securitygroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class SecuritygroupType extends AbstractType {


	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add('name', TextType::class, [
				'help' => 'add a new security groups',
			]);
	}

}
