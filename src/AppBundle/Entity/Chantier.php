<?php
// src/AppBundle/Entity/Chantier.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
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
     * @ORM\nomChantier
     * @ORM\Column(type="text")
     */
    protected $nom;

    /**
     * @OneToOne(targetEntity="Adresse")
     * @JoinColumn(name="idAdresse", referencedColumnName="id")
     */
    private $adresse;
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

}
