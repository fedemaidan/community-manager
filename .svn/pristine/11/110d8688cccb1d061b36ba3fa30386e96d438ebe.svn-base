<?php

namespace CM\Bundle\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FanPage
 *
 * @ORM\Table(name="fan_page")
 * @ORM\Entity(repositoryClass="CM\Bundle\ModelBundle\Entity\FanPageRepository")
 */
class FanPage
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
     * @ORM\Column(name="fb_id", type="string", length=255, unique=true)
     */
    private $fb_id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, unique=true)
     */
    private $name;

    /**
     * The Facebook fan page url
     * 
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="app_id", type="string", length=255)
     */
    private $app_id;

    /**
     * @var string
     *
     * @ORM\Column(name="app_secret", type="string", length=255)
     */
    private $app_secret;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token", type="string", length=255)
     */
    private $access_token;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token_actualizado", type="boolean")
     */
    private $accesTokenActualizado;
    
    
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
     * Set name
     *
     * @param string $name
     * @return FanPage
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set access_token
     *
     * @param string $accessToken
     * @return FanPage
     */
    public function setAccessToken($accessToken)
    {
        $this->access_token = $accessToken;

        return $this;
    }

    /**
     * Get access_token
     *
     * @return string 
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return FanPage
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set fb_id
     *
     * @param string $fbId
     * @return FanPage
     */
    public function setFbId($fbId)
    {
        $this->fb_id = $fbId;

        return $this;
    }

    /**
     * Get fb_id
     *
     * @return string 
     */
    public function getFbId()
    {
        return $this->fb_id;
    }

    /**
     * Set app_id
     *
     * @param string $appId
     * @return FanPage
     */
    public function setAppId($appId)
    {
        $this->app_id = $appId;

        return $this;
    }

    /**
     * Get app_id
     *
     * @return string 
     */
    public function getAppId()
    {
        return $this->app_id;
    }

    /**
     * Set app_secret
     *
     * @param string $appSecret
     * @return FanPage
     */
    public function setAppSecret($appSecret)
    {
        $this->app_secret = $appSecret;

        return $this;
    }

    /**
     * Get app_secret
     *
     * @return string 
     */
    public function getAppSecret()
    {
        return $this->app_secret;
    }
    


    /**
     * Set accesTokenActualizado
     *
     * @param boolean $accesTokenActualizado
     * @return FanPage
     */
    public function setAccesTokenActualizado($accesTokenActualizado)
    {
        $this->accesTokenActualizado = $accesTokenActualizado;

        return $this;
    }

    /**
     * Get accesTokenActualizado
     *
     * @return boolean 
     */
    public function getAccesTokenActualizado()
    {
        return $this->accesTokenActualizado;
    }
}
