<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="btp_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(name="auth0_id", type="string", length=255, nullable=true) */
    protected $auth0_id;

    /** @ORM\Column(name="auth0_access_token", type="string", length=255, nullable=true) */
    protected $auth0_access_token;

    /** @ORM\Column(name="picture", type="string", length=512, nullable=true) */
    protected $picture;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set auth0Id
     *
     * @param string $auth0Id
     *
     * @return User
     */
    public function setAuth0Id($auth0Id)
    {
        $this->auth0_id = $auth0Id;

        return $this;
    }

    /**
     * Get auth0Id
     *
     * @return string
     */
    public function getAuth0Id()
    {
        return $this->auth0_id;
    }

    /**
     * Set auth0AccessToken
     *
     * @param string $auth0AccessToken
     *
     * @return User
     */
    public function setAuth0AccessToken($auth0AccessToken)
    {
        $this->auth0_access_token = $auth0AccessToken;

        return $this;
    }

    /**
     * Get auth0AccessToken
     *
     * @return string
     */
    public function getAuth0AccessToken()
    {
        return $this->auth0_access_token;
    }

    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return User
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }
}
