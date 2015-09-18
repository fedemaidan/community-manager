<?php

namespace CM\Bundle\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CM\Bundle\ModelBundle\Entity\Post;

class CommentsController extends Controller {
	
	/**
	 * @Route("/indexComments", name="index_comments")
	 *  @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE') or has_role('ROLE_MODEARATOR')")
	 * @Template()
	 */
	
	public function indexAction(Request $request) {
		
	}
	

    /**
    * @Route("/CM/mostrarComentarios" , name="mostrarComentarios")
    * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE') or has_role('ROLE_MODEARATOR')")
	* @Template()
    */
	
	public function mostrarComentariosAction(Request $request) {
		$filter = $request->getSession()->get('filter')->toArrayPost();
		$comentarios = $this->get('cm_service')->getCommentsPaginedWithFilters($filter);
		return array (
			'comentarios' => $comentarios,
		);
	}
	
	/**
	 * @Route("/CM/addComments" , name="agregarComentarios")
	 *  @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE') or has_role('ROLE_MODEARATOR')")
	 * @Template()
	 */
	
	public function addCommentsAction(Request $request) {
		$filter = $request->getSession()->get("filter")->toArrayPost();
		$request->getSession()->get("filter")->toArray();
		$comentarios = $this->get('cm_service')->getCommentsPaginedWithFilters($filter,$request->get('start'));
		return array (
				'comentariosNuevos' => $comentarios,
		);
	}
	
	/**
	 * @Route("/CM/deleteComments" , name="borrarComentario")
	 *  @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE') or has_role('ROLE_MODEARATOR')")
	 * @Template()
	 */
	
	public function deleteCommentsAction(Request $request) {
		//session_write_close();
		$calificacionBorrado = 4;
// 		if ($request->isXmlHttpRequest()) {
			$comentario =$this->get('cm_service')->getCommentById($request->get("comentario_id"));
			$result = $this->get('cm_service')->deleteCommentInFacebook($comentario);
			$result = $this->container->get('cm_service')->qualify($comentario,$calificacionBorrado);
			$response = new Response(json_encode($result));
			$response->headers->set('Content-Type','application/json');
// 		}
// 		else {
// 			return array('mensaje' => "No se puede borrar");
// 		}
		
		if ($result)
			return array('mensaje' => "Comentario borrado");
		else
			return array('mensaje' => "No se puede borrar resultado falso");
		
	}
	

	/**
	 *
	 * @Route("/CM/qualify", name="calificarComentario")
	 *  @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE') or has_role('ROLE_MODEARATOR')")
	 */
	 
	public function qualifyAction(Request $request) {
		//session_write_close();
		if ($request->isXmlHttpRequest()) {
			$comentario =$this->get('cm_service')->getCommentById($request->get('comentario_id'));
			$result = $this->container->get('cm_service')->qualify($comentario,$request->get('calificacion'));
			$response = new Response(json_encode($result));
			$response->headers->set('Content-Type','application/json');
		}
		else {
			$response = new Response();
		}	 
		return $response;
	}
	
	/**
	 * @Route("/CM/cargarFiltrosEnSesion", name="cargarFiltrosEnSesionCM")
	 * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE')")
	 *
	 */
	
	public function cargarFiltrosEnSesionAction(Request $request)
	{
		$filter = $request->getSession()->get('filter');
		$filter->setFanPageId($request->get('fanpageId', null));
		$filter->setText($request->get('texto', null));
		$filter->setQualification($request->get('calificacion', null));
		$filter->setRango($request->get('rango', null));
	
		if($filter->getFanPageId() == 0) {
			$filter->setFanPageName("Todos");
		}
		else {
			$fanPage = $this->container->get('cm_service')->getFanPageById($filter->getFanPageId());
			$filter->setFanPageName($fanPage->getName());
		}
	
		$request->getSession()->set("filter", $filter);
		
		return new Response(json_encode($filter));
	}


	/**
	 *
	 * @Route("/CM/destacar", name="destacarComentario")
	 *  @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE') or has_role('ROLE_MODEARATOR')")
	 */

	public function destacarComentarioAction(Request $request) {
		try {
			$comentarioId = $request->get('comentario_id');
			$valor = $request->get('valor');
			$result = $this->container->get('cm_service')->destacarComentario($comentarioId,$valor);
			$response = new Response(json_encode($result));
			$response->headers->set('Content-Type','application/json');	
			return $response;
		}
		catch (\Exception $e) {
   			return "Error al destacar comentario";
     	}
	}


}
