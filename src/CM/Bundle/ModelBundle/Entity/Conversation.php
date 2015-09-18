<?php

namespace CM\Bundle\ModelBundle\Entity;

use FS\SolrBundle\Doctrine\Annotation as Solr;
use CM\Bundle\ModelBundle\Doctrine\Annotation as SolrCustom;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\Mapping\RuntimeReflectionService;

/**
 * Conversation
 *
 * @Solr\Document
 * @ORM\Table(name="conversation")
 * @ORM\Entity(repositoryClass="CM\Bundle\ModelBundle\Entity\ConversationRepository")
 */
class Conversation implements CalificableInterface, TaggeableInterface
{
    /**
     * @var integer
     *
     * @Solr\Id
     * @Solr\Field(type="integer")
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @SolrCustom\FieldObject(type="integer")
     * @ORM\ManyToOne(targetEntity="FanPage")
     * @ORM\JoinColumn(name="fan_page_id", referencedColumnName="id")
     */
    private $fan_page;

    /**
     * @SolrCustom\FieldObjectArrayCollection(type="text")
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="conversation_tag",
     *      joinColumns={@ORM\JoinColumn(name="conversation_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    private $tags;

    /**
     * @var string
     * 
     * @Solr\Field(type="string")
     * @ORM\Column(name="conversation_id", type="string", length=255)
     */
    private $conversation_id;

    /**
     * The updated time of the conversation
     * @var string
     *
     * @Solr\Field(type="string")
     * @ORM\Column(name="updated_time", type="string", length=25)
     */
    private $updated_time;

    /**
     * The snippet of the most recent message of the conversation
     * 
     * @var string
     *
     * @ORM\Column(name="snippet", type="string", length=255)
     */
    private $snippet;

    /**
     * Comma separated FB conversation participants
     * 
     * @var string
     * 
     * @ORM\Column(name="participants", type="string", length=255)
     */
    private $participants;

    /**
     * [$calificacion description]
     * @var int
     *
     * @Solr\Field(type="integer")
     * @ORM\Column(name="calificacion", type="integer")
     */
    private $calificacion = 0;

    /**
     * @var text
     *
     * @SolrCustom\FieldPattern(type="text", pattern="_message_:_(.*?)_")
     * @ORM\Column(name="messages", type="text", nullable=true)
     */
    private $messages;

    public function __construct() {
        $this->tags = new ArrayCollection();
    }

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
     * Set updated_time
     *
     * @param string $updatedTime
     * @return Conversation
     */
    public function setUpdatedTime($updatedTime)
    {
        $this->updated_time = $updatedTime;

        return $this;
    }

    /**
     * Get updated_time
     *
     * @return string
     */
    public function getUpdatedTime()
    {
        return $this->updated_time;
    }

    /**
     * Set snippet
     *
     * @param string $snippet
     * @return Conversation
     */
    public function setSnippet($snippet)
    {
        $this->snippet = $snippet;

        return $this;
    }

    /**
     * Get snippet
     *
     * @return string 
     */
    public function getSnippet()
    {
        return $this->snippet;
    }

    /**
     * Set participants
     *
     * @param string $participants
     * @return Conversation
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;

        return $this;
    }

    /**
     * Get participants
     *
     * @return string 
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * Set messages
     *
     * @param string $messages
     * @return Conversation
     */
    public function setMessages($messages)
    {
        $this->messages = json_encode($messages, JSON_UNESCAPED_UNICODE);

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
     * Set fan_page
     *
     * @param \CM\Bundle\ModelBundle\Entity\FanPage $fanPage
     * @return Conversation
     */
    public function setFanPage(\CM\Bundle\ModelBundle\Entity\FanPage $fanPage = null)
    {
        $this->fan_page = $fanPage;

        return $this;
    }

    /**
     * Get fan_page
     *
     * @return \CM\Bundle\ModelBundle\Entity\FanPage 
     */
    public function getFanPage()
    {
        return $this->fan_page;
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

    /**
     * Set calificacion
     *
     * @param integer $calificacion
     * @return Conversation
     */
    public function setCalificacion($calificacion)
    {
        $this->calificacion = $calificacion;

        return $this;
    }

    /**
     * Get calificacion
     *
     * @return integer
     */
    public function getCalificacion()
    {
        return $this->calificacion;
    }

    /**
     * Add tags
     *
     * @param \CM\Bundle\ModelBundle\Entity\Tag $tags
     * @return Conversation
     */
    public function addTag(\CM\Bundle\ModelBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \CM\Bundle\ModelBundle\Entity\Tag $tags
     */
    public function removeTag(\CM\Bundle\ModelBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set calificacion
     *
     * @param integer $calificacion
     * @return Conversation
     */
    public function qualify($calificacion) {
        return $this->setCalificacion($calificacion);
    }

    /**
     * Add/Remove tag
     *
     * @param CM\Bundle\ModelBundle\Tag $tag
     * @param boolean $remove
     * @return Conversation
     */
    public function tag(\CM\Bundle\ModelBundle\Entity\Tag $tag, $remove = false) {
        if($remove) {
            $this->removeTag($tag);
        } else {
            $this->addTag($tag);
        }
    }

}
