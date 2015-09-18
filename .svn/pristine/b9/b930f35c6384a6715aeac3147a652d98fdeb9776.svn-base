<?php

namespace CM\Bundle\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CM\Bundle\ModelBundle\Entity\PostRepository;

class PostController extends Controller {
       
		const DEFAULT_FANPAGE_ALL = null;
		private $limitComentarios = 10; 
		private $limitPost = 6;
		
       /**
        * @Route("/indexPost", name="index_posts")
        * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE') or has_role('ROLE_MODEARATOR')")
        * @Template()
        */
       
       public function indexAction(Request $request) {
       	
       			$filter = $request->getSession()->get('filter');
       							 
				//cargo el objeto fan page
				$fanPage = $this->container->get('cm_service')->getFanPageById($filter->getFanPageId());
				if ($fanPage != null)
					$filter->setFanPageName($fanPage->getName());
				else 
					$filter->setFanPageName("Todos");
				
				$request->getSession()->set("filter", $filter);
				
               return array (
                               'fanPage' => $fanPage,
                               );
       }
       
       /**
        * @Route("/PT/posts" , name="posts")
        * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE') or has_role('ROLE_MODEARATOR')")
        * @Template()
        * 
        */
       
       public function postAction(Request $request) {
       			$filter = $request->getSession()->get('filter');
               $fan_page_id = $filter->getFanPageId();
               $created_time = $request->get('created_time',null);
               
               if ($fan_page_id  == "0")
               		$posts = $this->get('cm_service')->getPosts($created_time, $this->limitPost);
               else 
               		$posts = $this->get('cm_service')->getPostsByFanPage($fan_page_id,$created_time, $this->limitPost);

               return array(
                       'posts' => $posts,
               );
       }
        
       /**
        * @Route("/PT/allCommentsUploaded", name="allCommentsUploaded")
        * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE') or has_role('ROLE_MODEARATOR')")
        * @Template()
        */
       
       public function allCommentsUploadedAction(Request $request) {
       	$filter = $request->getSession()->get('filter');
       		$fan_page_id = $filter->getFanPageId();
       		
       		//TODO CAMBIAR CREATED POR UPDATED		 
       		$created_time = $request->get('created_time',null);
       		
       		if ($fan_page_id  == "0" )
       			$posts = $this->get('cm_service')->getPosts($created_time, $this->limitPost);
       		else
       			$posts = $this->get('cm_service')->getPostsByFanPage($fan_page_id,$created_time, $this->limitPost);
       		 
       		$comentariosDelPost = array();
       	
       		foreach ($posts as $post) {
       			$comentariosDelPost[$post->getId()] = $this->container->get('cm_service')->getComentariosPaginadosByPost($post->getId(),null,$this->limitComentarios);
       		}
       	
       	
       		return array (
       			'posts' => $comentariosDelPost,
       		);
       	
       }

       /**
        * @Route("/PT/addComments", name="addComments")
        * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE') or has_role('ROLE_MODEARATOR')")
        * @Template()
        */
        
       public function addCommentsAction(Request $request) {
       	
	       	$post_id = $request->get('post_id',null);
	       	$created_time = $request->get('created_time',null);
	       	$type = $request->get('type');
	       	
	       	$comentariosDelPost = array();
	       	if ($type == "prepend")   
	       		$comentariosAgregados = array_reverse($this->container->get('cm_service')->getComentariosPaginadosAnterioresByPost($post_id,$created_time,$this->limitComentarios));
	       	else if ($type == "append")
	       		$comentariosAgregados = $this->container->get('cm_service')->getComentariosPaginadosPosterioresByPost($post_id,$created_time,$this->limitComentarios);
	       	
	       	return array (
	       			'comentariosNuevos' => $comentariosAgregados,
	       	);
	       
       }

       /**
        * @Route("/PT/comment", name="comment")
        * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE') or has_role('ROLE_MODEARATOR')")
        * @Template()
        */
           
       public function commentAction(Request $request, $comentario) {
       			return array (
       				'comentario' => $comentario,
       			);
       }
       
       /**
        * 
        * @Route("/PT/qualify", name="comment_calificar")
        * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE') or has_role('ROLE_MODEARATOR')")
        */
       
       public function qualifyAction(Request $request) {
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
        * @Route("/PT/retrieveTags", name = "post_retrieve_tags")
        * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE') or has_role('ROLE_MODEARATOR')")
        */
       
       public function retrieveTagsAction(Request $request) {
               if ($request->isXmlHttpRequest()) {
                       $result = $this->get('cm_service')->getTagsByName($request->get('term'));
                       $response = new Response(json_encode($result));
                       $response->headers->set('Content-Type','application/json');
               }
               else {
                       $response = new Respose();
               }
               return $response;
       }
       
       /**
        * @Route("/PT/getPostTags", name="post_tags")
        * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE') or has_role('ROLE_MODEARATOR')")
        */
       
       public function getPostTagsAction(Request $request) {
               if ($request->isXmlHttpRequest()) {
                       $result = $this->get('cm_service')->getTagsByPost($request->get('post_id'));
                       $response = new Response(json_encode($result));
                       $response->headers->set('Content-Type', 'application/json');
               }
               else {
                       $response = new Response();
               }
               return $response;
       }
       
       /**
        * @Route("/PT/updateTags", name="post_tags_update")
        * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE') or has_role('ROLE_MODEARATOR')")
        */
       
       public function updateTags(Request $request) {
               if ($request->isXmlHttpRequest()) {
                		$post = $this->get('cm_service')->getPostById($request->get('post_id'));
                		$tag_name = $request->get('tag_name');
                		$remove = $request->get('remove',false);
                		$result = $this->get('cm_service')->updateTags($post,$tag_name,$remove);     
               	
                       $response = new Response(json_encode($result));
                       $response->headers->set('Content-Type', 'application/json');
               }
               else {
                       $response = new Response();
               }
               
               return $response;
       }
       

       /**
        * @Route("/PT/cargarFiltrosEnSesion", name="cargarFiltrosEnSesionPT")
        * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE')")
        *
        */
       
       public function cargarFiltrosEnSesionAction(Request $request)
       {
       
       	$filter = $request->getSession()->get('filter');
       	$filter->setFanPageId($request->get('fanpageId', "0"));
       	
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
        
       
}
