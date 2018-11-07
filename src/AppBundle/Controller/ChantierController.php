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
     * Finds and displays a planning for a chantier entity.
     *
     * @Route("/{slug}/planning", name="planning_chantier")
     * @Method("GET")
     */
    public function planningAction(Chantier $chantier)
    {
        return $this->render('chantier/planning.html.twig', array(
            'chantier' => $chantier,
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
