<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matias
 * Date: 9/19/13
 * Time: 2:59 PM
 * To change this template use File | Settings | File Templates.
 */

namespace CM\Bundle\ModelBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;

class FacebookService {

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    private function initFacebookSession()
    {
        $app_id = $this->container->getParameter('app_id');
        $app_secret = $this->container->getParameter('app_secret');

        FacebookSession::setDefaultApplication($app_id, $app_secret);

        // If you're making app-level requests:
        $session = FacebookSession::newAppSession();

        // To validate the session:
        try {
          $session->validate();
        } catch (FacebookRequestException $ex) {
          // Session not valid, Graph API returned an exception with the reason.
          echo $ex->getMessage();
        } catch (\Exception $ex) {
          // Graph API returned info, but it may mismatch the current app or have expired.
          echo $ex->getMessage();
        }

        return $session;
    }

    public function sendMessage($fb_conversation_id, $message) {
        $session = $this->initFacebookSession();
        //$params['access_token'] = $this->container->getParameter('fanpagetc_access_token');
        $params['access_token'] = $this->container->get('cm_service')->getAccessTokenDeLaPaginaDeLaConversacion($fb_conversation_id);
        $params['message'] = $message;
		
        $request = new FacebookRequest($session, 'POST',
          "/{$fb_conversation_id}/messages",
          $params);
        $response = $request->execute();
        $graphObject = $response->getGraphObject();

        if($graphObject->getProperty('id') != "") {
            return true;
        }

        return false;
    }

    

    
	public function deleteComment($fb_comment_id) {
		$session = $this->initFacebookSession();
		//token de la pagina
		//$params['access_token'] = $this->container->getParameter('fanpagetc_access_token');
		$params['access_token'] = $this->container->get('cm_service')->getAccessTokenDeLaPaginaDelComment($fb_comment_id);
		
		
		$request = new FacebookRequest($session, 'DELETE',
				"/{$fb_comment_id}",
				$params);
		
		$response = $request->execute();
		$graphObject = $response->getGraphObject();
		
		if($graphObject->getProperty('succes') == true) {
			return true;
		}
		
		return false;		
	}
}
