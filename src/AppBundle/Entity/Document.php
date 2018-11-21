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
     * @Assert\NotBlank()
     * @Assert\File(
     *      maxSize = "2M",
     *      mimeTypes={ "image/jpeg", "image/png", "image/bmp", "image/gif", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/pdf", "application/x-pdf", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/zip", "application/x-7z-compressed", "audio/x-wav", "audio/mpeg" },
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
     * @ORM\OneToOne(targetEntity="Chantier",cascade={"persist"})
     * @ORM\JoinColumn(name="idChantier", referencedColumnName="id")
     */
    private $chantier;

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
}