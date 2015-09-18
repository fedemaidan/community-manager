<?php

namespace CM\Bundle\APIBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CMCleanLockedCommentCommand extends ContainerAwareCommand {
	
	private $em;
	protected $output;
	
	protected function configure() {
		parent::configure();
		$this->setName('cm-api:unlockComments')
		->setDescription('Limpia la fecha de bloqueo de los comentarios')
		->addArgument('horas',InputArgument::REQUIRED, "Desbloquea comentarios que esten bloqueados hace una determinada cantidad de horas");
	}
	
	protected function execute(InputInterface $input, OutputInterface $output) {
		$this->output = $output;
		$horas = $input->getArgument('horas');
		$aux = '-'.$horas.' hours';
		$tiempoDesbloqueo = date('Y-m-d H:i:s', strtotime($aux));
		$output->writeln($tiempoDesbloqueo);
		$comentarios = $this->getManager()->getRepository('CMModelBundle:Comment')->getCommentByLockedTime($tiempoDesbloqueo);
		
		foreach ($comentarios as $comentario) {
			$comentario->setFechaDeBloqueo(null);	
		}
		
		$this->getManager()->flush();
		
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