<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class ContactController extends AbstractController
{
     /**
      * @Route("/contactpost", name="contactPost")
      */
    public function contactAction(Request $request, MailerInterface $mailer)
    {
      $name = $request->get('name');
      $email = $request->get('email');
      $message = $request->get('message');

      $from = $this->getParameter('app.email');
      $owner = $this->getParameter('app.email.owner');
      $emailContact = (new Email())
                     ->from($from)
                     ->to($email)
                     ->subject('Contacto')
                     ->html($this->renderView('Emails/emailContacto.html.twig',array('name' => $name)),'text/html');

     $emailOwner = (new Email())
                    ->from($from)
                    ->to($owner)
                    ->subject('Contacto')
                    ->html($this->renderView('Emails/emailPropietario.html.twig',array('name' => $name,'message'=>$message,'email'=>$email)),'text/html');

      $result = $mailer->send($emailContact);
      $result2 = $mailer->send($emailOwner);

      $contents = $this->renderView('gallery/gallery.html.twig', [
      ]);

      return new Response($contents);
    }
}
