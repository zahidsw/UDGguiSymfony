<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class RuleType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
            'choices' => [
                'Regle' => '1',
				'Scenario' => '2'
            ],
        ]);
	}


	
	public function getParent()
	{
		return ChoiceType::class;
	}
	
	public function getName()
	{
		return 'ruleType';
	}
}