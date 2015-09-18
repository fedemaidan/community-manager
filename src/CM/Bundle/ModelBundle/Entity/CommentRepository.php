<?php

namespace CM\Bundle\ModelBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends EntityRepository
{
	
	private function getUltimoComentarioCalificado($post) {
		$query = $this->createQueryBuilder('c');
		$query->select('c');
		$query->where('c.post = ?1');
		$query->andWhere('c.calificacion != ?2');
		$query->setMaxResults(1);
		$query->orderBy('c.fecha_de_creacion', 'ASC');
		
		$query->setParameter(1,$post);
		$query->setParameter(2,0);
		foreach ($query->getQuery()->getResult() as $com) {
			return $com; 
		}
		return null;
	}
	
	public function getComentariosPaginadosByPost($post, $cantidad) {
		$ultimoComentarioCalificado = $this->getUltimoComentarioCalificado($post);
		
		$query = $this->createQueryBuilder('c');
		$query->select('c');
		$query->where('c.post = ?1');
		$query->andWhere('c.fecha_de_creacion >= ?2');
		$query->andWhere('c.calificacion != 4');	
		$query->orderBy('c.fecha_de_creacion', 'DESC');
		
		$query->setMaxResults($cantidad);
		$query->setParameter(1,$post);
		
		$query->setParameter(2, isset($ultimoComentarioCalificado) ? $ultimoComentarioCalificado->getFechaDeCreacion(): "0");
		
		return $query->getQuery()->getResult();
	}
	
	public function getComentariosPaginadosAnterioresByPost($post, $created_time, $cantidad) {
		$query = $this->createQueryBuilder('c');
		$query->select('c');
		$query->where('c.post = ?1');
	
		$query->andWhere('c.fecha_de_creacion < ?2');
		$query->andWhere('c.calificacion != 4');
		$query->setParameter(2,$created_time);
	
		$query->orderBy('c.fecha_de_creacion', 'ASC');
	
		$query->setMaxResults($cantidad);
		$query->setParameter(1,$post);
	
	
		return $query->getQuery()->getResult();
	}
	
	public function getComentariosPaginadosPosterioresByPost($post, $created_time, $cantidad) {
		$query = $this->createQueryBuilder('c');
		$query->select('c');
		$query->where('c.post = ?1');
	
		$query->andWhere('c.fecha_de_creacion > ?2');
		$query->andWhere('c.calificacion != 4');
		$query->setParameter(2,$created_time);
	
		$query->orderBy('c.fecha_de_creacion', 'DESC');
	
		$query->setMaxResults($cantidad);
		$query->setParameter(1,$post);
	
		return $query->getQuery()->getResult();
	}

	public function getComentariosPaginadosConFiltros($filtros, $time_desde,$cantidad) {
		
		$query = $this->createQueryBuilder('c');
		$query->select('c');
		if ($time_desde) {
			$query->andWhere('c.fecha_de_creacion > ?1');
			$query->setParameter(1,$time_desde);
		}
		if ($filtros->getFanPageId()) {
			$query->andWhere('c.fan_page_id > ?2');
			$query->setParameter(2,$filtros->getFanPageId());
		}
		if ($filtros->getQualification()) {
			$query->andWhere('c.calificacion > ?3');
			$query->setParameter(3,$filtros->getQualification());
		}
		
		$query->setMaxResults($cantidad);
		return $query->getQuery()->getResult();
	}
	
	
	
	public function getCommentsMasViejosSinCalificar($limit) {
		$query = $this->createQueryBuilder('c');
		$query->select('c');
		$query->andWhere('c.calificacion = 0');
		$query->andWhere('c.fecha_de_bloqueo is null');
		$query->setMaxResults($limit);
		$query->orderBy('c.fecha_de_creacion', 'ASC');
		
		return $query->getQuery()->getResult();
	}
		
	public function getCantidadDeCalificacionesAgrupadas($desde,$hasta) {
			$conn = $this->getEntityManager()->getConnection();
			$query = "select calificacion, count(*) as cantidad from comment where calificacion != 0 and date(fechaDeCreacion) between :desde  and  :hasta group by calificacion";
			
			$consulta = $conn->prepare($query);
			$consulta->bindParam(':desde',$desde);
			$consulta->bindParam(':hasta',$hasta);
			
			$consulta->execute();
			
			return $consulta->fetchAll();
	
	}

	public function getFBIdsPeople($ids, $calificaciones){
		$conn = $this->getEntityManager()->getConnection();

		$my_ids = explode(', ', $ids);
		$params = array_fill(0, count($my_ids), '?');
		$params = implode(',', $params);

		$condicionCalificacion = "";
		$primera = true;
		if ($calificaciones != null	) {
		foreach ($calificaciones as $calificacion) {
			if ($primera) {
				$condicionCalificacion = "AND ( calificacion = ".$calificacion;
				$primera = false;
			}
			else {
				$condicionCalificacion .= " OR calificacion = ".$calificacion;
			}
		}
		if($condicionCalificacion != "") $condicionCalificacion .= ")";
		}
		$query = "select distinct(persona_facebook_id) from comment where post_id in ({$params}) {$condicionCalificacion}";


		$consulta = $conn->prepare($query);
		foreach($my_ids as $key => $id) {
			$consulta->bindValue($key+1,$id);
		}

		 
		$consulta->execute();
		 
		return $consulta->fetchAll();
	}
	

	function getCommentByLockedTime($tiempoDesbloqueo) {

		$query = $this->createQueryBuilder('c');
		$query->select('c');
		$query->andWhere('c.fecha_de_bloqueo < ?1');
		$query->setParameter(1,$tiempoDesbloqueo);
		
		return $query->getQuery()->getResult();
	}

	function getCommentPaginedByFilters($posts,$limit,$start) {
		//rango
		$rango = isset($posts['rango']) ? explode(' - ', $posts['rango']) : null;

		//ids
		$ids = isset($posts['post']) ? $posts['post'] : null;
		
		//calificacion
		$calificacion = isset($posts['calificacion']) ? $posts['calificacion']: NULL;

		$query = $this->createQueryBuilder('c');
		$query->select('c');
		if ($ids){
			$ids = implode(', ',$ids);
			$query->andWhere('c.post IN (:ids)')->setParameter('ids',$ids);
		}
		if ($rango) {
			$desde = $rango[0];
			$hasta = $rango[1];
			$query->andWhere('c.fecha_de_creacion between ?2 and ?3');
			$query->setParameter(2,$desde);
			$query->setParameter(3,$hasta);
		}
		if ($calificacion) {			
			if (is_array($calificacion)) {
				$calificacion = implode(', ', $calificacion);
				$query->andWhere('c.calificacion IN (:calificacion)')->setParameter('calificacion',$calificacion);
			}
			else {
				$query->andWhere('c.calificacion = ?1')->setParameter(1,$calificacion);
			}
		}
		$query->setFirstResult($start);
		$query->setMaxResults($limit);
		$query->orderBy('c.fecha_de_creacion', 'DESC');

		return $query->getQuery()->getResult();

	}
}

?>