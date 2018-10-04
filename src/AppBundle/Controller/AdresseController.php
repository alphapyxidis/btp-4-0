<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Adresse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Adresse controller.
 *
 * @Route("demo/adresse")
 */
class AdresseController extends Controller
{

    /**
     * pour autocompletion du formulaire : construction de la liste de valeurs pour recherche Select2 
     *
     * @Route("/liste-villes", name="search_ville")
     * @Method("GET")
     */
    public function searchVille(Request $request)
    {

        // get the value typed to search for postal codes
        $q = $request->query->get('q'); // use "term" instead of "q" for jquery-ui
        $q = str_replace("+"," ",$q); 

        $api_user = $this->container->getParameter('geonames_user');

        $service_url = 'http://api.geonames.org/postalCodeSearchJSON?placename_startsWith='.$q.'&maxRows=10&country=FR&style=short&username='.$api_user;
        
        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }
        curl_close($curl);
        $decoded = json_decode($curl_response);
        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            die('error occured: ' . $decoded->response->errormessage);
        }

        $curl_response = json_decode($curl_response);

        if (isset($curl_response->postalCodes)){
            $results = $curl_response->postalCodes;
        } else {
            $results = $curl_response;
        }

        return $this->render('adresse/ville-autocompletion.json.twig', array('results' => $results,));
    }

    /**
     * pour autocompletion du formulaire : sélection de la valeur à partir de l'id pour remplir le champ de saisie Select2  
     *
     * @Route("/ville/{id}", name="get_ville")
     * @Method("GET")
     */
    public function getVille($id = null)
    {
        // le webservice qui a été appelé renvoie comme id la valeur de la ville
        return $this->json(strtoupper($id));
    }

    /**
     * Lists all adresse entities.
     *
     * @Route("s/", name="adresse_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adresses = $em->getRepository('AppBundle:Adresse')->findAll();

        return $this->render('adresse/index.html.twig', array(
            'adresses' => $adresses,
        ));
    }

    /**
     * Creates a new adresse entity.
     *
     * @Route("/ajouter", name="adresse_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $adresse = new Adresse();
        $form = $this->createForm('AppBundle\Form\AdresseType', $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adresse);
            $em->flush();

            return $this->redirectToRoute('adresse_show', array('id' => $adresse->getId()));
        }

        return $this->render('adresse/new.html.twig', array(
            'adresse' => $adresse,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a adresse entity.
     *
     * @Route("/{id}", name="adresse_show")
     * @Method("GET")
     */
    public function showAction(Adresse $adresse)
    {
        $deleteForm = $this->createDeleteForm($adresse);

        return $this->render('adresse/show.html.twig', array(
            'adresse' => $adresse,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing adresse entity.
     *
     * @Route("/{id}/modifier", name="adresse_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Adresse $adresse)
    {
        $deleteForm = $this->createDeleteForm($adresse);
        $editForm = $this->createForm('AppBundle\Form\AdresseType', $adresse);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('adresse_edit', array('id' => $adresse->getId()));
        }

        return $this->render('adresse/edit.html.twig', array(
            'adresse' => $adresse,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a adresse entity.
     *
     * @Route("/{id}/supprimer", name="adresse_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Adresse $adresse)
    {
        $form = $this->createDeleteForm($adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adresse);
            $em->flush();
        }

        return $this->redirectToRoute('adresse_index');
    }

    /**
     * Creates a form to delete a adresse entity.
     *
     * @param Adresse $adresse The adresse entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Adresse $adresse)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('adresse_delete', array('id' => $adresse->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
