<?php

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class MemberGroup
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

    public function __construct()
    {
        $this->users = new ArrayCollection();
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
}
