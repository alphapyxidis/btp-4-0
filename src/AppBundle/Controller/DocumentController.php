<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use League\Flysystem\Filesystem;

/**
 * Document controller.
 *
 * @Route("lab/document")
 */
class DocumentController extends Controller
{
    /**
     * Lists all document entities.
     *
     * @Route("s/chantier/{slug}", name="document_index", defaults={"slug" = null})
     * @Method("GET")
     */
    public function indexAction(Request $request, $slug)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        if (is_null($slug)) {
            // $dql   = "SELECT d FROM AppBundle:Document d JOIN d.parent p JOIN d.chantier c"; 
            // $query = $em->createQuery($dql);

            $query = $em->createQueryBuilder()
            ->select('d')
            ->from('AppBundle:Document', 'd')
            ->Join('d.chantier', 'c')            
            ->leftJoin('d.parent', 'p');            
        } else {
            // $dql   = "SELECT d FROM AppBundle:Document d JOIN d.parent p JOIN d.chantier c WHERE c.slug = :slug";
            // $query = $em->createQuery($dql);

            $query = $em->createQueryBuilder()
            ->select('d')
            ->from('AppBundle:Document', 'd')
            ->Join('d.chantier', 'c')            
            ->leftJoin('d.parent', 'p')
            ->where('c.slug = :slug')
            ->setParameter('slug', $slug);
        }
    
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('document/index.html.twig', array('pagination' => $pagination, 'slug' => $slug));
    }

    /**
     * @Route("/detail/{id}", name="document_detail", defaults={"id" = null}, requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     */
    public function detailAction(Request $request, Document $document)
    {
        $deleteForm = $this->createDeleteForm($document);
        $deleteForm->handleRequest($request);

        $editForm = $this->createForm('AppBundle\Form\DocumentType', $document);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('document_detail', array('id' => $document->getId()));
        }

        return $this->render('document/detail.html.twig', array(
            'document' => $document,
            'delete_form' => $deleteForm->createView(),
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="document_show", defaults={"id" = null}, requirements={"id"="\d+"})
     * @Method("GET")
     */
    public function showAction(Document $document)
    {
        return $this->render('document/show.html.twig', array(
            'document' => $document,
        ));
    }

    /**
     * @Route("/{id}/plein-ecran", name="document_fullscreen", defaults={"id" = null}, requirements={"id"="\d+"})
     * @Method("GET")
     */
    public function fullscreenAction(Document $document)
    {
        return $this->render('document/fullscreen.html.twig', array(
            'document' => $document,
        ));
    }
    
    /**
     * @Route("/{id}/download", name="document_download")
     * @Method({"GET"})
     */
    public function downloadAction(Request $request, Document $document)
    {
        $filesystem = $this->get('btp40_filesystem');
        if ($filesystem->has($document->getFichier())) {
            $content = $filesystem->read($document->getFichier());

            $response = new Response();
    
            //set headers
            $response->headers->set('Content-Type', 'mime/type');
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$document->getOriginalFileName());
        
            $response->setContent($content);
            return $response;

        } else {
            throw $this->createNotFoundException("Le fichier correspondant au document n'existe plus sur le serveur");
        }
    }

    /**
     * Deletes a document entity.
     *
     * @Route("/delete/{id}", name="document_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Document $document)
    {
        $form = $this->createDeleteForm($document);
        $form->handleRequest($request);

        $chantier = $document->getChantier();

        if ($form->isSubmitted() && $form->isValid()) {
       
            // supprime le fichier
            $filesystem = $this->get('btp40_filesystem');
            if ($filesystem->has($document->getFichier())) {
                $filesystem->delete($document->getFichier());
            }

            $em = $this->getDoctrine()->getManager();
            $em->remove($document);
            $em->flush();
        }

        return $this->redirectToRoute('chantier_show', array('slug' => $chantier->getSlug()));
    }

    /**
     * Creates a form to delete a document entity.
     *
     * @param Document $document The document entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Document $document)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('document_delete', array('id' => $document->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
