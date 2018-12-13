<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dossier;
use AppBundle\Entity\Chantier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

/**
 * dossier controller.
 *
 * @Route("demo/dossier")
 */
class DossierController extends Controller
{
    /**
     * Finds and displays a dossier entity.
     *
     * @Route("/{id}", name="dossier_show", defaults={"id" = null}, requirements={"id"="\d+"})
     * @Method("GET")
     */
    public function showAction(dossier $dossier)
    {
        return $this->render('dossier/show.html.twig', array(
            'dossier' => $dossier,
        ));
    }
   
    /**
     * crée un dossier "virtuel" correspondant à la racine d'un chantier.
     *
     * @Route("/{slug}", name="dossier_chantier", defaults={"slug" = null})
     * @Method("GET")
     */
    public function rootAction(Request $request, $slug)
    {

        $dossier = new Dossier();

        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $this->getDoctrine()->getRepository(Chantier::class);
        $chantier = $repository->findOneBySlug($slug); 

        if (empty($chantier)) {
            throw $this->createNotFoundException('Chantier non trouvé');
        }


        $dossier->setChantier($chantier);
        $dossier->setNom($chantier->getNom());

        foreach ($chantier->getDossiers() as $sousDossier) {
            if (is_null($sousDossier->getParent())) {
                $dossier->addSousDossier($sousDossier);
            }
        }
        foreach ($chantier->getDocuments() as $document) {
            if (is_null($document->getParent())) {
                $dossier->addDocument($document);
            }
        }

        return $this->render('dossier/show.html.twig', array(
            'dossier' => $dossier,
        ));
    }

}
