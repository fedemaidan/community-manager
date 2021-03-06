<?php

namespace CM\Bundle\APIBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use CM\Bundle\ModelBundle\Entity\FacebookAPIState;
use CM\Bundle\ModelBundle\Entity\Post;
use CM\Bundle\ModelBundle\Entity\Comment;
use \DateTime;

class CMPostCommand extends CMAbstractCommand {
		
	private $cantidadDePosts;
	private $limitComments = 1000;
	
	protected function configure() {
		parent::configure();
		$this->setName('cm-api:pst')
			->setDescription('Carga los posts')
		    ->addArgument('page',InputArgument::REQUIRED, "The Facebook Fan Page FB ID")
		    ->addArgument('amountPosts',InputArgument::OPTIONAL, "Number of posts");
	}
	
	protected function execute(InputInterface $input, OutputInterface $output) {
		parent::execute($input,$output);

		$this->setCantidadDePosts($input->getArguments('amountPosts')['amountPosts']);
				
		$output->writeln(date('Y-m-d H:i:s')." - Fan Page: ".$this->getFanPage()->getName());
		$this->processPosts();
		$output->writeln(date('Y-m-d H:i:s'). " - Fin");
	}
	
	private function processPosts() {
		//cargo variables api state
	    $fb_api_state = $this->getFacebookAPIState(FacebookAPIState::POSTS_NODE, NULL);
        $since = $newest_since = $until = '';

        if($fb_api_state) {
            $since = $fb_api_state->getSince();
            $newest_since = $fb_api_state->getNewestSince();
            $until = $fb_api_state->getUntil();
        }

        $entra = $fb_posts = $this->pedirPost($since,$until );
        
		//traigo posts
		while($entra ) {
		  
		  	foreach ($fb_posts['data'] as $fb_post) {
		  		if ($this->noSuperaLimiteDePosts()) {
			  		
			  		$post = $this->savePost($fb_post);
			  		$comentariosGuardados = 0;
			  		if ($this->cumpleCondicion($post, $fb_post->updated_time)) {
		  				$comentariosGuardados = $this->processComments($post);
		  				
						$post->setUpdatedTime($fb_post->updated_time);
						$this->actualizarShared($post,$fb_post);
						$this->output->writeln(date('Y-m-d H:i:s'). "\t\t Agregue ".$comentariosGuardados ." comentarios en el post con id: ".$fb_post->id);
						
		  			}
		  			
		  			$this->actualizarCantidadDePostsRestantes();
		  			$this->getManager()->flush();
		  			
		  		}
		  	}
		  
		  if($newest_since == '') {
                $parsed_url = parse_url($fb_posts['paging']->previous);
                parse_str($parsed_url['query'], $url_params);
                $newest_since = $url_params['since'];
            }
            $parsed_url = parse_url($fb_posts['paging']->next);
            parse_str($parsed_url['query'], $url_params);
            $until = $url_params['until'];
            
		  	$this->actualizoFacebookAPIState(FacebookAPIState::POSTS_NODE, $fb_post->id, $since,$newest_since,$until );
		  	$this->getManager()->flush();
		  	
		  	$fb_posts = $this->pedirPost($since,$until );
		  	$entra = isset($fb_posts['data']);
		  	
		}

		$this->actualizoFacebookAPIState(FacebookAPIState::POSTS_NODE, $fb_post->id, $since,$newest_since,$until );
		$this->getManager()->flush();
		$this->getManager()->clear();

	}
	
	/**
	 *
	 * @return FacebookAPIState
	 */
	
	private function getFacebookAPIState($node_type, $postId) {
	
		$facebookAPIState = $this->getManager()->getRepository('CMModelBundle:FacebookAPIState')->findOneBy(array('page' => $this->getFanPage()->getName(), 'node_type' => $node_type, 'post_id' => $postId));

		if (!$facebookAPIState) {
			return false;
		}
		return $facebookAPIState;
	}
	
