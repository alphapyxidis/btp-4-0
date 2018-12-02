<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
use AppBundle\Entity\Dossier;
use AppBundle\Entity\Chantier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

/**
 * Document controller.
 */
class ApiDocumentController extends Controller
{
    
     /**
     * @Rest\View()
     * @Rest\Get("/get-documents-chantier/{slug}")
     */
    public function getDocumentsChantierAction(Request $request, $slug)
    {
       
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $this->getDoctrine()->getRepository(Chantier::class);
        $chantier = $repository->findOneBySlug($slug); 

        if (empty($chantier)) {
            return new JsonResponse(['message' => 'Aucun document trouvé'], Response::HTTP_NOT_FOUND);
        }

        return $this->render('document/documents-chantier.json.twig', array(
            'chantier' => $chantier,
        ));

    }

     /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Patch("/update-document/{id}")
     */
    public function patchDocumentAction(Request $request, $id)
    {
       
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $this->getDoctrine()->getRepository(Document::class);
        $document = $repository->findOneById($id); 

        if (empty($document)) {
            return new JsonResponse(['message' => 'Aucun document trouvé'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm('AppBundle\Form\ApiDocumentType', $document);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre 
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(),false);


        if ($form->isValid()) {
            $em->merge($document);
            $em->flush();
            return $document;
        } else {
            return $form;
        }
    }    

     /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Patch("/update-dossier/{id}")
     */
    public function patchDossierAction(Request $request, $id)
    {
       
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $this->getDoctrine()->getRepository(Dossier::class);
        $dossier = $repository->findOneById($id); 

        if (empty($dossier)) {
            return new JsonResponse(['message' => 'Aucun dossier trouvé'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm('AppBundle\Form\ApiDossierType', $dossier);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre 
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(),false);


        if ($form->isValid()) {
            $em->merge($dossier);
            $em->flush();
            return $dossier;
        } else {
            return $form;
        }
    }   
}
