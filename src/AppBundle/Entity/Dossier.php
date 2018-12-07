<?php
// src/AppBundle/Entity/Dossier.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity({"chantier", "nom"})
 * @ORM\Table(name="btp_dossier")
 */
class Dossier
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
     * @Gedmo\Slug(fields={"nom"}, updatable=true, unique=false)
     * @ORM\Column(type="string", length=256, unique=false)
     */
    private $slug;

    /**
     * @Assert\Type(type="AppBundle\Entity\Chantier")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="Chantier", cascade={"persist"}, inversedBy="dossiers")
     * @ORM\JoinColumn(name="idChantier", referencedColumnName="id")
     */
    private $chantier;
    
    /**
     * @Assert\Type(type="AppBundle\Entity\Dossier")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="Dossier", cascade={"persist"}, inversedBy="sousDossiers")
     * @ORM\JoinColumn(name="idDossierParent", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="parent")
     */
    private $documents;   

    /**
     * @ORM\OneToMany(targetEntity="Dossier", mappedBy="parent")
     */
    private $sousDossiers;       
    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $folderCreatedAt;    

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $folderDeletedAt;   
    
    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=true)
     */
    protected $folderCreatedBy;   

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
     * Set folderCreatedAt
     *
     * @param \DateTime $folderCreatedAt
     *
     * @return Dossier
     */
    public function setFolderCreatedAt($folderCreatedAt)
    {
        $this->folderCreatedAt = $folderCreatedAt;

        return $this;
    }

    /**
     * Get folderCreatedAt
     *
     * @return \DateTime
     */
    public function getFolderCreatedAt()
    {
        return $this->folderCreatedAt;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Dossier $parent
     *
     * @return Dossier
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

    /**
     * Add document
     *
     * @param \AppBundle\Entity\Document $document
     *
     * @return Dossier
     */
    public function addDocument(\AppBundle\Entity\Document $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document
     *
     * @param \AppBundle\Entity\Document $document
     */
    public function removeDocument(\AppBundle\Entity\Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Add sousDossier
     *
     * @param \AppBundle\Entity\Dossier $sousDossier
     *
     * @return Dossier
     */
    public function addSousDossier(\AppBundle\Entity\Dossier $sousDossier)
    {
        $this->sousDossiers[] = $sousDossier;

        return $this;
    }

    /**
     * Remove sousDossier
     *
     * @param \AppBundle\Entity\Dossier $sousDossier
     */
    public function removeSousDossier(\AppBundle\Entity\Dossier $sousDossier)
    {
        $this->sousDossiers->removeElement($sousDossier);
    }

    /**
     * Get sousDossiers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSousDossiers()
    {
        return $this->sousDossiers;
    }

    /**
     * Set folderCreatedBy
     *
     * @param \AppBundle\Entity\User $folderCreatedBy
     *
     * @return Dossier
     */
    public function setFolderCreatedBy(\AppBundle\Entity\User $folderCreatedBy = null)
    {
        $this->folderCreatedBy = $folderCreatedBy;

        return $this;
    }

    /**
     * Get folderCreatedBy
     *
     * @return \AppBundle\Entity\User
     */
    public function getFolderCreatedBy()
    {
        return $this->folderCreatedBy;
    }

    /**
     * Set folderDeletedAt
     *
     * @param \DateTime $folderDeletedAt
     *
     * @return Dossier
     */
    public function setFolderDeletedAt($folderDeletedAt)
    {
        $this->folderDeletedAt = $folderDeletedAt;

        return $this;
    }

    /**
     * Get folderDeletedAt
     *
     * @return \DateTime
     */
    public function getFolderDeletedAt()
    {
        return $this->folderDeletedAt;
    }
}
