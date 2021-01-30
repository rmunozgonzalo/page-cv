<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Gallery;

class IndexController extends AbstractController
{
     /**
      * @Route("/index", name="index")
      */
    public function coming(): Response
    {
      $contents = $this->renderView('coming.html.twig', [
      ]);

      return new Response($contents);
    }
    /**
     * @Route("/", name="galeria")
     */
   public function gallery(): Response
   {
     $refreshTk = $this->getParameter('app.refreshToken');
     $gallery = $this->getDoctrine()
       ->getRepository(Gallery::class)
       ->findAll();
     $contents = $this->renderView('gallery/gallery.html.twig',
     [
       'refresh'=>$refreshTk,
       'gallery'=>$gallery
     ]);

     return new Response($contents);
   }

   /**
    * @Route("/admintest", name="admintest")
    * @IsGranted("ROLE_ADMIN")
    */
  public function adminAction(): Response
  {
    $contents = $this->renderView('coming.html.twig', [
    ]);

    return new Response($contents);
  }
}
