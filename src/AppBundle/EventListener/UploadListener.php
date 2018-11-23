<?php
namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManager;
//use Doctrine\Common\Persistence\ObjectManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Document;
use AppBundle\Entity\Chantier;

class UploadListener
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct($entityManager) 
    {
        $this->em = $entityManager;
    }
    
    public function onUpload(PostPersistEvent $event)
    {
        $file = $event->getFile();

        $em = $this->em;

        $request = $event->getRequest();
        $slug = $request->get('chantier');

        $originalName = $request->files->get('file')->getClientOriginalName();
        $pathParts = pathinfo($originalName);
        $originalName = $pathParts['filename'];

        $repository = $em->getRepository(Chantier::class);
        $chantier = $repository->findOneBySlug($slug); 

        if (empty($chantier)) {

             throw new UploadException('Aucun chantier trouvé : ['.$slug.']');
        }

        $object = new Document();
        $object->setNom($originalName);
        $object->setFichier($file->getPathName());
        $object->setChantier($chantier);

        $em->persist($object);
        $em->flush();

        //if everything went fine
        $response = $event->getResponse();
        $response['success'] = true;
        return $response;
    }
}