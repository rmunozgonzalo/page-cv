<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\Session as SessionSpotify;
use App\Controller\SpotifyWebAPI;

class SpotifyController extends AbstractController
{
     /**
      * @Route("/currenttrack", name="currentTrack")
      */
    public function currentTrackAction(Request $request)
    {
      session_start();

            // Fetch the saved access token from somewhere. A database for example.
        $cliente =  $this->getParameter('app.client');
        $clienteSecret = $this->getParameter('app.clientSecret');

        $session = new SessionSpotify(
                 $cliente,
                 $clienteSecret,
        		  'http://localhost/page-cv/public/index.php/currenttrack'

        );
        $code = 0;
        if(isset($_GET['code'])){
          $code = $_GET['code'];
        }
        //if(0){
        if(!isset($_GET['code']) && !isset($_SESSION["accessToken"])){
        $verifier = $session->generateCodeVerifier(); // Store this value somewhere, a session for example
        $challenge = $session->generateCodeChallenge($verifier);
        $state = $session->generateState();
        $_SESSION["verifier"] = $verifier;

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

          if(!isset($_SESSION["accessToken"])){
            // Request a access token using the code from Spotify and the previously created code verifier
          	$session->requestAccessToken($_GET['code'],$_SESSION["verifier"]);
            //$session->requestAccessToken($code,$verifier);

          	$accessToken = $session->getAccessToken();
          	$refreshToken = $session->getRefreshToken();

            $_SESSION["accessToken"] = $accessToken;
            $_SESSION["refreshToken"] = $refreshToken;

          }

          $api = new SpotifyWebAPI();

        	// Fetch the saved access token from somewhere. A session for example.
        	$api->setAccessToken($_SESSION["accessToken"]);
          $track = null;


            $track = $api->getMyRecentTracks(['limit'=>1]);

            $track2 = json_decode(json_encode($track), true);
            if(isset($track2['error'])){
              $cliente =  $this->getParameter('app.client');
              $clienteSecret = $this->getParameter('app.clientSecret');

              $session = new SessionSpotify(
                       $cliente,
                       $clienteSecret,
            		  'http://localhost/page-cv/public/index.php/currenttrack'

            );

            // Fetch the refresh token from somewhere. A database for example.
            $refreshToken = $_SESSION["refreshToken"];
            $session->refreshAccessToken($refreshToken);

            $accessToken = $session->getAccessToken();
            $refreshToken = $session->getRefreshToken();

            // Set our new access token on the API wrapper and continue to use the API as usual
            $api->setAccessToken($accessToken);

            $track = $api->getMyRecentTracks(['limit'=>1]);
          }


        	// It's now possible to request data about the currently authenticated user
        	//echo json_encode($api->getMyRecentTracks(['limit'=>1]));
          $data = ['track'=>$track];
        	// Getting Spotify catalog data is of course also possible
          return new JsonResponse($data);
        }
    }

    /**
     * @Route("/init", name="init")
     */
   public function initAction(Request $request)
   {
     session_start();

     $cliente =  $this->getParameter('app.client');
     $clienteSecret = $this->getParameter('app.clientSecret');

     $session = new SessionSpotify(
              $cliente,
              $clienteSecret,
             'http://localhost/page-cv/public/index.php/init'

       );
       $code = 0;
       if(isset($_GET['code'])){
         $code = $_GET['code'];
       }
       //if(0){
       if(!isset($_GET['code'])){
       $verifier = $session->generateCodeVerifier(); // Store this value somewhere, a session for example
       $challenge = $session->generateCodeChallenge($verifier);
       $state = $session->generateState();
       $_SESSION["verifier"] = $verifier;

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
           $session->requestAccessToken($_GET['code'],$_SESSION["verifier"]);
           //$session->requestAccessToken($code,$verifier);

           $accessToken = $session->getAccessToken();
           $refreshToken = $session->getRefreshToken();

           $_SESSION["accessToken"] = $accessToken;
           $_SESSION["refreshToken"] = $refreshToken;



         $api = new SpotifyWebAPI();

         // Fetch the saved access token from somewhere. A session for example.
         $api->setAccessToken($_SESSION["accessToken"]);
         $track = null;


           $track = $api->getMyRecentTracks(['limit'=>1]);


           $session->refreshAccessToken($refreshToken);

           $accessToken = $session->getAccessToken();
           $refreshToken = $session->getRefreshToken();

           // Set our new access token on the API wrapper and continue to use the API as usual
           $api->setAccessToken($accessToken);

           $track = $api->getMyRecentTracks(['limit'=>1]);



         // It's now possible to request data about the currently authenticated user
         //echo json_encode($api->getMyRecentTracks(['limit'=>1]));
         $data = ['track'=>$track];
         // Getting Spotify catalog data is of course also possible
         return new JsonResponse($data);
       }


   }
}
