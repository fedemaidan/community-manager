<?php

namespace CM\Bundle\ModelBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

// DON'T forget this use statement!!!
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="CM\Bundle\ModelBundle\Entity\UserRepository")
 */
class User implements UserInterface, EquatableInterface, \Serializable
{
    private $salt;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 
     * @var string
     * 
     * @ORM\Column(type="string", length=100, nullable=false, unique=true)
     * @Assert\Email()
     * @Assert\NotNull(message="El campo email es obligatorio.")
     */
    private $email;

    /**
     * 
     * @var string
     * 
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotNull(message="El campo password es obligatorio.")
     */
    private $password;

    /**
     *
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(
     *      min = "0",
     *      max = "100",
     *      minMessage = "El nombre debe tener como mínimo {{ limit }} caracteres.",
     *      maxMessage = "El nombre debe tener como máximo {{ limit }} caracteres."
     * )
     */
    private $nombre;

    /**
     *
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(
     *      min = "0",
     *      max = "100",
     *      minMessage = "El apellido debe tener como mínimo {{ limit }} caracteres.",
     *      maxMessage = "El apellido debe tener como máximo {{ limit }} caracteres."
     * )
     */
    private $apellido;


    /** 
     * 
     * @var ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @ORM\JoinTable(name="user_role")
     */
    private $roles;

    public function __construct() {
        $this->roles = new ArrayCollection();
        $this->salt = md5(uniqid(null, true));
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
     * Set email
     *
     * @param string $email
     * @return GCBAUsuario
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    public function setPassword($password){
        $this->password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

        return $this;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return GCBAUsuario
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     * @return GCBAUsuario
     */
    public function setApellido($apellido) {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido() {
        return $this->apellido;
    }

    /**
     * Add Roles
     *
     * @param Role $roles
     * @return Usuario
     */
    public function addRole(Role $roles) {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param Role $roles
     */
    public function removeRole(Role $roles) {
        $this->roles->removeElement($roles);
    }
    
	public function removeAllRoles() {
		
		foreach ($this->getRoles() as $rol) {
			$this->removeRole($rol);
		}
	}    

    /*********** USER INTERFACE METHODS ******************/

    public function getRoles() {
        return $this->roles->toArray();
    }

    public function getPassword() {
        return $this->password;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getUsername() {
        return $this->getEmail();
    }

    public function eraseCredentials() {

    }

    //EquatableInterface
    public function isEqualTo(UserInterface $user) {
        
        if ($user instanceof Usuario) {
            
            if($user->getEmail() !== $this->getEmail()){
                return false;
            }
            
            return true;
        }
        return false;
    }

    //Serializable
    /**
    * Serializes the content of the current User object
    * @return string
    */
    public function serialize()
    {
        return \json_encode(array($this->email, $this->password, $this->id, $this->nombre, $this->apellido));
    }

    /**
    * Unserializes the given string in the current User object
    * @param serialized
    */
    public function unserialize($serialized)
    {
        list($this->email, $this->password, $this->id, $this->nombre, $this->apellido) = \json_decode($serialized);
    }
}
