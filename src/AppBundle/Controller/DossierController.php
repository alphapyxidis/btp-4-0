<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dossier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * dossier controller.
 *
 * @Route("demo/dossier")
 */
class DossierController extends Controller
{
    /**
     * Lists all dossier entities.
     *
     * @Route("s/", name="dossier_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT d FROM AppBundle:Dossier d";
        $query = $em->createQuery($dql);
    
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('dossier/index.html.twig', array('pagination' => $pagination));
    }

    /**
     * Finds and displays a dossier entity.
     *
     * @Route("/{id}", name="dossier_show", defaults={"id" = null}, requirements={"id"="\d+"})
     * @Method("GET")
     */
    public function showAction(dossier $dossier)
    {
        $deleteForm = $this->createDeleteForm($dossier);

        return $this->render('dossier/show.html.twig', array(
            'dossier' => $dossier,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Deletes a dossier entity.
     *
     * @Route("/{id}", name="dossier_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, dossier $dossier)
    {
        $form = $this->createDeleteForm($dossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
       
            $em = $this->getDoctrine()->getManager();
            $em->remove($dossier);
            $em->flush();
        }

        return $this->redirectToRoute('dossier_index');
    }

    /**
     * Creates a form to delete a dossier entity.
     *
     * @param dossier $dossier The dossier entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(dossier $dossier)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('dossier_delete', array('id' => $dossier->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
