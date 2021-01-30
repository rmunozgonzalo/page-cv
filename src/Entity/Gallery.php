<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gallery")
 */
class Gallery
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    public function __construct()
    {
    }

    public function getId(){
      return $this->id;
    }

    public function getTitulo(){
      return $this->titulo;
    }

    public function setTitulo($titulo){
      $this->titulo = $titulo;
    }

    public function getDescripcion(){
      return $this->descripcion;
    }

    public function setDescripcion($descripcion){
      $this->descripcion = $descripcion;
    }

}
