<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

 /**
 * @ORM\Entity
 * @ORM\Table(name="btp_user")
 * @UniqueEntity(fields="usernameCanonical", errorPath="username", message="fos_user.username.already_used")
 * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="emailCanonical",
 *          column=@ORM\Column(
 *              name     = "email_canonical",
 *              type     = "string",
 *              length   = 180,
 *              unique  = false
 *          )
 *      )
 * })
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

    /** @ORM\Column(name="nickname", type="string", length=512, nullable=true) */
    protected $nickname;

    /** @ORM\Column(name="realname", type="string", length=512, nullable=true) */
    protected $realname;    

    /** @ORM\Column(name="picture", type="string", length=512, nullable=true) */
    protected $picture;

    /** @ORM\Column(name="identity_provider", type="string", length=32, nullable=false) */
    protected $identity_provider="BTP 4.0";

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

    /**
     * Set nickname
     *
     * @param string $nickname
     *
     * @return User
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set realname
     *
     * @param string $realname
     *
     * @return User
     */
    public function setRealname($realname)
    {
        $this->realname = $realname;

        return $this;
    }

    /**
     * Get realname
     *
     * @return string
     */
    public function getRealname()
    {
        return $this->realname;
    }

    /**
     * Set identityProvider
     *
     * @param string $identityProvider
     *
     * @return User
     */
    public function setIdentityProvider($identityProvider)
    {
        $this->identity_provider = $identityProvider;

        return $this;
    }

    /**
     * Get identityProvider
     *
     * @return string
     */
    public function getIdentityProvider()
    {
        return $this->identity_provider;
    }
}
