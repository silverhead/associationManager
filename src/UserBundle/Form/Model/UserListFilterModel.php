<?php

namespace UserBundle\Form\Model;

use UserBundle\Entity\UserGroup;

class UserListFilterModel
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var UserGroup
     */
    private $group;

    /**
     * @var bool
     */
    private $active;

    public function __construct()
    {
        $this->username = "";
        $this->group = new UserGroup();
        $this->active = true;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return UserListFilterModel
     */
    public function setUsername(?string $username): UserListFilterModel
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return UserListFilterModel
     */
    public function setEmail(?string $email): UserListFilterModel
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return UserGroup
     */
    public function getGroup(): ?UserGroup
    {
        return $this->group;
    }

    /**
     * @param UserGroup $group
     * @return UserListFilterModel
     */
    public function setGroup(?UserGroup $group): UserListFilterModel
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return UserListFilterModel
     */
    public function setActive(?bool $active): UserListFilterModel
    {
        $this->active = $active;

        return $this;
    }
}