	private function actualizoFacebookAPIState( $node_type, $post_id, $since, $newest_since,$until) {
// 		if ($this->dry) {
// 			return false; // ???????
// 		}
		
		$facebookAPIState = $this->getManager()->getRepository('CMModelBundle:FacebookAPIState')->findOneBy(array('page' => $this->getFanPage()->getName(), 'node_type' => $node_type, 'post_id' => $post_id));
		

		if (!$facebookAPIState) {
			$facebookAPIState = new FacebookAPIState();
		}
		
		$facebookAPIState->setPage($this->getFanPage()->getName());
		$facebookAPIState->setNodeType($node_type);
		$facebookAPIState->setPostId($post_id);
		$facebookAPIState->setSince($since);
        $facebookAPIState->setNewestSince($newest_since);
        $facebookAPIState->setUntil($until);
		
		
		if($facebookAPIState->getId() == null) {
			$this->getManager()->persist($facebookAPIState);
		}
		
		$this->getManager()->flush();
		
	}
	
	private function actualizoCommentFacebookAPIState( $node_type, $post_id, $after) {
// 		if ($this->dry) {
// 			return false; // ???????
// 		}
	
		$facebookAPIState = $this->getManager()->getRepository('CMModelBundle:FacebookAPIState')->findOneBy(array('page' => $this->getFanPage()->getName(), 'node_type' => $node_type, 'post_id' => $post_id));
	
		
		if (!$facebookAPIState) {
			$facebookAPIState = new FacebookAPIState();
		}
	
		$facebookAPIState->setPage($this->getFanPage()->getName());
		$facebookAPIState->setNodeType($node_type);
		$facebookAPIState->setPostId($post_id);
		$facebookAPIState->setAfter($after);
	
		if($facebookAPIState->getId() == null) {
			$this->getManager()->persist($facebookAPIState);
		}
	
		$this->getManager()->flush();
	
	}
	
	private function cumpleCondicion($post, $updated_time) {
		// la condicion es que haya sufrido modificaciones
		return $post->getUpdatedTime() != $updated_time;
	}
	
	private function savePost($fb_post) {
		$post = $this->getManager()->getRepository('CMModelBundle:Post')->findOneBy(array('facebook_id' => $fb_post->id));
		$tieneObjectId = true;
		
		if (!$post) {
			$post = new Post();
			$post->setActualizarShared(true);
		}
		
		$post->setType($fb_post->type);
		$post->setFechaDeCreacion($fb_post->created_time);
		$post->setFanPage($this->getFanPage());
		$post->setFacebookId($fb_post->id);
		if (isset($fb_post->object_id))
			$post->setObjectId($fb_post->object_id);
		else
			$tieneObjectId = false;
		
		$stringDeBusqueda = "";
		if (isset($fb_post->message)) {
			$post->setMessage($fb_post->message);
			$stringDeBusqueda .= $fb_post->message;
		}
		if (isset($fb_post->caption)) {
			$post->setCaption($fb_post->caption);
			$stringDeBusqueda .= $fb_post->caption;
		}
		if (isset($fb_post->story)) {
			$post->setStory($fb_post->story);
			$stringDeBusqueda .= $fb_post->story;
		}
		if (isset($fb_post->link)) {
			$post->setLink($fb_post->link);
			$stringDeBusqueda .= $fb_post->link;
		}
		if (isset($fb_post->description)) {
			$post->setDescription($fb_post->description);
			$stringDeBusqueda .= $fb_post->description;
		}

		$post->setStringDeBusqueda($stringDeBusqueda);
		
		//persisto
		if ($post->getId() == null) {
			if ($tieneObjectId)
				$this->output->writeln(date('Y-m-d H:i:s')." - \t\t\t Post Nuevo con facebook id: " . $post->getFacebookId());
			else
				$this->output->writeln(date('Y-m-d H:i:s')." - \t\t\t Post nuevo con facebook id: " . $post->getFacebookId(). " sin object_id");
			$this->getManager()->persist($post);
				
		}
		
		
		
		return $post;
	}
	
