<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
     /**
      * @Route("/", name="index")
      */
    public function coming(): Response
    {
      $contents = $this->renderView('coming.html.twig', [
      ]);

      return new Response($contents);
    }
    /**
     * @Route("/gallery", name="galeria")
     */
   public function gallery(): Response
   {
     $contents = $this->renderView('gallery/gallery.html.twig', [
     ]);

     return new Response($contents);
   }
}
