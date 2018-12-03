<?php
// src/AppBundle/Entity/Document.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity("nom")
 * @ORM\Table(name="btp_document")
 */
class Document
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=256)
     * @Assert\NotBlank()
     */
    protected $nom;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $commentaire;
    
    /**
     * @ORM\Column(type="string", length=256)
     * @Assert\File(
     *      maxSize = "5M",
     *      mimeTypes={ "image/jpeg", "image/png", "image/bmp", "image/gif", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/pdf", "application/x-pdf", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-7z-compressed", "application/zip", "application/x-zip-compressed", "multipart/x-zip",  "audio/x-wav", "audio/mpeg" },
     *      mimeTypesMessage = "Les types de fichiers autorisés sont : images (JPEG, PNG, BMP, GIF), sons (WAV, MP3), documents (PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX) et archives (ZIP, 7Z)",
     *      maxSizeMessage = "La taille du fichier est supérieure au maximun autorisé : {{ limit }} {{ suffix }}."
     * )
     */
    protected $fichier;
    
    /**
     * @Gedmo\Slug(fields={"nom"}, updatable=true, unique=true)
     * @ORM\Column(type="string", length=256, unique=true)
     */
    private $slug;
        
    /**
     * @Assert\Type(type="AppBundle\Entity\Chantier")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="Chantier", cascade={"persist"}, inversedBy="documents")
     * @ORM\JoinColumn(name="idChantier", referencedColumnName="id")
     */
    private $chantier;
    
    /**
     * @Assert\Type(type="AppBundle\Entity\Dossier")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="Dossier", cascade={"persist"}, inversedBy="documents")
     * @ORM\JoinColumn(name="idDossierParent", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    private $mimeType;

    /**
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    private $originalFileName;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    protected $fileSize;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $fileCreatedAt;   

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="uploaded_at", type="datetime", nullable=true)
     */
    private $fileUploadedAt;    

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="uploaded_by", referencedColumnName="id", nullable=true)
     */
    protected $fileUploadedBy;   

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
     * @return Document
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
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Document
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set fichier
     *
     * @param string $fichier
     *
     * @return Document
     */
    public function setFichier($fichier)
    {
        $this->fichier = $fichier;

        return $this;
    }

    /**
     * Get fichier
     *
     * @return string
     */
    public function getFichier()
    {
        return $this->fichier;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Document
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
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
     * Set chantier
     *
     * @param \AppBundle\Entity\Chantier $chantier
     *
     * @return Document
     */
    public function setChantier(\AppBundle\Entity\Chantier $chantier = null)
    {
        $this->chantier = $chantier;

        return $this;
    }

    /**
     * Get chantier
     *
     * @return \AppBundle\Entity\Chantier
     */
    public function getChantier()
    {
        return $this->chantier;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return document
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set originalFileName
     *
     * @param string $originalFileName
     *
     * @return document
     */
    public function setOriginalFileName($originalFileName)
    {
        $this->originalFileName = $originalFileName;

        return $this;
    }

    /**
     * Get originalFileName
     *
     * @return string
     */
    public function getOriginalFileName()
    {
        return $this->originalFileName;
    }

    /**
     * Set fileSize
     *
     * @param string $fileSize
     *
     * @return document
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * Get fileSize
     *
     * @return string
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Set fileCreatedAt
     *
     * @param \DateTime $fileCreatedAt
     *
     * @return document
     */
    public function setFileCreatedAt($fileCreatedAt)
    {
        $this->fileCreatedAt = $fileCreatedAt;

        return $this;
    }

    /**
     * Get fileCreatedAt
     *
     * @return \DateTime
     */
    public function getFileCreatedAt()
    {
        return $this->fileCreatedAt;
    }

    /**
     * Set fileUploadedAt
     *
     * @param \DateTime $fileUploadedAt
     *
     * @return document
     */
    public function setFileUploadedAt($fileUploadedAt)
    {
        $this->fileUploadedAt = $fileUploadedAt;

        return $this;
    }

    /**
     * Get fileUploadedAt
     *
     * @return \DateTime
     */
    public function getFileUploadedAt()
    {
        return $this->fileUploadedAt;
    }

    /**
     * Set fileUploadedBy
     *
     * @param \AppBundle\Entity\User $fileUploadedBy
     *
     * @return document
     */
    public function setFileUploadedBy(\AppBundle\Entity\User $fileUploadedBy = null)
    {
        $this->fileUploadedBy = $fileUploadedBy;

        return $this;
    }

    /**
     * Get fileUploadedBy
     *
     * @return \AppBundle\Entity\User
     */
    public function getFileUploadedBy()
    {
        return $this->fileUploadedBy;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Dossier $parent
     *
     * @return Document
     */
    public function setParent(\AppBundle\Entity\Dossier $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Dossier
     */
    public function getParent()
    {
        return $this->parent;
    }
}
