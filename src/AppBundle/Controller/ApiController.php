<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chantier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

/**
 * Chantier controller.
 */
class ApiController extends Controller
{
    
     /**
     * Lists all chantier in a given area.
     * @Rest\View()
     * @Rest\Get("/api/localise-chantiers")
     */
    public function getNeighborhoodAction(Request $request)
    {

        $south = $request->query->get('south', '48.815458');
        $west = $request->query->get('west', '2.2279671');

        $north = $request->query->get('north', '48.911599');
        $east = $request->query->get('east', '2.429292');

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT c FROM AppBundle:Chantier c JOIN c.adresse a WHERE a.lat between " .$south . " and ". $north ." and a.lon between " . $west . " and " . $east;
        $query = $em->createQuery($dql);
        $chantiers= $query->getResult();

        if (empty($chantiers)) {
            return new JsonResponse(['message' => 'Aucun chantier trouvé'], Response::HTTP_NOT_FOUND);
        }

        return $chantiers;
    }

     /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Patch("/api/update-chantier/{slug}")
     */
    public function patchPlanningAction(Request $request, $slug)
    {
        
       
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $this->getDoctrine()->getRepository(Chantier::class);
        $chantier = $repository->findOneBySlug($slug); 

        if (empty($chantier)) {
            return new JsonResponse(['message' => 'Aucun chantier trouvé'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm('AppBundle\Form\ApiChantierType', $chantier);

        // Le paramètre false dit à Symfony de garder les valeurs dans notre 
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(),false);


        if ($form->isValid()) {
            $em->merge($chantier);
            $em->flush();
            return $chantier;
        } else {
            return $form;
        }
    }
   
}
