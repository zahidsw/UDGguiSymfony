<?php

namespace App\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
    protected function renderLogin(array $data)
    {
        $template = sprintf('FOSUserBundle:Security:login.html.%s', 'twig');
        
        
        // Images Controller
        $imgPath = "assets/images/site/loginImages/";
        $handle  = opendir($imgPath);
        
        //On parcoure chaque �l�ment du dossier
        while ($file = readdir($handle))
        {
        	//Si les fichiers sont des images
        	if(preg_match ("!(\.jpg|\.jpeg|\.gif|\.bmp|\.png)$!i", $file))
        	{
        		$listFile[] = $file;
        	}
        }
        
        shuffle($listFile);
        $data["images"] = $listFile;
        $data["imagePath"] = $imgPath;
        
        return $this->container->get('templating')->renderResponse($template, $data);
    }
}
