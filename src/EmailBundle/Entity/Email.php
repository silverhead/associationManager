<?php

namespace EmailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Email
 * @package EmailBundle\Entity
 * @ORM\Entity(repositoryClass="EmailBundle\Repository\EmailRepository")
 */
class Email
{
    public const STATUS_UNKNOWN = 0;
    public const STATUS_DRAFT = 1;
    public const STATUS_NOT_SEND = 2;
    public const STATUS_SENT = 3;
    public const STATUS_AUTOMATIC = 4;

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
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $updatedDate;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $automatic;

    public function __construct()
    {
        $this->updatedDate = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Email
     */
    public function setLabel(string $label): Email
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return Email
     */
    public function setSubject(string $subject): Email
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return Email
     */
    public function setBody(string $body): Email
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return Email
     */
    public function setStatus(int $status): Email
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedDate(): \DateTime
    {
        return $this->updatedDate;
    }

    /**
     * @param \DateTime $updatedDate
     * @return Email
     */
    public function setUpdatedDate(\DateTime $updatedDate): Email
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAutomatic(): bool
    {
        return $this->automatic;
    }

    /**
     * @param bool $automatic
     * @return Email
     */
    public function setAutomatic(bool $automatic): Email
    {
        $this->automatic = $automatic;

        return $this;
    }

    /**
     * Get automatic.
     *
     * @return bool
     */
    public function getAutomatic()
    {
        return $this->automatic;
    }
}
