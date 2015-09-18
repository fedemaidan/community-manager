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

class CMPostSharedCommand extends CMAbstractCommand {

	private $limit = 1000;

	protected function configure() {
		parent::configure();
		
		$this->setName('cm-api:shared')
		->setDescription('Actualiza los shared de los post')
		->addArgument('page',InputArgument::REQUIRED, "The Facebook Fan Page FB ID")
		->addArgument('amountPosts',InputArgument::OPTIONAL, "Number of posts");
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		parent::execute($input,$output);
		
		$output->writeln(date('Y-m-d H:i:s')." - Shared de los post de la Fan Page: ".$this->getFanPage()->getName());
		$this->processPosts();
		$output->writeln(date('Y-m-d H:i:s'). " - Fin");
	}
	
	private function processPosts() {
		
		$posts = $this->getManager()
				->getRepository('CMModelBundle:Post')
				->findBy(
						array('fanPage' => $this->getFanPage(), 
								'actualizarShared' => true));
	
		foreach ($posts as $post ) {
			$this->output->writeln(date('Y-m-d H:i:s')." - Voy a procesar los shared del post:" . $post->getId());
			$this->cargoLosSharedDelPost($post);
			$this->output->writeln(date('Y-m-d H:i:s')." - Termine de procesar los shared del post:" . $post->getId());
				
			$this->getManager()->flush();
			$this->output->writeln(date('Y-m-d H:i:s')." - Guarde los shared del post:" . $post->getId());
		}
	}
	
	private function cargoLosSharedDelPost($post) {
		$primero = true;
		$after = '';
		
		$fb_shareds = $this->pedirShareds($post, $after, false);
		
		while (isset($fb_shareds['data']) ) {

			foreach ($fb_shareds['data'] as $shared) {
				if ($primero) {
					$ids =  $shared->from->id;
					
					$primero = false;
				}
				else $ids .= ",".$shared->from->id;
					
				
			}
			
			//va a la siguiente pagina
			if (isset($fb_shareds['paging']->cursors->after)) {
				$after = $fb_shareds['paging']->cursors->after;
			}
			else
				$after = '';
				
			$fb_shareds = $this->pedirShareds($post, $after, false);
		}
		
		//guardo datos
		if (isset($ids))
			$this->saveShareds($post,$ids);
	}
	
	private function pedirShareds($post, $after, $summary) {
		if ($post->getObjectId() != null)
			return $this->performRequest($post->getObjectId(), 'sharedposts', array('after' => $after, 'limit' => $this->limit, 'summary' => $summary));
		else
			return $this->performRequest($post->getFacebookId(), 'sharedposts', array('after' => $after, 'limit' => $this->limit, 'summary' => $summary));
	}
	
	private function saveShareds($post,$ids ) {
		$this->output->writeln(date('Y-m-d H:i:s')." - Inserto los shared ");
		$post->setActualizarShared(false);
		$post->setShared($ids);
	}
}

?>