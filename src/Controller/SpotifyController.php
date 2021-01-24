<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\Session as SessionSpotify;
use App\Controller\SpotifyWebAPI;
use App\Entity\Spotify;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\HttpFoundation\Session\Session;

class SpotifyController extends AbstractController
{
   use LockableTrait;
   public function getName(){
      return "test-function";
    }
    /**
     * @Route("/init", name="init")
     */
   public function initAction(Request $request)
   {

     $sessionHttp = new Session();
     $sessionHttp->start();

     $cliente =  $this->getParameter('app.client');
     $clienteSecret = $this->getParameter('app.clientSecret');
     $urlRedirect = $this->getParameter('app.urlRedirect');
     $code = $request->get('code');

     $session = new SessionSpotify(
              $cliente,
              $clienteSecret,
             $urlRedirect

       );
       //if(0){
       if($code == null ){
       $verifier = $session->generateCodeVerifier(); // Store this value somewhere, a session for example
       $challenge = $session->generateCodeChallenge($verifier);
       $state = $session->generateState();

       $sessionHttp->set('verifier', $verifier);

       $options = [
           'code_challenge' => $challenge,
           'scope' => [
               'playlist-read-private',
               'user-read-private',
           'user-read-recently-played',
           'user-read-currently-playing',
           'user-read-playback-state'

           ],
           'state' => $state,
       ];

       return $this->redirect($session->getAuthorizeUrl($options));
       }
       else{

           // Request a access token using the code from Spotify and the previously created code verifier
           $session->requestAccessToken($code,$sessionHttp->get('verifier'));
           //$session->requestAccessToken($code,$verifier);

           $accessToken = $session->getAccessToken();
           $refreshToken = $session->getRefreshToken();

         $api = new SpotifyWebAPI();

         // Fetch the saved access token from somewhere. A session for example.
         $api->setAccessToken($accessToken);
         $track = null;


           $track = $api->getMyRecentTracks(['limit'=>1]);


           $session->refreshAccessToken($refreshToken);

           $accessToken = $session->getAccessToken();
           $refreshToken = $session->getRefreshToken();

           // Set our new access token on the API wrapper and continue to use the API as usual
           $api->setAccessToken($accessToken);

           $track = $api->getMyRecentTracks(['limit'=>1]);

           $entityManager = $this->getDoctrine()->getManager();
           $spotifyEntity = $entityManager->getRepository(Spotify::class)->findOneBy(array(), array('id' => 'DESC'));
           if($spotifyEntity==null){
             $spotify = new Spotify();
             $spotify->setRefreshToken($refreshToken);
             $spotify->setToken($accessToken);
             $entityManager->persist($spotify);
           }
           else{
             $spotifyEntity->setRefreshToken($refreshToken);
             $spotifyEntity->setToken($accessToken);

           }
           $entityManager->flush();

         // It's now possible to request data about the currently authenticated user
         //echo json_encode($api->getMyRecentTracks(['limit'=>1]));
         $data = ['track'=>$track,'refreshToken'=>$refreshToken];
         // Getting Spotify catalog data is of course also possible
         return new JsonResponse($data);
       }


   }

   /**
    * @Route("/tokenforever", name="tokenforever")
    */
  public function tokenForeverAction(Request $request)
  {

    if (!$this->lock()) {
      $data = ['bussy'=>200];

      return new JsonResponse($data);
    }

    $entityManager = $this->getDoctrine()->getManager();
    $spotifyEntity = $entityManager->getRepository(Spotify::class)->findOneBy(array(), array('id' => 'DESC'));
    $refreshTk = $spotifyEntity->getRefreshToken();

    $cliente =  $this->getParameter('app.client');
    $clienteSecret = $this->getParameter('app.clientSecret');
    $urlRedirect = $this->getParameter('app.urlRedirect');

    $session = new SessionSpotify(
             $cliente,
             $clienteSecret,
            $urlRedirect

      );

    $session->refreshAccessToken($refreshTk);
    $accessToken=$session->getAccessToken();
    $refreshToken = $session->getRefreshToken();


    if($spotifyEntity==null){
      $spotify = new Spotify();
      $spotify->setRefreshToken($refreshToken);
      $spotify->setToken($accessToken);
      $entityManager->persist($spotify);
    }
    else{
      $spotifyEntity->setRefreshToken($refreshToken);
      $spotifyEntity->setToken($accessToken);

    }
    $entityManager->flush();

    $api = new SpotifyWebAPI();
    $api->setAccessToken($accessToken);


    $track = $api->getMyRecentTracks(['limit'=>1]);

    $data = ['track'=>$track,'refresh'=>$refreshToken];

    return new JsonResponse($data);

  }
}
