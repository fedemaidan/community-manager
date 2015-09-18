<?php

namespace CM\Bundle\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="CM\Bundle\ModelBundle\Entity\RoleRepository")
 */
class Role implements RoleInterface, \Serializable
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
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(name="role", type="string", length=20, unique=true)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     */
    private $users;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * @see RoleInterface
     */
    public function getRole()
    {
        return $this->role;
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
     * Set name
     *
     * @param string $name
     * @return GCBARole
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
     * Add usuarios
     *
     * @param User $usuarios
     * @return Role
     */
    public function addUsers(User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove usuarios
     *
     * @param User $usuarios
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    //Serializable
    /**
    * Serializes the content of the current User object
    * @return string
    */
    public function serialize()
    {
        return \json_encode(array($this->name, $this->role, $this->id));
    }

    /**
    * Unserializes the given string in the current User object
    * @param serialized
    */
    public function unserialize($serialized)
    {
        list($this->name, $this->role, $this->id) = \json_decode($serialized);
    }

    public function __toString() {
        return $this->getName();
    }

    /**
     * Add users
     *
     * @param \CM\Bundle\ModelBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\CM\Bundle\ModelBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }
}
