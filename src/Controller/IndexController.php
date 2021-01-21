<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
     /**
      * @Route("/")
      */
    public function coming(): Response
    {
      $contents = $this->renderView('coming.html.twig', [
      ]);

      return new Response($contents);
    }
    /**
     * @Route("/gallery")
     */
   public function gallery(): Response
   {
     $contents = $this->renderView('gallery.html.twig', [
     ]);

     return new Response($contents);
   }
}
