<?php
// src/AppBundle/Entity/Chantier.php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="btp_chantier")
 * @Vich\Uploadable
 */
class Chantier
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank()
     */
    protected $nom;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $description;
    
    /**
     * @Gedmo\Slug(fields={"nom"}, updatable=true, unique=true)
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @Assert\Type(type="AppBundle\Entity\Adresse")
     * @Assert\Valid()
     * @ORM\OneToOne(targetEntity="Adresse",cascade={"persist"})
     * @ORM\JoinColumn(name="idAdresse", referencedColumnName="id")
     */
    private $adresse;

    /**
     * 
     * @Vich\UploadableField(mapping="chantier_file", fileNameProperty="fileName", size="fileSize")
     * 
     */
    private $AttachedFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $fileName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var integer
     */
    private $fileSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $fileUpdatedAt;

    public function __construct()
    {
        //parent::__construct();
        // your own logic
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Chantier
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Chantier
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Chantier
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set adresse
     *
     * @param \AppBundle\Entity\Adresse $adresse
     *
     * @return Chantier
     */
    public function setAdresse(\AppBundle\Entity\Adresse $adresse = null)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return \AppBundle\Entity\Adresse
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file

     */
    public function setAttachedFile($file = null)
    {
        $this->AttachedFile = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->fileUpdatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * Get AttachedFile
     *
     * @return File
     */
    public function getAttachedFile()
    {
        return $this->AttachedFile;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return Chantier
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set fileSize
     *
     * @param integer $fileSize
     *
     * @return Chantier
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * Get fileSize
     *
     * @return integer
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Set fileUpdatedAt
     *
     * @param \DateTime $fileUpdatedAt
     *
     * @return Chantier
     */
    public function setFileUpdatedAt($fileUpdatedAt)
    {
        $this->fileUpdatedAt = $fileUpdatedAt;

        return $this;
    }

    /**
     * Get fileUpdatedAt
     *
     * @return \DateTime
     */
    public function getFileUpdatedAt()
    {
        return $this->fileUpdatedAt;
    }
}
