<?php

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserGroup
 * @package UserBundle\Entity
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserGroupRepository")
 */
class UserGroup
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $active = false;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\User", mappedBy="group")
     */
    private $users;

    /**
     * @var string[]
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $credentials;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return UserGroup
     */
    public function setId(int $id): UserGroup
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return UserGroup
     */
    public function setLabel(string $label): UserGroup
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return UserGroup
     */
    public function setActive(bool $active): UserGroup
    {
        $this->active = $active;

        return $this;
    }


    /**
     * Add user
     *
     * @param User $user
     *
     * @return UserGroup
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Add credential
     *
     * @param string
     *
     * @return UserGroup
     */
    public function addCredential(string $credential)
    {
        $this->credentials[] = $credential;

        return $this;
    }

    /**
     * Remove credential
     *
     * @param string $credential
     */
    public function removeCredential(string $credential)
    {
        if(isset($this->credentials[$credential])){
            unset($this->credentials[$credential]);            
        }
        
        return $this;
    }

    /**
     * Set credentials
     *
     * @return UserGroup
     */
    public function setCredentials(array $credentials)
    {
        $this->credentials = $credentials;
        
        return $this;
    }    
    
    /**
     * Get credentials
     *
     * @return array
     */
    public function getCredentials()
    {
        return $this->credentials;
    }
    
    public function getNbUsers()
    {
        return $this->users->count();
    }
}
