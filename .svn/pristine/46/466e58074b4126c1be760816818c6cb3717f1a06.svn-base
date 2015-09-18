<?php

namespace CM\Bundle\APIBundle\Traits;


use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook;
use Facebook\FacebookRequestException;


trait FacebookTrait {
	
	/**
	 * This perform the FB api request
	 *
	 * @param $edge The URL edge (method) (Example: conversations)
	 * @param array $params The optional params (Example: limits)
	 * @return mixed|string The request response
	 */
	protected function performRequest($node, $edge = '', $params = array(), $asArray = true, $fanpage = null) {
		try {
			if ($fanpage == null) {
				$session = $this->initFacebookSession($this->getFanPage()->getAppId(), $this->getFanPage()->getAppSecret());
				$params['access_token'] = $this->getFanPage()->getAccessToken();
			}
			else {
				$session = $this->initFacebookSession($fanpage->getAppId(), $fanpage->getAppSecret());
				$params['access_token'] = $fanpage->getAccessToken();
			}

			$params['limit'] = isset($params['limit']) ? $params['limit'] : 100;
			$request = new FacebookRequest($session, 'GET', '/'.$node.'/'.$edge, $params);

			//$graphObject = $this->pedirDatosTratandoException($request);
	
			$response = $request->execute();
			$graphObject = $response->getGraphObject();
	
			if($asArray) {
				return $graphObject->asArray();
			} else {
				return $graphObject;
			}
		}
		 
		catch (FacebookServerException $e) {
			$this->output->writeln(date('Y-m-d H:i:s')." - FacebookServerException: ". $e->getHttpStatusCode(). " error tipo:  " . $e->getErrorType() );
			sleep(20);
			return $this->performRequest($node, $edge , $params , $asArray ,$fanpage);
		}
		catch (FacebookThrottleException $e) {
			$this->output->writeln(date('Y-m-d H:i:s')." - FacebookThrottleException: ". $e->getHttpStatusCode(). " error tipo:  " . $e->getErrorType() . " mensaje:  " . $e->getMessage());
			sleep(1860); //31 minutos
			return $this->performRequest($node, $edge , $params , $asArray ,$fanpage);
		}
		catch (FacebookApiException $e) {
			$this->output->writeln(date('Y-m-d H:i:s')." - FacebookApiException: ". $e->getHttpStatusCode(). " error tipo:  " . $e->getErrorType() . " mensaje:  " . $e->getMessage());
			return $this->performRequest($node, $edge , $params , $asArray ,$fanpage);
		}
		catch (Facebook\FacebookSDKException $e) {
			if ($fanpage)
				$this->output->writeln(date('Y-m-d H:i:s')." - Exception SDK " . $fanpage->getName() . " (".$e->getCode() .") mensaje:  " . $e->getMessage());
			else 
				$this->output->writeln(date('Y-m-d H:i:s')." - Exception SDK " . $this->getFanPage()->getName() . " (".$e->getCode() .") mensaje:  " . $e->getMessage());
			//sleep(20);
			if ($e->getCode() == 17 or $e->getCode() == 4 or $e->getCode() == 613) { //demasiadas llamadas
				sleep(2000);
				return $this->performRequest($node, $edge , $params , $asArray ,$fanpage);
			}
			if ($e->getCode() == 190) { //change password
				$this->output->writeln(date('Y-m-d H:i:s')." - Exception SDK -> actualizar access_token");
				if ($fanpage) {
					$fanpage->setAccesTokenActualizado(false);
					$this->getManager()->persist($fanpage);
				}
				else {
					$this->getFanPage()->setAccesTokenActualizado(false);
					$this->getManager()->persist($this->getFanPage());
				}
				
				$this->getManager()->flush();
				
				
				//termina proceso
			}
			else if ($e->getCode() == 7) { // connection timeout
				$this->output->writeln(date('Y-m-d H:i:s')." - Exception SDK -> Exit -> Connection timeout");
				exit("Connection timeout");
			}  
			else {
				return $this->performRequest($node, $edge , $params , $asArray ,$fanpage);
			}
		}
	}
	
	
	/**
	 * This initializes and returns the facebook session
	 * @return \FacebookSession
	 */
	protected function initFacebookSession($app_id, $app_secret) {
	
		FacebookSession::setDefaultApplication($app_id, $app_secret);
	
		// If you're making app-level requests:
		$session = FacebookSession::newAppSession();
	
		//$session->validate();
		//To validate the session:
		        try {
		          $session->validate();
		        }
		        catch (FacebookRequestException $ex) {
		          // Session not valid, Graph API returned an exception with the reason.
		          echo $ex->getMessage() . " Session not valid";
		          
		        }
		        catch (\Exception $ex) {
		          // Graph API returned info, but it may mismatch the current app or have expired.
		          echo $ex->getMessage();
		          $this->initFacebookSession($app_id, $app_secret);
		        }
	
		return $session;
		}
	
}