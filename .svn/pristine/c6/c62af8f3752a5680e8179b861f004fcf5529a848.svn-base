<?php

namespace CM\Bundle\APIBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use CM\Bundle\ModelBundle\Entity\FacebookAPIState;
use Facebook;
use CM\Bundle\ModelBundle\Entity\Post;
use \DateTime;

class CMPostLikesCommand extends CMAbstractCommand {
	
	private $limit = '1000';
	
	protected function configure() {
		parent::configure();
		$this->setName('cm-api:likes')
		->setDescription('Actualiza los likes de los post')
		->addArgument('page',InputArgument::REQUIRED, "The Facebook Fan Page FB ID")
		->addArgument('amountPosts',InputArgument::OPTIONAL, "Number of posts");
	}
	
	protected function execute(InputInterface $input, OutputInterface $output) {
		parent::execute($input,$output);
		
		//$this->setCantidadDePosts($input->getArguments('amountPosts')['amountPosts']);
		
		$output->writeln(date('Y-m-d H:i:s')." - Likes de los post de la Fan Page: ".$this->getFanPage()->getName());
		$this->processPosts();
		$output->writeln(date('Y-m-d H:i:s'). " - Fin");
		
	}
	
	private function processPosts() {		
		$posts = $this->getManager()->getRepository('CMModelBundle:Post')->findByFanPage($this->getFanPage()); 
		
		foreach ($posts as $post ) {
			$this->output->writeln(date('Y-m-d H:i:s')." - Voy a procesar los likes del post:" . $post->getId());
			$this->cargoLosLikesDelPost($post);
			$this->output->writeln(date('Y-m-d H:i:s')." - Termine de procesar los likes del post:" . $post->getId());
			
			$this->getManager()->flush();
			$this->output->writeln(date('Y-m-d H:i:s')." - Guarde los likes del post:" . $post->getId());
		}

		
		
	}	
	
	
	private function cargoLosLikesDelPost($post) {
		$primero = true;
		$after = '';
		
		$fb_likes = $this->performRequest($post->getFacebookId(), 'likes', array('after' => $after, 'limit' => $this->limit, 'summary' => true));
		$entra = isset($fb_likes['data'][0]);
		$cantidadLikes = $fb_likes['summary']->total_count;
		
		while($entra) {	
			
			//si no hay nuevos likes sale
			if ($primero) {
				if($cantidadLikes == $post->getCantidadLikes()) 
					return;
				$this->output->writeln(date('Y-m-d H:i:s')." - Hay que actualizar likes");
			}
			
			//si hay carga los likes
			foreach ($fb_likes['data'] as $like) {
				if ($primero) {
					$ids =  $like->id;
					$primero = false;
				}
				else $ids .= ",".$like->id;
	
			}
			
			
			//va a la siguiente pagina
			if (isset($fb_likes['paging']->cursors->after))
				$after = $fb_likes['paging']->cursors->after;
			else 
				$after = '';
			
			
			
			$fb_likes = 	$fb_likes = $this->performRequest($post->getFacebookId(), 'likes', array('after' => $after, 'limit' => $this->limit, 'summary' => false));		
			$entra = isset($fb_likes['data'][0]); 
		}
		
		//guardo datos
		if (isset($ids))
			$this->saveLikes($post,$ids,$cantidadLikes);
	}
	
	private function saveLikes($post,$ids,$cantidad) {
		$this->output->writeln(date('Y-m-d H:i:s')." - Inserto likes");
		$post->setLikes($ids);
 		//$post->setActualizarLikes(false);
 		$post->setCantidadLikes($cantidad);
 		
	}
		
}

?>