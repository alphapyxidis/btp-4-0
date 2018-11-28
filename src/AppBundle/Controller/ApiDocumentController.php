<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
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
    public function getDocumentAction(Request $request, $slug)
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
  
}
