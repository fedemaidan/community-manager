<?php

namespace CM\Bundle\APIBundle\Command;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use CM\Bundle\ModelBundle\Entity\FacebookAPIState;
use CM\Bundle\ModelBundle\Entity\Conversation;
use CM\Bundle\ModelBundle\Entity\MessageCache;



class CMConversationMessageCommand extends CMAbstractCommand{

	private $fanpage;
	
	protected function configure()
	{
		parent::configure();
		$this
		->setName('cm-api:messages')
		->setDescription('It retrieves messages for a conversation')
		->addArgument('conversationId', InputArgument::REQUIRED, "The Facebook Fan Page FB ID")
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		
		$id = $input->getArgument('conversationId');
		$conversation =	$this->getManager()->getRepository('CMModelBundle:Conversation')->findOneBy(array('id' => $id));
		$this->fanpage = $conversation->getFanPage();
		
		$output->writeln(date('Y-m-d H:i:s')." - Actualizar conversation " . $input->getArgument('conversationId'));
		
		$this->processMessages($conversation);

		$output->writeln(date('Y-m-d H:i:s')." - FIN.");

	}
	
	

	private function processMessages($conversation) {
		//Check the last API state
		$fb_api_state = $this->getFacebookAPIState(FacebookAPIState::MESSAGES_NODE, $conversation->getConversationId());
		$since = $newest_since = $until = $__paging_token = '';
		if($fb_api_state) {
			$since = $fb_api_state->getSince();
			$newest_since = $fb_api_state->getNewestSince();
			$until = $fb_api_state->getUntil();
			$__paging_token = $fb_api_state->getPagingToken();
		}
	
	
		// Iterate the fb conversation's messages
		while($conversation_messages = $this->performRequest($conversation->getConversationId(), 'messages', array('since' => $since, 'until' => $until, '__paging_token' => $__paging_token), true, $this->fanpage)) {
			 
			$stored_messages = $conversation->getMessages() ? $conversation->getMessages() : array();
			if($since != '') {
				$this->saveMessageCache($conversation, $conversation_messages['data']);
				//$conversation->setMessages(array_merge($conversation_messages['data'], $stored_messages));
			} else {
				if (isset($conversation_messages['data']))
					$conversation->setMessages(array_merge($stored_messages,$conversation_messages['data']));
			}
		
			$conversation->setSnippet($conversation_messages['data'][0]->message);
			if($newest_since == '') {
				$parsed_url = parse_url($conversation_messages['paging']->previous);
				parse_str($parsed_url['query'], $url_params);
				$newest_since = $url_params['since'];
				//		$__paging_token = isset($url_params['__paging_token']) ? $url_params['__paging_token'] : '';
			}
			$parsed_url = parse_url($conversation_messages['paging']->next);
			parse_str($parsed_url['query'], $url_params);
			$until = $url_params['until'];
			$__paging_token = isset($url_params['__paging_token']) ? $url_params['__paging_token'] : '';
	
			$this->updateFacebookAPIState(FacebookAPIState::MESSAGES_NODE, $conversation->getConversationId(), $since, $newest_since, $until,$__paging_token);
			$this->getManager()->flush();
	
		}
		// Reset for next run
		$since = $newest_since;
		$newest_since = '';
		$until = '';
		$__paging_token = '';
		$stored_messages = $conversation->getMessages() ? $conversation->getMessages() : array();
		$messageCache = $this->getMessageCache($conversation);
		$stored_messages_cache = $messageCache->getMessages() ? $messageCache->getMessages() : array();
		$conversation->setMessages(array_merge($stored_messages_cache, $stored_messages));
		$this->getManager()->remove($messageCache);
		$this->updateFacebookAPIState(FacebookAPIState::MESSAGES_NODE, $conversation->getConversationId(), $since, $newest_since, $until,$__paging_token);
		$this->getManager()->flush();
		// --------------------
	
	}
	
	
	private function saveMessageCache($conversation, $messages) {
	
		$messageCache = $this->getMessageCache($conversation);
	
		$stored_messages = $messageCache->getMessages() ? $messageCache->getMessages() : array();
		$messageCache->setMessages(array_merge($messages, $stored_messages));
		$messageCache->setConversationId($conversation->getId());
		if($messageCache->getId() == null) {
			$this->getManager()->persist($messageCache);
		}
		$this->getManager()->flush();
	}
	
	private function updateFacebookAPIState($node_type, $conversation_id, $since, $newest_since, $until, $__paging_token) {
		//         if ($this->dry) {
		//             return false;
		//         }
		$facebookAPIState = $this->getManager()->getRepository('CMModelBundle:FacebookAPIState')->findOneBy(array('page' => $this->fanpage->getName(), 'node_type' => $node_type, 'conversation_id' => $conversation_id));
	
		if (!$facebookAPIState) {
			$facebookAPIState = new FacebookAPIState();
		}
	
		$facebookAPIState->setPage($this->fanpage->getName());
		$facebookAPIState->setNodeType($node_type);
		$facebookAPIState->setConversationId($conversation_id);
		$facebookAPIState->setSince($since);
		$facebookAPIState->setNewestSince($newest_since);
		$facebookAPIState->setUntil($until);
		$facebookAPIState->setPagingToken($__paging_token);
		if($facebookAPIState->getId() == null) {
			$this->getManager()->persist($facebookAPIState);
		}
		$this->getManager()->flush();
	}
	
	
	private function getFacebookAPIState($node_type, $conversation_id = null) {
	
		$facebookAPIState = $this->getManager()->getRepository('CMModelBundle:FacebookAPIState')->findOneBy(array('page' => $this->fanpage->getName(), 'node_type' => $node_type, 'conversation_id' => $conversation_id));
		/* @var $twitterAPIState TwitterAPIState */
		if (!$facebookAPIState) {
			return false;
		}
		return $facebookAPIState;
	}
	
	private function getMessageCache($conversation) {
		$messageCache = $this->getManager()->getRepository('CMModelBundle:MessageCache')->findOneBy(array('conversation_id' => $conversation->getId()));
	
		if(!$messageCache) {
			$messageCache = new MessageCache();
		}
	
		return $messageCache;
	}
	
	
}