<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="spotify")
 */
class Spotify
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=2092)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $refreshToken;

    public function __construct()
    {
    }

    public function getId(){
      return $this->id;
    }

    public function getToken(){
      return $this->token;
    }

    public function setToken($token){
      $this->token = $token;
    }

    public function getRefreshToken(){
      return $this->refreshToken;
    }

    public function setRefreshToken($refreshToken){
      $this->refreshToken = $refreshToken;
    }

}
