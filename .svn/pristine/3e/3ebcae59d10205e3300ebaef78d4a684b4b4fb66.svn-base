<?php

namespace CM\Bundle\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MessageCache
 *
 * @ORM\Table(name="message_cache")
 * @ORM\Entity(repositoryClass="CM\Bundle\ModelBundle\Entity\MessageCacheRepository")
 */
class MessageCache
{
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
     * @ORM\Column(name="conversation_id", type="string", length=255)
     */
    private $conversation_id;

    /**
     * @var text
     * 
     * @ORM\Column(name="messages", type="text", nullable=true)
     */
    private $messages;

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
     * Set messages
     *
     * @param string $messages
     * @return Conversation
     */
    public function setMessages($messages)
    {
        $this->messages = json_encode($messages);

        return $this;
    }

    /**
     * Get messages
     *
     * @return string 
     */
    public function getMessages()
    {
        return json_decode($this->messages);
    }

    /**
     * Set conversation_id
     *
     * @param string $conversationId
     * @return Conversation
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
}
