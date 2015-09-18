<?php

namespace CM\Bundle\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FanpageLikes
 * 
 * @ORM\Table(name="fanpage_like")
 * @ORM\Entity(repositoryClass="CM\Bundle\ModelBundle\Entity\FanpageLikeRepository")
 * 
 */


class FanpageLike {

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
	 * @ORM\Column(name="fecha",type="string")
	 * 
	 */
	
	private $fecha;

	/**
	 * @var integer
     * @ORM\ManyToOne(targetEntity="Fanpage")
	 */
	
	private $fanpage;
	
	/** 
	 * @var string
	 * @ORM\Column(name="cantidad",type="string")
	 */
	
	private $cantidad;

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
     * Set fecha
     *
     * @param string $fecha
     * @return FanpageLike
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return string 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set cantidad
     *
     * @param string $cantidad
     * @return FanpageLike
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return string 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set fanpage
     *
     * @param \CM\Bundle\ModelBundle\Entity\Fanpage $fanpage
     * @return FanpageLike
     */
    public function setFanpage(\CM\Bundle\ModelBundle\Entity\Fanpage $fanpage = null)
    {
        $this->fanpage = $fanpage;

        return $this;
    }

    /**
     * Get fanpage
     *
     * @return \CM\Bundle\ModelBundle\Entity\Fanpage 
     */
    public function getFanpage()
    {
        return $this->fanpage;
    }
}
