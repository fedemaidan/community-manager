<?php

namespace CM\Bundle\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LogController extends Controller
{
	public function logsAction(Request $request)
	{
		$absolute_path = realpath(__DIR__.'/../../../../..');
		$archivo = fopen($absolute_path."/app/logs/".$request->get('archivo'),'r');
		
		$respuesta = "";
		while (!feof($archivo)) {
			$respuesta .= fgets($archivo)."<br>";		
		}
		
	
		return new Response($respuesta);
	}

	
}