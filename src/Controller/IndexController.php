<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Gallery;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;


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
    * @Route("/galeria", name="galeria")
    */
   public function gallery(): Response
   {
     $refreshTk = $this->getParameter('app.refreshToken');
     $gallery = $this->getDoctrine()
       ->getRepository(Gallery::class)
       ->findBy(['estado'=>1]);

     $contents = $this->renderView('gallery/gallery.html.twig',
     [
       'refresh'=>$refreshTk,
       'gallery'=>$gallery
     ]);

     return new Response($contents);
   }

   /**
    * @Route("/", name="galeriavue")
    */
  public function galleryVue(): Response
  {
    $refreshTk = $this->getParameter('app.refreshToken');

    $contents = $this->renderView('gallery/galleryVue.html.twig',
    [
      'refresh'=>$refreshTk,
    ]);

    return new Response($contents);
  }

     /**
    * @Route("/galeriavue2", name="galeriavue2")
    */
    public function galleryVue2(): Response
    {
      $refreshTk = $this->getParameter('app.refreshToken');
      $gallery = $this->getDoctrine()
      ->getRepository(Gallery::class)
      ->findBy(['estado'=>1]);

      $contents = $this->renderView('gallery/galleryv2.html.twig',
      [
        'refresh'=>$refreshTk,
        'gallery'=>$gallery
      ]);
  
      return new Response($contents);
    }
   /**
    * @Route("/galeria/json", name="galeriajson")
    */
  public function galleryJsonAction()
  {
    $base = $this->getParameter('app.base.images');
    $encoders = [new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];

    $serializer = new Serializer($normalizers, $encoders);

    $gallery = $this->getDoctrine()
      ->getRepository(Gallery::class)
      ->findBy(['estado'=>1]);

    foreach ($gallery as $img => $value) {
      $gallery[$img]->setImageMini($base.''.$gallery[$img]->getImageMini());
      $gallery[$img]->setImage($base.''.$gallery[$img]->getImage());
    }

    $galleryJson = $serializer->serialize($gallery,'json');

    return new Response($galleryJson);
  }

  public function adminAction(): Response
  {
    $contents = $this->renderView('coming.html.twig', [
    ]);

    return new Response($contents);
  }
}
