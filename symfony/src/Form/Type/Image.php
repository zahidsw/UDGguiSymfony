<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class Image extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$imgPath = "assets/images/scenarios/";
		$handle  = opendir($imgPath);
		
		$listFile = array();
		
		$listFile[null] = "Aucune";
		
		//On parcoure chaque �l�ment du dossier
		while ($file = readdir($handle))
		{
			//Si les fichiers sont des images
			if(preg_match ("!(\.jpg|\.jpeg|\.gif|\.bmp|\.png)$!i", $file))
			{
				$listFile[$file] = $file;
			}
		}
		
		
		$resolver->setDefaults(array(
			'choices' => array_flip($listFile)
				)
		);
	}
	
	public function getParent()
	{
		return ChoiceType::class;
	}
	
	public function getName()
	{
		return 'imageType';
	}
}