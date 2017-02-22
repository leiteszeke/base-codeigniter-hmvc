<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( session_status() == PHP_SESSION_NONE ) {
    session_start();
}
 
require_once( APPPATH . 'libraries/Facebook/Facebook/GraphObject.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/GraphSessionInfo.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookSession.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/HttpClients/FacebookCurl.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/HttpClients/FacebookHttpable.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/HttpClients/FacebookCurlHttpClient.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookResponse.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookSDKException.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookRequestException.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookPermissionException.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookAuthorizationException.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookServerException.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookRequest.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/FacebookRedirectLoginHelper.php' );
require_once( APPPATH . 'libraries/Facebook/Facebook/Entities/AccessToken.php' );

use Facebook\GraphSessionInfo;
use Facebook\FacebookSession;
use Facebook\FacebookCurl;
use Facebook\FacebookHttpable;
use Facebook\FacebookCurlHttpClient;
use Facebook\FacebookResponse;
use Facebook\FacebookAuthorizationException;
use Facebook\FacebookServerException;
use Facebook\FacebookPermissionException;
use Facebook\FacebookRequestException;
use Facebook\FacebookRequest;
use Facebook\FacebookSDKException;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\GraphObject;
use Facebook\AccessToken;
 
class Facebook {
    var $ci;
    var $helper;
    var $session;
 
    public function __construct() {
        $this->ci =& get_instance();
 
        FacebookSession::setDefaultApplication( $this->ci->config->item('fb_appid'), $this->ci->config->item('fb_secret') );
        $this->helper = new FacebookRedirectLoginHelper( $this->ci->config->item('fb_fanpage_url') );
 
        if ( $this->ci->session->userdata('fb_token') ) {
            $this->session = new FacebookSession( $this->ci->session->userdata('fb_token') );
 
            // Validate the access_token to make sure it's still valid
            try {
                if ( ! $this->session->validate() ) {
                    $this->session = false;
                }
            } catch ( Exception $e ) {
                echo "(1) Exception occured, code: " . $e->getCode();
                echo " with message: " . $e->getMessage() . "<br />";
                // Catch any exceptions
                $this->session = false;
            }
        } else {            
            try {
                $this->session = $this->helper->getSessionFromRedirect();
            } catch(FacebookRequestException $ex) {
                echo "(2) Exception occured, code: " . $ex->getCode();
                echo " with message: " . $ex->getMessage() . "<br />";
                // When Facebook returns an error
            } catch(\Exception $ex) {
                echo "(3) Exception occured, code: " . $ex->getCode();
                echo " with message: " . $ex->getMessage() . "<br />";
                // When validation fails or other local issues
            }
        }
 
        if ( $this->session ) {
            $this->ci->session->set_userdata( 'fb_token', $this->session->getToken() );
 
            $this->session = new FacebookSession( $this->session->getToken() );
        }else{
            $this->session = new FacebookSession( $this->ci->session->userdata("dmfbat") );
        }
    }
 
    public function getFbuid(){
        //$session = new FacebookSession( "CAAUhru6r1cwBAEV2ypXEoujW8rsvHW2ZB8LRNyWgaPHzVokFTvjk9q9YjEdomwtCyVSju7vAGkSamTZBbQhg1tGnjZAQNWAOsZAIaqvEjo65aNCL2TXajEatSq3HLLMmLnWuAibnaVYzvjauTpLmRNGk7O8uWP8gbBSPZAsuQJ8IewFLnvB34qM1tMVOcMN3jqmKPSgcnLQZDZD" );
        try{
            $session = new FacebookSession( $this->ci->session->userdata("dmfbat") );
            /*
            echo "sess: <br />";
            $tmp = $session->getSessionInfo()->asArray();
            var_dump($tmp);
            */
            $user_id = $session->getSessionInfo()->asArray()['user_id'];
            return $user_id;
        }catch(Exception $e){
            return 0;
        }
    }
 
    public function get_login_url() {
        return $this->helper->getLoginUrl( $this->ci->config->item('permissions') );
    }
 
    public function get_logout_url() {
        if ( $this->session ) {
            return $this->helper->getLogoutUrl( $this->session, site_url( 'logout' ) );
        }
        return false;
    }
 
    public function get_user() {
        $session = new FacebookSession( $this->ci->session->userdata("dmfbat") );
        
        if ( $session ) {
            try {
                $request = (new FacebookRequest( $session, 'GET', '/me' ))->execute();
                $user = $request->getGraphObject()->asArray();
 
                return $user;
 
            } catch(FacebookRequestException $e) {
                return false;
 
                /*echo "Exception occured, code: " . $e->getCode();
                echo " with message: " . $e->getMessage();*/
            }
        }else{
        }
    }
    
    public function get_requests() {
        $session = new FacebookSession( $this->ci->session->userdata("dmfbat") );

        if ( $session ) {
            $request = new FacebookRequest(
                $session,
                'GET',
                '/me/apprequests'
            );
            $response = $request->execute();

            $graphObject = $response->getGraphObject()->asArray();

            return $graphObject;
        }else{
            return false;
        }
    }
    
    public function borrar_requests($id) {
        $session = new FacebookSession( $this->ci->session->userdata("dmfbat") );

        if ( $session ) {
            $request = new FacebookRequest(
                $session,
                'DELETE',
                '/'.$id
            );
            $response = $request->execute();

            $graphObject = $response->getGraphObject()->asArray();

            return $graphObject;
        }else{
            return false;
        }
    }
}
 
/* End of file Facebook.php */