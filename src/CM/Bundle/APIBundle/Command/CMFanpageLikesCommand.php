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
use CM\Bundle\ModelBundle\Entity\FanpageLike;
use \DateTime;
use CM\Bundle\APIBundle\Traits\FacebookTrait;

class CMFanpageLikesCommand extends ContainerAwareCommand {

	use FacebookTrait; //los metodos de conexion con facebook estan en el trait
	private $limit = '0';
	private $em;
	protected $output;
		
	protected function configure() {
		parent::configure();
		$this->setName('cm-api:likesFanpage')
			->setDescription('Cantidad de likes al momento en cada fanpage');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$this->output = $output;
		$fanpages = $this->getFanpages();
				
		foreach ($fanpages as $fanpage) {
			$output->writeln(date('Y-m-d H:i:s')." - Likes de la Fan Page: ".$fanpage->getName());
			
			$fb_likes = $this->performRequest($fanpage->getFbId(), null, array( 'limit' => $this->limit, 'summary' => true), true, $fanpage);
			//$fb_likes = json_decode(file_get_contents("https://graph.facebook.com/v2.1/".$fanpage->getFbId()."?access_token=".$fanpage->getAccessToken()));
			
			$cantidad = isset($fb_likes['likes']) ? $fb_likes['likes'] : $fb_likes->likes;
			$this->saveLikes($cantidad,$fanpage);
			$this->getManager()->flush();
		}

	}
	
	private function getFanpages() {
		return $this->getManager()->getRepository('CMModelBundle:FanPage')->findAll();
	}
	
	private function saveLikes($cantidad,$fanpage) {
		$fanpageLike = $this->getManager()->getRepository('CMModelBundle:FanpageLike')->findOneBy(
																			array('fecha' => date('Y-m-d'), 'fanpage' => $fanpage));
		
		if (!$fanpageLike) {
			$fanpageLike = new FanpageLike();
			$this->getManager()->persist($fanpageLike);
		}
		
		$fanpageLike->setCantidad($cantidad);
		$fanpageLike->setFecha(date('Y-m-d'));
		$fanpageLike->setFanpage($fanpage);
		
		
	}
	
	/**
	 * This returns the entity manager
	 * @return EntityManager
	 * */
	protected function getManager() {
		if ($this->em != null) return $this->em;
	
		$this->em = $this->getContainer()->get('doctrine')->getManager();
		$this->em->getConnection()->getConfiguration()->setSQLLogger(null);//Turn off sql logger
	
		return $this->em;
	}
}
?>