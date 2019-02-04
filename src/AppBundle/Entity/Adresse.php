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
     * @ORM\Column(type="string", length=8)
     */
    protected $codePostal;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $ville;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    protected $codeInsee;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $lat;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $lon;
    
    public function __construct()
    {
        //parent::__construct();
        // your own logic
    }

    //https://services.gisgraphy.com/geocoding/geocode?country=FR&address=

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
     * Set rue
     *
     * @param string $rue
     *
     * @return Adresse
     */
    public function setRue($rue)
    {
        $this->rue = $rue;

        return $this;
    }

    /**
     * Get rue
     *
     * @return string
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return Adresse
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Adresse
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set lat
     *
     * @param string $lat
     *
     * @return Adresse
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lon
     *
     * @param string $lon
     *
     * @return Adresse
     */
    public function setLon($lon)
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * Get lon
     *
     * @return string
     */
    public function getLon()
    {
        return $this->lon;
    }

    public function __toString()
    {
        return $this->getRue().' '.$this->getCodePostal().' '.$this->getVille();
    }    

    /**
     * Set codeInsee
     *
     * @param string $codeInsee
     *
     * @return Adresse
     */
    public function setCodeInsee($codeInsee)
    {
        $this->codeInsee = $codeInsee;

        return $this;
    }

    /**
     * Get codeInsee
     *
     * @return string
     */
    public function getCodeInsee()
    {
        return $this->codeInsee;
    }

    /* retrouve le code INSEE à partir du code postal et de la ville */
    public function updateCodeInsee()
    {

        $service_url = 'https://geo.api.gouv.fr/communes?nom='.urlencode($this->ville).'&codePostal='.$this->codePostal.'&fields=code&format=json';

        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }
        curl_close($curl);
        $decoded = json_decode($curl_response);
        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            die('error occured: ' . $decoded->response->errormessage);
        }

        $curl_response = json_decode($curl_response, true);

        $this->codeInsee = $curl_response[0]['code'];
 
        return $this;
    }    
}
