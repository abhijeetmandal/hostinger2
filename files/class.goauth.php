<?php
    /**
     * Member based functions
     *
     * @author CrushIt!! <admin@crushit.fit>
     * @todo implement google authentication
     * @todo Check if all functions need to be public
     */

    class goauth {
        private $app;
        private $client;
        
        public function __construct($app) {
            //vendor files from composer which is in gauth folder
            include_once __DIR__ . '/../gdrive/vendor/autoload.php';
            
            $this->app = $app;
            $app->goauth = $this;
            
            
        }
        
        //Step 1: Configure the client object
        function configureClientObject($redirect_uri){
            /*************************************************
            * Ensure you've downloaded your oauth credentials
            ************************************************/
           if (!$oauth_credentials = getOAuthCredentialsFile()) {
             $this->log->add("users.log", missingOAuth2CredentialsWarning());
             return false;
           }
           
           /************************************************
            * The redirect URI is to the current page, e.g:
            * http://localhost:8080/simple-file-upload.php
            ************************************************/
            //$redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
           
            $this->client = new Google_Client();
            $this->client->setAuthConfig($oauth_credentials);
            $this->client->setRedirectUri($redirect_uri);
            $this->client->addScope("https://www.googleapis.com/auth/drive");
            
            return $this->client;
        }
        
        //Step 2: Redirect to Google's OAuth 2.0 server
        function redirectToGoogleOAuthserver(){
            $auth_url = $this->client->createAuthUrl();
            header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        }
        
        //Step 3: Google prompts user for consent
        //need not do anything just wait
        
        //Step 4: Handle the OAuth 2.0 server response
        
        function getOAuthCredentialsFile()
        {
          // oauth2 creds
          $oauth_creds = __DIR__ . '/../gdrive/oauth-credentials.json';
          //echo "__DIR__: ".realpath(__DIR__.'/../../../files/gdrive/oauth-credentials.json')."</br>";
          //echo "realpath: ".realpath($oauth_creds)."</br>";

          if (file_exists($oauth_creds)) {
            return $oauth_creds;
          }

          return false;
        }
        
        function missingOAuth2CredentialsWarning()
        {
          $ret = "Warning: You need to set the location of your OAuth2 Client Credentials from the http://developers.google.com/console Google API console Once downloaded, move them into the root directory of this repository and rename them 'oauth-credentials.json";

          return $ret;
        }
        
        /************************************************
        * If we have a code back from the OAuth 2.0 flow,
        * we need to exchange that with the
        * Google_Client::fetchAccessTokenWithAuthCode()
        * function. We store the resultant access token
        * bundle in the session, and redirect to ourself.
        ************************************************/
       public function storeAccessToken($code,$redirect_uri) {
         $token = $client->fetchAccessTokenWithAuthCode($code);
         $client->setAccessToken($token);

         // store in the session also
         $_SESSION['upload_token'] = $token;

         // redirect back to the example
         header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
         return true;
       }
       
       // set the access token as part of the client
       public function setAccessToken($code,$redirect_uri) {
           if (!empty($_SESSION['upload_token'])) {
            $client->setAccessToken($_SESSION['upload_token']);
            if ($client->isAccessTokenExpired()) {
              unset($_SESSION['upload_token']);
            }
          } else {
            $authUrl = $client->createAuthUrl();
          }       
       }
       
    }
    
?>
