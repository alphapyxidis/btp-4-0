<?php
namespace AppBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class UploadListener
{
    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }
    
    public function onUpload(PostPersistEvent $event)
    {
        //...
        throw new UploadException('Nope, I don\'t do files.');

        //if everything went fine
        $response = $event->getResponse();
        $response['success'] = false;
        return $response;
    }
}