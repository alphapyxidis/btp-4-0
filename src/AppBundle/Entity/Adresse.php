<?php
// src/AppBundle/Entity/Adresse.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="btp_adresse")
 */
class Adresse
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     */
    protected $rue;

    /**
     * @ORM\Column(type="text")
     */
    protected $codePostal;

    /**
     * @ORM\Column(type="text")
     */
    protected $ville;

    /**
     * @ORM\Column(type="decimal")
     */
    protected $lat;
    
    /**
     * @ORM\Column(type="decimal")
     */
    protected $lon;
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    //https://services.gisgraphy.com/geocoding/geocode?country=FR&address=
}
