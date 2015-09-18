<?php

/*
 * @author Matias Blanco
 *
 * Abstract class for the Twitter commands
 */

namespace CM\Bundle\APIBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook;
use Facebook\FacebookRequestException;
use CM\Bundle\ModelBundle\Entity\FanPage;
use CM\Bundle\ModelBundle\Service\CMService;
use CM\Bundle\APIBundle\Traits\FacebookTrait;


/**
 * Command.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class CMAbstractCommand extends ContainerAwareCommand
{

	use FacebookTrait; //los metodos de conexion con facebook estan en el trait
	
    private $em;
    protected $output;
    private $page = null;
    protected  $fanPage;


    protected function configure() {
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->page = $input->getArgument('page');

        $this->output = $output;
    }

    /**
     * This returns the fan page being processed
     */
    protected function getFanPage() {
        if(!$this->fanPage) {
            $this->fanPage = $this->getContainer()->get('cm_service')->getFanPageByFbId($this->page);
        }
        return $this->fanPage;
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