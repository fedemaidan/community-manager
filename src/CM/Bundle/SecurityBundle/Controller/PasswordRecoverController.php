<?php

namespace CM\Bundle\SecurityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class PasswordRecoverController extends Controller
{

	/**
	 * @Route("/recoverpasswordpage", name="recoverPasswordPage")
	 * @Template()
	 */
	public function indexAction(Request $request)
	{
		$error = $request->get('error',null);
        return array(
            'last_username' => $request->getSession()->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        );
	}


	/**
	 * @Route("/passwordRecover", name="recoverPassword")
	 */
	public function passwordRecoverAction(Request $request)
	{
		$user = $this->container->get('cm_service')->getUserByEmail($request->get('username'));
		
		if (!$user) {
			return $this->redirect($this->generateUrl('recoverPasswordPage',
				array('error' => 'Usuario no encontrado')));
		}
		
		$password = $this->randomString(6);
		$passwordAnterior = $user->getPassword();
		
		$this->container->get('cm_service')->actualizarPassword($user,$password);
		$resultado = $this->enviarMailDeRecuperacionDeContraseña($user,$password);
		
		if ($resultado != "Enviado"){
			$this->container->get('cm_service')->actualizarPassword($user,$passwordAnterior);
			return $this->redirect($this->generateUrl('recoverPasswordPage',
				array('error' => $resultado)));
		}

		return $this->redirect($this->generateUrl('cm_login'));
	}
	
	function randomString($length = 6) {
		$str = "";
		$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		return $str;
	}
	
	
	function enviarMailDeRecuperacionDeContraseña($user, $password) {
		try {
		$message = \Swift_Message::newInstance()
		->setSubject('Hello Email')
		->setFrom('falso@gmail.com')
		->setTo($user->getEmail())
		->setBody(
				$this->renderView(
						'CMSecurityBundle:PasswordRecover:email.txt.twig',
						array('password' => $password)
				)
		);
		
		$this->get('mailer')->send($message);
		return "Enviado";
		}
		catch (\Swift_RfcComplianceException $e) {
			return "Accion no completada por mail incorrecto";
			
		}
		catch (\Swift_TransportException $e) {
			return "Accion no completada por problemas de conexion";

		}
	}
	
}
