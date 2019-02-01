<?php

// src/AppBundle/Controller/BlogController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

// Import the BinaryFileResponse
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use AppBundle\Entity\Chantier;

class BlogController extends Controller
{
    /**
     * @Route("/chantier-du-futur", name="accueil")
     */
    public function accueilAction()
    {
        return $this->render('navigation/chantier-du-futur.html.twig');
    }

    /**
     * @Route("/presentation", name="presentation")
     */
    public function presentationAction()
    {
        return $this->render('navigation/presentation.html.twig');
    } 

    /**
     * @Route("/experimentation", name="experimentation")
     */
    public function experimentationAction()
    {
        return $this->render('navigation/experimentation.html.twig');
    } 

    /**
     * @Route("/resultats", name="resultats")
     */
    public function resultatsAction()
    {
        return $this->render('navigation/resultats.html.twig');
    }    

    /**
     * @Route("/BTP 4.0 - le chantier du futur.pdf", name="rapport")
     */
    public function rapportAction()
    {
        // i.e Sending a file from the resources folder in /web
        // in this example, the TextFile.txt needs to exist in the server
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../web/data/';
        $filename = "BTP 4.0 - le chantier du futur.pdf";

        // This should return the file located in /mySymfonyProject/web/public-resources/TextFile.txt
        // to being viewed in the Browser
        return new BinaryFileResponse($publicResourcesFolderPath.$filename);        
    }


    /**
     * @Route("/lab", name="lab")
     */
    public function labAction(Request $request)
    {
        return $this->render('chantier/carto.html.twig'); 
    }

    /**
     * @Route("/lab/plan-installation-chantier", name="pic")
     */
    public function picAction()
    {
        return $this->render('chantier/exemple-pic.html.twig'); 
    }}

?>