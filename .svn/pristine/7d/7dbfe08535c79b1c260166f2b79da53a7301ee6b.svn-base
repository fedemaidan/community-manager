<?php

namespace CM\Bundle\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
	/**
     * @Route("/", name="dashboard")
     * @Template()
     */
    public function indexAction(Request $request)
    {

        return array();
    }

    /**
     * @Route("/DB/likesForFanpage", name="DB/likesForFanpage")
     */
    public function likesForFanpageAction(Request $request)
    {
    	
    	$array = $this->pedirLikesPorFanpage();
    	
    	$response = new Response(json_encode($array));
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }

    /**
     * @Route("/DB/likesForFanpageWithRange", name="DB/likesForFanpageWithRange")
     */
    
    public function likesForFanpageWithRangeAction(Request $request)
    {
    	$desde = date('Y-m-d', strtotime('-1 month'));
    	$hasta = date('Y-m-d');
    	$fechas = $this->arrayDeFechasEntre($desde,$hasta);
    	$array = $this->pedirLikesPorFanpageWithRange($desde,$hasta);
    	
    	$response = new Response(
    			 	json_encode(array ( 
    					'data' => $array,
    					'categorias' => $fechas
    					 ))
    			);
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
    
    

    /**
     * @Route("/DB/contadorCalificacionesConRango", name="DB/contadorCalificacionesConRango")
     */
    
    public function contadorCalificacionesConRangoAction(Request $request)
    {
    	$fechas = explode(' - ',$request->get('daterange',''));
    	$desde = $fechas[0];
    	$hasta = $fechas[1];
    	
    	
    	$array = $this->calcularCalificacionesDelRango($desde,$hasta);
    	$response = new Response(json_encode($array));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    
    private function pedirLikesPorFanpage () {
    	$array = array();
    	$conjunto = $this->container->get('cm_service')->getFanpagesLikesByFecha(date('Y-m-d'));
    	
    	foreach ($conjunto as $fanpageLike) {
    		$array += array( $fanpageLike->getFanpage()->getName() => $fanpageLike->getCantidad());
    	}
    	
    	return $array;
    }
    
    private function pedirLikesPorFanpageWithRange($desde,$hasta) {
    	$array = array();
    	$conjunto = $this->container->get('cm_service')->getFanpagesLikesByFecha($desde,$hasta);
    	$ant = '';
    	
    	foreach ($conjunto as $fanpageLike) {
    		if ($ant == $fanpageLike->getFanpage()) {
    			$array[$fanpageLike->getFanpage()->getName()][] = intval($fanpageLike->getCantidad());

    		}
    		else {
    			$array[$fanpageLike->getFanpage()->getName()][] = intval($fanpageLike->getCantidad());
    		}
    			
    		$ant = $fanpageLike->getFanpage();
    	}	
    	
    	return $array;
    }
    
    private function arrayDeFechasEntre($desde,$hasta) {
    	$fechas = array();
    	$fec = $desde;
    	
    	for ($i = 30; $fec != $hasta; $i--) {
    		$fec = date('Y-m-d', strtotime('-'.$i.' day'));
    		$dia = date('d-m', strtotime('-'.$i.' day'));
    		$fechas[] = $dia;
    	}
    	
    	return $fechas;
    }
    
    private function calcularCalificacionesDelRango($desde,$hasta) {  	
    	$arrayComentarios = $this->container->get('cm_service')->getCantidadDeCalificacionesDeCommentariosAgrupadas($desde,$hasta);
    	$calificaciones = array();
    	$calificaciones['Positivos'] = 0;
    	$calificaciones['Neutrales'] = 0;
    	$calificaciones['Negativos'] = 0;
    	
    	foreach ($arrayComentarios as $row) {
    		if ($row['calificacion'] == "2") $calificaciones['Positivos'] = intval($row['cantidad']);
    		if ($row['calificacion'] == "1") $calificaciones['Neutrales'] = intval($row['cantidad']);
    		if ($row['calificacion'] == "3") $calificaciones['Negativos'] = intval($row['cantidad']);
    	}
    	
   		$calificaciones['Positivos'] += intval($this->container->get('cm_service')->getCantidadDeLikesDePosts($desde,$hasta));
    	$calificaciones['Positivos'] += intval($this->container->get('cm_service')->getCantidadDeSharesDePosts($desde,$hasta));
    	
    	return $calificaciones;
  
    }
}

?>