<?php

namespace CM\Bundle\FrontendBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PrivateMessageController extends Controller
{
	/**
     * @Route("/private_message", name="private_message")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE')")
     * @Template()
     */
    public function indexAction(Request $request)
    {
		$filter = $request->getSession()->get('filter');
		if ($filter->getFanPageId() && $filter->getFanPageId() != 0) {
    		$fanPage = $this->container->get('cm_service')->getFanPageById($filter->getFanPageId());
// 			$request->getSession()->set("filter", $filter);
		}
		else {         
			$fanPages = $this->container->get('cm_service')->getFanPages();
			$fanPage = $fanPages[0];
			$filter->setFanPageId($fanPage->getId());
			$filter->setFanPageName($fanPage->getName());
			$request->getSession()->set("filter", $filter);
		}
        return array(
            'fanPage' => $fanPage,
            );
    }

    /**
     * @Route("/PM/conversations", name="private_message_conversations")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE')")
     * @Template()
     */
    public function conversationsAction(Request $request) {
        $conversation_id = $request->get('conversation_id', null);

        if($conversation_id == null) {
            $filter = $request->getSession()->get("filter")->toArray();
            $start = $request->get('start', 0);
            $limit = $this->container->getParameter('limit_default');
            $conversations = $this->container->get('cm_service')->getConversationsByFanPage($filter, $start, $limit);
        } else {
            $conversations = array($this->container->get('cm_service')->getConversationById($conversation_id));
        }
        
        return array(
            'conversations' => $conversations,
        );
    }

    /**
     * @Route("/PM/messages", name="private_message_messages")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE')")
     * @Template()
     */
    public function messagesAction(Request $request) {
        $conversation_id = $request->get('conversation_id', null);

        if($conversation_id == null) {
            $filter = $request->getSession()->get("filter")->toArray();
            $start = $request->get('start', 0);
            $limit = $this->container->getParameter('limit_default');
            $conversations = $this->container->get('cm_service')->getConversationsByFanPage($filter, $start, $limit);
        } else {
            $conversations = array($this->container->get('cm_service')->getConversationById($conversation_id));
        }
        
        $conversation_messages = array();
        foreach ($conversations as $conversation) {
	    $messages = $conversation->getMessages();
	    usort($messages, function($a, $b) { //Sort the array using a user defined function
                return $a->created_time < $b->created_time ? -1 : 1; //Compare the scores
            });
            $conversation_messages[$conversation->getId()] = $messages;
        }
        return array(
            'conversation_messages' => $conversation_messages,
        );
    }

    /**
     * @Route("/PM/qualify", name="private_message_calificar")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE')")
     */
    public function qualifyAction(Request $request) {
        if($request->isXmlHttpRequest()) {
            $conversation = $this->get('cm_service')->getConversationById($request->get('conversation_id'));
            if ($conversation->getCalificacion() == $request->get('calificacion')) {
            	$result = $this->container->get('cm_service')->qualify($conversation, "0");
            }
            else {
            	$result = $this->container->get('cm_service')->qualify($conversation, $request->get('calificacion'));
            }
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
        } else {
            $response = new Response();
        }
        return $response;
    }

    /**
     * @Route("/PM/sendMessage", name="private_message_send_message")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE')")
     */
    public function sendMessageAction(Request $request) {
        if($request->isXmlHttpRequest()) {
            $result = $this->get('cm_service')->sendPMMessage($request->get('conversation_id'), $request->get('message'));
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
        } else {
            $response = new Response();
        }
        return $response;
    }

    /**
     * @Route("/PM/retrieveTags", name="private_message_retrieve_tags")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE')")
     */
    public function retrieveTagsAction(Request $request) {
        if($request->isXmlHttpRequest()) {
            $result = $this->get('cm_service')->getTagsByName(trim($request->get('term')));
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
        } else {
            $response = new Response();
        }
        return $response;
    }

    /**
     * @Route("/PM/getConversationTags", name="private_message_conversation_tags")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE')")
     */
    public function getConversationTagsAction(Request $request) {
        if($request->isXmlHttpRequest()) {
            $result = $this->get('cm_service')->getTagsByConversation($request->get('conversation_id'));
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
        } else {
            $response = new Response();
        }
        return $response;
    }

    /**
     * @Route("/PM/updateTags", name="private_message_conversation_tags_update")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE')")
     */
    public function updateTags(Request $request) {
        if($request->isXmlHttpRequest()) {
            $conversation = $this->get('cm_service')->getConversationById($request->get('conversation_id'));
            $tag_name = $request->get('tag_name');
            $remove = $request->get('remove', false);
            $result = $this->get('cm_service')->updateTags($conversation, $tag_name, $remove);
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
        } else {
            $response = new Response();
        }
        return $response;
    }
    
    /**
     * @Route("/PM/cargarFiltrosEnSesion", name="cargarFiltrosEnSesionPM")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ADMIN_PAGE')")
     * 
     */
    
    public function cargarFiltrosEnSesionAction(Request $request)
    {
    	$filter = $request->getSession()->get('filter');
    	$filter->setFanPageId($request->get('fanpageId', null));
    	$filter->setText($request->get('texto', null));
    	$filter->setTags($request->get('tags', null));
    	$filter->setQualification($request->get('calificacion', null));
    
    	if(!$filter->getFanPageId()) {
    		$fanPages = $request->getSession()->get('fan_pages');
    		if(!empty($fanPages)) {
    			$filter->setFanPageId($fanPages[0]->getId());
    		}
    	}
    
    	$fanPage = $this->container->get('cm_service')->getFanPageById($filter->getFanPageId());
    	$filter->setFanPageName($fanPage->getName());
    
    	$request->getSession()->set("filter", $filter);
    	return new Response(json_encode($filter));
    }
    
}
