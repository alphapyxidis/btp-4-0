<?php

// src/AppBundle/Controller/NavigationController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

// Import the BinaryFileResponse
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use AppBundle\Entity\Chantier;

class NavigationController extends Controller
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
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../web/ressources/';
        $filename = "BTP 4.0 - le chantier du futur.pdf";

        // This should return the file located in /mySymfonyProject/web/public-resources/TextFile.txt
        // to being viewed in the Browser
        return new BinaryFileResponse($publicResourcesFolderPath.$filename);        
    }


     /**
     * Lists all chantier in a given area.
     *
     * @Route("/api/autour-de-moi", name="chantier_neighborhood")
     * @Method("GET")
     */
    public function neighborhoodAction(Request $request)
    {

        $south = $request->query->get('south', '48.815458');
        $west = $request->query->get('west', '2.2279671');

        $north = $request->query->get('north', '48.911599');
        $east = $request->query->get('east', '2.429292');

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT c FROM AppBundle:Chantier c JOIN c.adresse a WHERE a.lat between " .$south . " and ". $north ." and a.lon between " . $west . " and " . $east;
        $query = $em->createQuery($dql);
        $chantiers= $query->getResult();

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($chantiers as $chantier) {  
               $temp = array(
                    'nom' => $chantier->getNom(),  
                    'slug' => $chantier->getSlug(),  
                    'rue' => $chantier->getAdresse()->getRue(),  
                    'ville' => $chantier->getAdresse()->getVille(),  
                    'lat' => $chantier->getAdresse()->getLat(),  
                    'lon' => $chantier->getAdresse()->getLon(),  
               );   
               $jsonData[$idx++] = $temp;  
            } 

            $response = new JsonResponse($jsonData);


            return $response; 
         } else { 
            return $this->render('chantier/carto.html.twig', array('chantiers'=>$chantiers)); 
         }         
    
    }

    /**
     * @Route("/demo", name="demo")
     */
    public function demoAction(Request $request)
    {
        return $this->redirectToRoute('chantier_neighborhood');  
    }

    /**
     * @Route("/demo/plan-installation-chantier", name="pic")
     */
    public function picAction()
    {
        return $this->render('chantier/exemple-pic.html.twig'); 
    }}

?>