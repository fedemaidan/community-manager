<?php

namespace CM\Bundle\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ABMFanPageController extends Controller {
	
	/**
	 * @Route("conf_fanpage", name="abmFanPage")
	 * @Security("has_role('ROLE_ADMIN')")
	 * @Template()
	 */
	public function indexAction(Request $request)
	{
		$fanpages = $this->container->get('cm_service')->getFanPages();
		$fanpage = $this->container->get('cm_service')->getFanPageById($request->get('fanpage',null));
		
		
		return array('pestaña' => $request->get('pestaña','lista'),
				'fanpages' => $fanpages,
				'fanpage' => $fanpage);
		
	}
	
	/**
	 * @Route("/alta/fanpage", name="altaFanPage")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function altaFanPageAction(Request $request)
	{
		$resultado = $this->container->get('cm_service')->insertarFanPage($request->get('nombre'),
															$request->get('access_token'),
															$request->get('url'),
															$request->get('fb_id'),
															$request->get('app_id'),
															$request->get('app_secret'));
		
		//actualizo fanpages de session
		$fan_pages = $this->container->get('cm_service')->getFanPages();
		$session = $request->getSession();
		$session->set('fan_pages', $fan_pages);

		return $this->redirect($this->generateUrl('abmFanPage',
				array('pestaña' => 'alta')));
	}
	
	/**
	 * @Route("/baja/fanpage", name="bajaFanPage")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	
	public function bajaFanPageAction(Request $request) {
		$fanpages = $request->get('fanpages');
		
		foreach ($fanpages as $fanpage_id) {
			$this->container->get('cm_service')->eliminarFanPage($fanpage_id);
		}
		//actualizo fanpages de session
		$fan_pages = $this->container->get('cm_service')->getFanPages();
		$session = $request->getSession();
		$session->set('fan_pages', $fan_pages);
		
		return $this->redirect($this->generateUrl('abmFanPage',
				array('pestaña' => 'baja')));
	}
	
	/**
	 * @Route("/modificar/selecccionarFanpage", name="seleccionarFanPage")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	
	public function seleccionarFanPageAction(Request $request) {
		
		$fanpage_id = $request->get('fanpage');
		$fanpage = $this->container->get('cm_service')->getFanPageById($fanpage_id);

		
		return $this->redirect($this->generateUrl('abmFanPage',
				array('pestaña' => 'modificacion',
						'fanpage' => $fanpage_id)));
	}
	
	/**
	 * @Route("/modificar/fanpage", name="modificarFanPage")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	
	public function modificarFanPageAction(Request $request) {
		$resultado = $this->container->get('cm_service')->actualizarFanPage($request->get('id'),
				$request->get('nombre'),
				$request->get('access_token'),
				$request->get('url'),
				$request->get('fb_id'),
				$request->get('app_id'),
				$request->get('app_secret'));
		
		//actualizo fanpages de session
		$fan_pages = $this->container->get('cm_service')->getFanPages();
		$session = $request->getSession();
		$session->set('fan_pages', $fan_pages);
		
		return $this->redirect($this->generateUrl('abmFanPage',
				array('pestaña' => 'lista')));
	}
	
	/**
	 * @Route("/loginFacebook", name="loginFacebook")
	 * @Security("has_role('ROLE_ADMIN')")
	 * @Template()
	 */
	
	public function loginFacebookAction(Request $request) {
		return array("appId" => $request->get('appId'), "appSecret" => $request->get('appSecret'), "fanpageId" => $request->get('fanpageId'));
	}
	
	/**
	 * @Route("/renuevaAccessToken", name="renuevaAccessToken")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	
	public function renuevaAccessTokenAction(Request $request) {
		$fanpageId = $request->get("fanpageId");
		$access_token = $request->get("access_token");
		var_dump($access_token);die;
		$app_id = $request->get("appId");
		$app_secret = $request->get("appSecret");
		
 		$access_tokenPermanent = $this->generarAccessTokenPermanent($access_token, $app_id, $app_secret);
 		if ($access_tokenPermanent != null) {
 			$this->container->get('cm_service')->actualizarAppSecretDeLaFanpage($fanpageId,$access_tokenPermanent);
 			$resultado = $access_tokenPermanent;
 		}
 		else 		
 		 	$resultado = "0";
 			
		return new Response($resultado);
		
	}
	
	private function generarAccessTokenPermanent($access_token, $app_id, $app_secret) {
		
		$dir = "https://graph.facebook.com/oauth/access_token?client_id=".$app_id."&client_secret=".$app_secret."&grant_type=fb_exchange_token&fb_exchange_token=".$access_token;
		
		
		//long
		$resultado = file_get_contents($dir);
		parse_str($resultado, $params);
		$access_tokenLong = $params['access_token'];
		$dir = "https://graph.facebook.com/me/accounts?access_token=".$access_tokenLong;
			
		
		//permanent
		$resultado = file_get_contents($dir);
		$obj = json_decode($resultado,true);
		if (isset($obj['data'][0]))
			$access_tokenPermanent = $obj['data'][0]['access_token'];
		else 
			$access_tokenPermanent = null;
		
		return $access_tokenPermanent;
	}

}