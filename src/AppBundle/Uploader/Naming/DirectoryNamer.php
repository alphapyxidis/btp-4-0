<?php

namespace AppBundle\Uploader\Naming;


use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class DirectoryNamer implements NamerInterface
{
    private $requestStack;
    
    public function __construct(RequestStack $requestStack){
        $this->requestStack = $requestStack;
    }
    
    /**
     * Creates a user directory name for the file being uploaded.
     *
     * @param FileInterface $file
     * @return string The directory name.
     */
    public function name(FileInterface $file)
    {
        $slug = $this->requestStack->getCurrentRequest()->get('chantier');
        
        return sprintf('%s/%s.%s',
            $slug,
            uniqid(),
            $file->getExtension()
        );
    }
}