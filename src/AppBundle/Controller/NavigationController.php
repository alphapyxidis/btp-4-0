<?php

// src/AppBundle/Controller/NavigationController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class NavigationController extends Controller
{
    /**
     * @Route("/chantier-du-futur", name="BTP 4.0 - le chantier du futur")
     */
    public function homepageAction()
    {
        $number = mt_rand(0, 100);

        return $this->render('navigation/chantier-du-futur.html.twig', array(
            'number' => $number,
        ));
    }
}

?>