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
use League\Flysystem\Filesystem;

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
     * @Rest\Put("/add-dossier")
     */
    public function putDossierAction(Request $request)
    {
        $dossier = new Dossier();
        $form = $this->createForm('AppBundle\Form\ApiDossierType', $dossier);
        $form->submit($request->request->all(),true);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($dossier);
            $em->flush();

            return $dossier;
        } else {
            $errors = (string) $form->getErrors(true, false);
            $errors =  preg_replace('/\r|\n/', '', $errors); // remove CR LF
            return new JsonResponse(['message' => $errors], Response::HTTP_CONFLICT);
        }
    }

     /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Delete("/delete-document/{id}", defaults={"id" = null})
     */
    public function deleteDocumentAction(Request $request, $id)
    {
       
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $this->getDoctrine()->getRepository(Document::class);
        $document = $repository->findOneById($id); 

        if (empty($document)) {
            return new JsonResponse(['message' => 'Aucun document trouvé'], Response::HTTP_NOT_FOUND);
        }

        // supprime le fichier
        $filesystem = $this->get('btp40_filesystem');
        if ($filesystem->has($document->getFichier())) {
            $filesystem->delete($document->getFichier());
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($document);
        $em->flush();

        return new JsonResponse(['message' => 'Document supprimé'], Response::HTTP_OK);
    }

     /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Delete("/delete-dossier/{id}", defaults={"id" = null})
     */
    public function deleteDossierAction(Request $request, $id)
    {
       
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $this->getDoctrine()->getRepository(Dossier::class);
        $dossier = $repository->findOneById($id); 

        if (empty($dossier)) {
            return new JsonResponse(['message' => 'Aucun dossier trouvé'], Response::HTTP_NOT_FOUND);
        } 

        $documents = $dossier->getDocuments();
        if (count($documents)>0) {
            return new JsonResponse(["message" => "Le dossier n'est pas vide"], Response::HTTP_CONFLICT);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($dossier);
        $em->flush();

        return new JsonResponse(['message' => 'Dossier supprimé'], Response::HTTP_OK);
    }

     /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Patch("/update-document/{id}", defaults={"id" = null})
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


        if ($form->isSubmitted() && $form->isValid()) {
            $em->merge($document);
            $em->flush();
            return $document;
        } else {
            $errors = (string) $form->getErrors(true, false);
            return new JsonResponse(['message' => $errors], Response::HTTP_CONFLICT  );
        }
    }    

     /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Patch("/update-dossier/{id}", defaults={"id" = null})
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


        if ($form->isSubmitted() && $form->isValid()) {
            $em->merge($dossier);
            $em->flush();
            return $dossier;
        } else {
            $errors = (string) $form->getErrors(true, false);
            return new JsonResponse(['message' => $errors], Response::HTTP_CONFLICT  );
        }
    }   
}
