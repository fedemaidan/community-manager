<?php

namespace CM\Bundle\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AudienciasController extends Controller
{
	/**
     * @Route("/audiencias", name="audiencias")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_AUDIENCE_GENERA')")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return array();
    }
    
    /**
     * @Route("AU/generarAudiencia", name="generarAudiencias")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_AUDIENCE_GENERA')")
     */

    public function generarAudienciaAction(Request $request)
    {
    	
    	$fanpages = $request->get('fanpages',null);
    	$canalDeInteraccion = $request->get('canalDeInteraccion');
    	$calificaciones = $request->get('calificaciones',null);
    	$texto = $request->get('texto','');
    	$tags = $request->get('search-tags','');
    	$fechas = explode(' - ',$request->get('daterange',''));
    	$desde = $fechas[0];
    	$hasta = $fechas[1]; 
    	
    	
    	$audiencia = $this->container->get('cm_service')->getAudiencia($fanpages,$canalDeInteraccion,$calificaciones,$texto,$tags,$desde,$hasta);

    	
    	/////EXPORTA A CSV/////////////////////////////
    	$response = new StreamedResponse();
    	$response->setCallback(
    			function () use ($audiencia) {
    				$handle = fopen('php://output', 'r+');
    				
    				foreach ($audiencia as $row) {
    					fputcsv($handle, $row);
    				}
    				
    				fclose($handle);
    			}
    	);
    	
    	$fileName = "audiencia".date('Y-m-d H-i-s').'.csv';
    	$response->headers->set('Content-Type', 'application/force-download');
    	$response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'"');
    	//////////////////////////////////////////////////////////////////7
    	
  		return $response;  	
//    	return new Response(json_encode($audiencia));
    }
    
    private function exportarCSV($audiencia) {
    	$response = new StreamedResponse();
    	$response->setCallback(
    			function () use ($results) {
    				$handle = fopen('php://output', 'r+');
    				if(!empty($results)) {
    					fputcsv($handle,
    					array_keys($results[0])
    					);
    				}
    				foreach ($results as $row) {
    					fputcsv($handle, $row);
    				}
    				fclose($handle);
    			}
    	);
    	
    	$fileName = "alumnos_ranking_".date('YmdHis').'.csv';
    	$response->headers->set('Content-Type', 'application/force-download');
    	$response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'"');
    	
    	return $response;
    	
    }

}

?>