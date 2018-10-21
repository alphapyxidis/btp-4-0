<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chantier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Chantier controller.
 *
 * @Route("demo/chantier")
 */
class ChantierController extends Controller
{
    /**
     * rebuild slugs for all chantier entities.
     *
     * @Route("s/rebuild-slug", name="chantier_reslug")
     * @Method("GET")
     */
    public function reslugAction(Request $request)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $repository = $this->getDoctrine()->getRepository(Chantier::class);

        $chantiers = $repository->FindAll();

        foreach($chantiers as $chantier){
            $newName = rtrim($chantier->getNom());
            $chantier->setNom($newName);
            $em->persist($chantier);
            $em->flush();
         }

        return $this->redirectToRoute('chantier_index', array('page' => 1));
    }
    
     /**
     * Lists all chantier in a given area.
     *
     * @Route("/autour-de-moi", name="chantier_neighborhood")
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

        return $this->render('chantier/neighborhood.json.twig', array('chantiers'=>$chantiers)); 

        // if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {  
        //     $jsonData = array();  
        //     $idx = 0;  
        //     foreach($chantiers as $chantier) {  
        //        $temp = array(
        //             'nom' => $chantier->getNom(),  
        //             'slug' => $chantier->getSlug(),  
        //             'rue' => $chantier->getAdresse()->getRue(),  
        //             'ville' => $chantier->getAdresse()->getVille(),  
        //             'lat' => $chantier->getAdresse()->getLat(),  
        //             'lon' => $chantier->getAdresse()->getLon(),  
        //        );   
        //        $jsonData[$idx++] = $temp;  
        //     } 

        //     $response = new JsonResponse($jsonData);

        //     return $response; 
        //  } else { 
        //     return $this->render('chantier/carto.html.twig', array('chantiers'=>$chantiers)); 
        //  }         
    
    }

     /**
     * Lists all chantier entities.
     *
     * @Route("s/", name="chantier_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT c FROM AppBundle:Chantier c";
        $query = $em->createQuery($dql);
    
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            8/*limit per page*/
        );

        return $this->render('chantier/index.html.twig', array('pagination' => $pagination));
    
    }

    /**
     * Creates a new chantier entity.
     *
     * @Route("/ajouter", name="chantier_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $chantier = new Chantier();
        $form = $this->createForm('AppBundle\Form\ChantierType', $chantier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($chantier);
            $em->flush();

            return $this->redirectToRoute('chantier_show', array('slug' => $chantier->getSlug()));
        }

        return $this->render('chantier/new.html.twig', array(
            'chantier' => $chantier,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a chantier entity.
     *
     * @Route("/{slug}", name="chantier_show")
     * @Method("GET")
     */
    public function showAction(Chantier $chantier)
    {
        $deleteForm = $this->createDeleteForm($chantier);

        return $this->render('chantier/show.html.twig', array(
            'chantier' => $chantier,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing chantier entity.
     *
     * @Route("/modifier/{slug}", name="chantier_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Chantier $chantier)
    {
        $deleteForm = $this->createDeleteForm($chantier);
        $editForm = $this->createForm('AppBundle\Form\ChantierType', $chantier);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('chantier_edit', array('slug' => $chantier->getSlug()));
        }

        return $this->render('chantier/edit.html.twig', array(
            'chantier' => $chantier,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a chantier entity.
     *
     * @Route("/supprimer/{slug}", name="chantier_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Chantier $chantier)
    {
        $form = $this->createDeleteForm($chantier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($chantier);
            $em->flush();
        }

        return $this->redirectToRoute('chantier_index');
    }

    /**
     * Creates a form to delete a chantier entity.
     *
     * @param Chantier $chantier The chantier entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Chantier $chantier)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('chantier_delete', array('slug' => $chantier->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
