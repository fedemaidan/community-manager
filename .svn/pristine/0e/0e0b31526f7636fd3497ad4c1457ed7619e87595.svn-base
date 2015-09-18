<?php

/*
 * @author Matias Blanco
*
* Abstract class for the Twitter commands
*/

namespace CM\Bundle\APIBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use CM\Bundle\APIBundle\Command\CMConversationCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Command.
 *
 * @author Federico Maidan
 */
class CMPostExecutorCommand extends ContainerAwareCommand
{

	private $em;

	protected function configure()
	{
		parent::configure();
		$this
			->setName('cm-api:post:execute')
			->setDescription('It executes the post command for all the fan pages in the DB')
			->addArgument('amountPosts',InputArgument::OPTIONAL, "Number of posts");

	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$absolute_path = realpath(__DIR__.'/../../../../..');
		$fanPageFBIds = $this->getFBIds();
		$amountPosts = $input->getArguments('amountPosts')['amountPosts'];
		
		foreach ($fanPageFBIds as $fanPageFBId) {
			exec('run-one nohup php '.$absolute_path.'/app/console cm-api:pst '.$fanPageFBId.' '. $amountPosts . ' >> '.$absolute_path.'/app/logs/pst'.$amountPosts.'-'.$fanPageFBId.' &');
			exec('run-one nohup php '.$absolute_path.'/app/console cm-api:likes '.$fanPageFBId.' '. $amountPosts . ' >> '.$absolute_path.'/app/logs/likesPost'.$amountPosts.'-'.$fanPageFBId.' &');
			exec('run-one nohup php '.$absolute_path.'/app/console cm-api:shared '.$fanPageFBId.' '. $amountPosts . ' >> '.$absolute_path.'/app/logs/sharedPost'.$amountPosts.'-'.$fanPageFBId.' &');
		}
	}

	/**
	 * This returns the entity manager
	 * @return EntityManager
	 * */
	private function getManager() {
		if ($this->em != null) return $this->em;

		$this->em = $this->getContainer()->get('doctrine')->getManager();
		$this->em->getConnection()->getConfiguration()->setSQLLogger(null);//Turn off sql logger

		return $this->em;
	}

	private function getFBIds() {
		return $this->getManager()->getRepository('CMModelBundle:FanPage')->getFBIds();
	}

}
?>
