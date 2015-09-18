<?php

namespace CM\Bundle\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class ApiController extends Controller implements TokenAuthenticatedController
{
	
    public function obtenerComentariosAction($limit)
    {
    	$comentarios = $this->get('cm_service')->getCommentsMasViejosSinCalificar($limit);
    	$this->get('cm_service')->getBloquearComentarios($comentarios);
		
    	$arrayComentarios = $this->comentariosToArray($comentarios);
    	return new Response(json_encode($arrayComentarios));
    }
    
    public function calificarComentariosAction(Request $request)
    {	
    	$ids = '';
    	$arrayComentarios = json_decode($request->get('comentarios'), true);
		foreach ($arrayComentarios as $comentario) {
			$ids['id'][] = $comentario['id'];
		}
    	
    	$comentarios = $this->get('cm_service')->getCommentsByIds($ids);
    	$resultado = $this->calificarComentarios($arrayComentarios,$comentarios);    	
    	return new Response($resultado);
    }
    
    private function comentariosToArray($comentarios) {

    	$array = '';
    	foreach ($comentarios as $comentario) {
    		$arrayCom['id'] = $comentario->getId();
    		$arrayCom['facebookID'] = $comentario->getFacebookID();
    		$arrayCom['personaFacebookId'] = $comentario->getPersonaFacebookId();
    		$arrayCom['personaNombre'] = $comentario->getPersonaNombre();
    		$arrayCom['calificacion'] = $comentario->getCalificacion();
    		$arrayCom['comentario'] = $comentario->getComentario();
    		$arrayCom['fechaDeCreacion'] = $comentario->getFechaDeCreacion();
    		$arrayCom['fechaDeBloqueo'] = $comentario->getFechaDeBloqueo();
    		$arrayCom['postLocalId'] = $comentario->getPost()->getId();
    		$arrayCom['postFacebookId'] = $comentario->getPost()->getFacebookId();
    		$array[] = $arrayCom;	
    		
    	}
    	
    	return $array; 
    }
    
    private function calificarComentarios($arrayComentarios,$comentarios) {
    	
    	$cantidadNoCalificados = $cantidadCalificados = 0;
    	
    	foreach ($comentarios as $comentario) {
    		if ($comentario->getCalificacion() == '0') {
    			foreach ($arrayComentarios as $com) {
    				if ($com['id'] == $comentario->getId()) {
    					$this->container->get('cm_service')->qualify($comentario,$com['calificacion']);
    					$cantidadCalificados++;
    					break;
    				}
    			}		
    		}
    		else {
    			$cantidadNoCalificados++;
    		}
    	}
    	return json_encode(array("calificados" => $cantidadCalificados, "noCalificados" =>$cantidadNoCalificados));
//     	return "Se calificaron ".$cantidadCalificados." y no se calificaron ".$cantidadNoCalificados." por ya estar calificados";
    }
}
