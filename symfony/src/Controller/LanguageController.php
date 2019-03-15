<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class LanguageController extends AbstractController
{
    public function languageAction()
    {
    	$em_gui = $this->getDoctrine()->getManager();
    	
    	$languages = $em_gui->getRepository('App\Entity\Gui\Language')->findAll();
    	
    	$data["languages"] = $languages;
    	
        return $this->render('language/language.html.twig', $data);
    }
}
