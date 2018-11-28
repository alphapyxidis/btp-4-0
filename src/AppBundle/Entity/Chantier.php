<?php
// src/AppBundle/Entity/Chantier.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity("nom")
 * @ORM\Table(name="btp_chantier")
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
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $gantt='{"tasks":[],"deletedTaskIds":[],"resources":[],"roles":[],"canWrite":true,"canAdd":true,"canWriteOnParent":true,"zoom":"1M"}';

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $pic='{"type":"FeatureCollection","features":[]}';

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Url()
     */
    protected $webcam;
    
    /**
     * @Gedmo\Slug(fields={"nom"}, updatable=true, unique=true)
     * @ORM\Column(type="string", length=128, unique=true)
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
     * @ORM\ManyToOne(targetEntity="User",cascade={"persist"})
     * @ORM\JoinColumn(name="idUser", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Dossier", mappedBy="chantier")
     */
    private $dossiers;   

    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="chantier")
     */
    private $documents;    

    public function __construct()
    {
        //parent::__construct();
        // your own logic
    }

    public function __toString() {
        return $this->slug;
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
     * Set gantt
     *
     * @param string $gantt
     *
     * @return Chantier
     */
    public function setGantt($gantt)
    {
        $this->gantt = $gantt;

        return $this;
    }

    /**
     * Get gantt
     *
     * @return string
     */
    public function getGantt()
    {
        return $this->gantt;
    }

    /**
     * Set pic
     *
     * @param string $pic
     *
     * @return Chantier
     */
    public function setPic($pic)
    {
        $this->pic = $pic;

        return $this;
    }

    /**
     * Get pic
     *
     * @return string
     */
    public function getPic()
    {
        return $this->pic;
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Chantier
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set webcam
     *
     * @param string $webcam
     *
     * @return Chantier
     */
    public function setWebcam($webcam)
    {
        $this->webcam = $webcam;

        return $this;
    }

    /**
     * Get webcam
     *
     * @return string
     */
    public function getWebcam()
    {
        return $this->webcam;
    }

    /**
     * Add document
     *
     * @param \AppBundle\Entity\Document $document
     *
     * @return Chantier
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
     * Add dossier
     *
     * @param \AppBundle\Entity\Dossier $dossier
     *
     * @return Chantier
     */
    public function addDossier(\AppBundle\Entity\Dossier $dossier)
    {
        $this->dossiers[] = $dossier;

        return $this;
    }

    /**
     * Remove dossier
     *
     * @param \AppBundle\Entity\Dossier $dossier
     */
    public function removeDossier(\AppBundle\Entity\Dossier $dossier)
    {
        $this->dossiers->removeElement($dossier);
    }

    /**
     * Get dossiers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDossiers()
    {
        return $this->dossiers;
    }
}
