<?php

// src/AppBundle/Controller/NavigationController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class NavigationController extends Controller
{
    /**
     * @Route("/chantier-du-futur", name="accueil")
     */
    public function accueilAction()
    {
        return $this->render('navigation/chantier-du-futur.html.twig',
        );
    }

    /**
     * @Route("/presentation", name="presentation")
     */
    public function presentationAction()
    {
        return $this->render('navigation/presentation.html.twig',
        );
    }    
}

?>