	private function processComments($post) {
		//cargo variables api state
		$fb_api_state = $this->getFacebookAPIState(FacebookAPIState::POSTS_NODE, $post->getId());
		$after = '';
		
		if($fb_api_state) {
			$after = $fb_api_state->getAfter();
		}
			
		
		$entra = $fb_comments = $this->pedirComments($post,$after);
		
		
		$contador = 0;
		//traigo comentarios
		while($entra) {
			$ultimaPos = count($fb_comments['data']) - 1 ;	
			
			if ($this->esFechaPosterior ($post->getUltimoComentario(), $fb_comments['data'][$ultimaPos]->created_time )) {
				foreach ($fb_comments['data'] as $fb_comment) {
					if ($this->esFechaPosterior($post->getUltimoComentario() ,$fb_comment->created_time))
						$comment = $this->saveComment($fb_comment, $post);
						$contador++;
				}
			}
			 
			if (isset($fb_comments['paging']->cursors->after))
				$after = $fb_comments['paging']->cursors->after;
			else
				$after = '';
				
			$this->actualizoCommentFacebookAPIState(FacebookAPIState::POSTS_NODE, $post->getFacebookId(), $after);
			
			$entra = $fb_comments = $this->pedirComments($post,$after);
			
		}
		
		return $contador;
	}
	
	public function saveComment($fb_comment,$post) {
		
		$comentario = new Comment();
		$comentario->setFacebookID($fb_comment->id);
		if (isset($fb_comment->from)) {
			$comentario->setPersonaFacebookId($fb_comment->from->id);
			$comentario->setPersonaNombre($fb_comment->from->name);
		}
		else {
			$comentario->setPersonaFacebookId("1");
			$comentario->setPersonaNombre("Persona desconocida");
		}
		$comentario->setCalificacion(0);
		$comentario->setComentario($fb_comment->message);
		$comentario->setFechaDeCreacion($fb_comment->created_time);
		$comentario->setPost($post);
		
		
		
		$post->setUltimoComentario($comentario->getFechaDeCreacion());
		
		$this->getManager()->persist($comentario);
		$this->getManager()->persist($post);
		return $comentario;
		
	}
	
	public function  esFechaPosterior($ultimoComentarioFecha,$nuevoFecha) {
		return strtotime($ultimoComentarioFecha) < strtotime($nuevoFecha);  
	}
	
	private function setCantidadDePosts($cantidad) {
		
		if (isset($cantidad))
			$this->cantidadDePosts = $this->pasarStringAInt($cantidad);
		else 
			$this->cantidadDePosts = 9999999; 
	}
	
	private function actualizarCantidadDePostsRestantes() {
		$this->cantidadDePosts--;
	}
	
	private function noSuperaLimiteDePosts() {
		return $this->cantidadDePosts > 0;
	}
	
	private function pasarStringAInt($cantidadStr) {
		$offset = strlen($cantidadStr) - 1;
		$valor = 0;
		$pos = 0;
		while ( $offset >= 0) {
			$valor += ($cantidadStr[$pos] - '0') * pow(10 ,$offset);
			$offset--;
			$pos++;
			
		}
		return $valor;
	}
	
	private function actualizarShared($post,$fb_post) {
		if (isset($fb_post->shares)) {
		if ($fb_post->shares->count != $post->getCantidadShared()) {
			$post->setActualizarShared(true);
			$post->setCantidadShared($fb_post->shares->count);			
		}
		}
		else {
			$post->setActualizarShared(true);
		}
	}
	
	private function pedirComments($post, $after) {
		return $this->performRequest($post->getFacebookId(), 'comments', array('after' => $after, 'order' => 'chronological', 'filter' => 'stream', 'limit' => $this->limitComments));
	}

	private function pedirPost($since,$until) {
		return $this->performRequest($this->getFanPage()->getFbId(), 'posts', array('since' => $since, 'until' => $until  ));
	}
	
}

?>