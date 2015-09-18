<?php

namespace CM\Bundle\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FacebookAPIState
 *
 * @ORM\Table(name="facebook_api_state")
 * @ORM\Entity(repositoryClass="CM\Bundle\ModelBundle\Entity\FacebookAPIStateRepository")
 */
class FacebookAPIState
{
    const CONVERSATIONS_NODE = 'conversations';
    const MESSAGES_NODE = 'messages';
    const POSTS_NODE = "posts";
    const COMMENTS_NODE = "comments";

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="page", type="string", length=100)
     */
    private $page;

    /**
     * This is the node type of the API (me, conversations, messages)
     * @var string
     *
     * @ORM\Column(name="node_type", type="string", length=100)
     */
    private $node_type;

    /**
     * This is the FB conversation ID
     * @var string
     *
     * @ORM\Column(name="conversation_id", type="string", length=255, nullable=true)
     */
    private $conversation_id;

    /**
     * This is the FB post ID
     * @var string
     *
     * @ORM\Column(name="post_id", type="string", length=255, nullable=true)
     */
    private $post_id;
    

    /**
     * @var string
     *
     * @ORM\Column(name="since", type="string", length=255, nullable=true)
     */
    private $since;

    /**
     * @var string
     *
     * @ORM\Column(name="newest_since", type="string", length=255,nullable=true)
     */
    private $newest_since;

    /**
     * @var string
     *
     * @ORM\Column(name="until", type="string", length=255,nullable=true)
     */
    private $until;

    /**
     * @var string
     *
     * @ORM\Column(name="paging_token", type="string", length=255,nullable=true)
     */
    private $paging_token;
    

    
    
    /**
     * @var string
     *
     * @ORM\Column(name="after", type="string", length=255, nullable=true)
     */
    
    
    private $after;
  	
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set page
     *
     * @param string $page
     * @return FacebookAPIState
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return string 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set node_type
     *
     * @param string $nodeType
     * @return FacebookAPIState
     */
    public function setNodeType($nodeType)
    {
        $this->node_type = $nodeType;

        return $this;
    }

    /**
     * Get node_type
     *
     * @return string 
     */
    public function getNodeType()
    {
        return $this->node_type;
    }

    /**
     * Set until
     *
     * @param string $until
     * @return FacebookAPIState
     */
    public function setUntil($until)
    {
        $this->until = $until;

        return $this;
    }

    /**
     * Get until
     *
     * @return string 
     */
    public function getUntil()
    {
        return $this->until;
    }

    /**
     * Set since
     *
     * @param string $since
     * @return FacebookAPIState
     */
    public function setSince($since)
    {
        $this->since = $since;

        return $this;
    }

    /**
     * Get since
     *
     * @return string 
     */
    public function getSince()
    {
        return $this->since;
    }

    /**
     * Set conversation_id
     *
     * @param string $conversationId
     * @return FacebookAPIState
     */
    public function setConversationId($conversationId)
    {
        $this->conversation_id = $conversationId;

        return $this;
    }

    /**
     * Get conversation_id
     *
     * @return string 
     */
    public function getConversationId()
    {
        return $this->conversation_id;
    }

    /**
     * Set post_id
     *
     * @param string $postId
     * @return FacebookAPIState
     */
    public function setPostId($postId)
    {
    	$this->post_id = $postId;
    
    	return $this;
    }
    
    /**
     * Get post_id
     *
     * @return string
     */
    public function getPostId()
    {
    	return $this->post_id;
    }
    
    /**
     * Set newest_since
     *
     * @param string $newestSince
     * @return FacebookAPIState
     */
    public function setNewestSince($newestSince)
    {
        $this->newest_since = $newestSince;

        return $this;
    }

    /**
     * Get newest_since
     *
     * @return string 
     */
    public function getNewestSince()
    {
        return $this->newest_since;
    }
    
    /**
     * Set until
     *
     * @param string $until
     * @return FacebookAPIState
     */
    public function setAfter($after)
    {
    	$this->after = $after;
    
    	return $this;
    }
    
    /**
     * Get until
     *
     * @return string
     */
    public function getAfter()
    {
    	return $this->after;
    }
    

    /**
     * Set paging_token
     *
     * @param string $pagingToken
     * @return FacebookAPIState
     */
    public function setPagingToken($pagingToken)
    {
        $this->paging_token = $pagingToken;

        return $this;
    }

    /**
     * Get paging_token
     *
     * @return string 
     */
    public function getPagingToken()
    {
        return $this->paging_token;
    }
}
