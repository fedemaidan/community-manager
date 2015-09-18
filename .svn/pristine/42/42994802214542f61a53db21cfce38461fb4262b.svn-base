<?php

namespace CM\Bundle\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ABMGmailController extends Controller {
	
	/**
	 * @Route("conf_gmail", name="abmGmail")
	 * @Security("has_role('ROLE_ADMIN')")
	 * @Template()
	 */
	public function indexAction(Request $request)
	{
		return array();
	}
	
	/**
	 * @Route("/alta/gmail", name="altaGmail")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function altaGmailAction(Request $request)
	{
// 		$resultado = $this->container->get('cm_service')->insertarFanPage($request->get('nombre'),
// 															$request->get('access_token'),
// 															$request->get('url'),
// 															$request->get('fb_id'),
// 															$request->get('app_id'),
// 															$request->get('app_secret'));
		
		return new Response("Cargado con exito TODO");
	}
	
	/**
	 * @Route("/baja/gmail", name="bajaGmail")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	
	public function bajaGmailAction(Request $request) {
// 		$fanpages = $request->get('fanpages');
		
// 		foreach ($fanpages as $fanpage_id) {
// 			$this->container->get('cm_service')->eliminarFanPage($fanpage_id);
// 		}
		
		return new Response("Eliminar con exito TODO");
	}
	
	/**
	 * @Route("/modificar/selecccionarGmail", name="seleccionarGmail")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	
	public function seleccionarGmailAction(Request $request) {
		
// 		$fanpage_id = $request->get('fanpage');
// 		$fanpage = $this->container->get('cm_service')->getFanPageById($fanpage_id);
// 		$array['name'] = $fanpage->getName();
// 		$array['access_token'] = $fanpage->getAccessToken();
// 		$array['url'] = $fanpage->getUrl();
// 		$array['fb_id'] = $fanpage->getFbId();
// 		$array['app_id'] = $fanpage->getAppId();
// 		$array['app_secret'] = $fanpage->getAppSecret();
		
		return new Response("TODO");
	}
	
	/**
	 * @Route("/modificar/gmail", name="modificarGmail")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	
	public function modificarGmailAction(Request $request) {
// 		$resultado = $this->container->get('cm_service')->actualizarFanPage($request->get('id'),
// 				$request->get('nombre'),
// 				$request->get('access_token'),
// 				$request->get('url'),
// 				$request->get('fb_id'),
// 				$request->get('app_id'),
// 				$request->get('app_secret'));
		
		return new Response("Actualizado con exito TODO");
	}
}