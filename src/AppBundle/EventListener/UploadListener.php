<?php
namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManager;

use Oneup\UploaderBundle\Event\PostPersistEvent;
use Oneup\UploaderBundle\Event\PreUploadEvent;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Document;
use AppBundle\Entity\Chantier;
use AppBundle\Entity\Dossier;

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
        $parent = $request->get('parent');
        // convert UNIX epoch time to DateTime
        $clientOriginalDate = \DateTime::createFromFormat('U',substr($request->get('fileLastModified'), 0, 10));
        $mimetype = $request->get('mimeType');

        $originalName = $request->files->get('file')->getClientOriginalName();
        $pathParts = pathinfo($originalName);
        $documentName = $pathParts['filename']; // nom du fichier original sans l'extension

        $repository = $em->getRepository(Chantier::class);
        $chantier = $repository->findOneBySlug($slug); 

        if (empty($chantier)) {

             throw new UploadException('Aucun chantier trouvé : ['.$slug.']');
        }

        $repository = $em->getRepository(Dossier::class);
        $parent = $repository->findOneById($parent); 

        $filesize = $file->getSize();
        
        $object = new Document();
        $object->setNom($documentName);
        $object->setFichier($file->getPathName());
        $object->setFileCreatedAt($clientOriginalDate);
        $object->setOriginalFileName($originalName);
        $object->setFileSize($filesize);
        $object->setMimeType($mimetype);
        $object->setChantier($chantier);
        if (!empty($parent)) {
            $object->setParent($parent);
        }

        $em->persist($object);
        $em->flush();

        //if everything went fine
        $response = $event->getResponse();
        $response['success'] = true;
        return $response;
    }
}