<?php

namespace UserBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class User
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @Vich\Uploadable
 */
class User implements UserInterface
{
    protected $discr = 'user';

    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var array
     * @ORM\Column(type="array", nullable=false)
     */
    protected $roles;

    /**
     * Assert\Regex(pattern="/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/", match=true)
     *
     * , groups={"Registration", "Profile", "Resetting"}

     */

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $password;

    /**
     * @var string
     */
    protected $plainPassword;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $salt;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false, unique=false)
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false, unique=false)
     */
    protected $lastName;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastDateAskNewPassword;

    /**
     * @var string
     * @ORM\Column(name="ask_password_token", type="string", length=255, nullable=true)
     */
    protected $askPasswordToken;

    /**
     * @var UserGroup
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\UserGroup", inversedBy="users")
     */
    protected $group;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $active;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="member_avatar", fileNameProperty="avatar")
     *
     * @var File
     */
    protected $avatarFile;

    /**
     *
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $avatar;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $createAt;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $updatedAt;


    public function __construct()
    {
        $this->avatar = 'user.png';
    }

    /**
     * @param mixed $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param mixed $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @param mixed $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }



    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
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
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName()
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

    /**
     * @return \DateTime
     */
    public function getLastDateAskNewPassword()
    {
        return $this->lastDateAskNewPassword;
    }

    /**
     * @param \DateTime $lastDateAskNewPassword
     * @return User
     */
    public function setLastDateAskNewPassword(\DateTime $lastDateAskNewPassword): User
    {
        $this->lastDateAskNewPassword = $lastDateAskNewPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getAskPasswordToken()
    {
        return $this->askPasswordToken;
    }

    /**
     * @param string $askPasswordToken
     * @return User
     */
    public function setAskPasswordToken(string $askPasswordToken): User
    {
        $this->askPasswordToken = $askPasswordToken;

        return $this;
    }

    /**
     * @return UserGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param UserGroup $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    public function isDeletable()
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    public function isActive()
    {
        return $this->active;
    }

    /**
     * @return string
     */
    public function getDiscr(): string
    {
        return $this->discr;
    }

    /**
     * @param string $discr
     * @return User
     */
    public function setDiscr(string $discr): User
    {
        $this->discr = $discr;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword():? string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return User
     */
    public function setPlainPassword(string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }


    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }


    /**
     * @return File
     */
    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    /**
     * @param File $avatarFile
     * @return Member
     */
    public function setAvatarFile(?File $avatarFile = null): User
    {
        $this->avatarFile = $avatarFile;

        if($avatarFile){
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * @param \DateTime $createAt
     * @return Member
     */
    public function setCreateAt($createAt) : User
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return Member
     */
    public function setUpdatedAt(\DateTime $updatedAt): User
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
