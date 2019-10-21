<?php

namespace EmailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Email
 * @package EmailBundle\Entity
 * @ORM\Entity(repositoryClass="EmailBundle\Repository\EmailRepository")
 * @ORM\Table(name="email_email")
 * @ORM\InheritanceType("JOINED")
 */
class Email
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
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $bodyHtml;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $bodyText;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $updatedDate;

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
    public function getBodyHtml(): string
    {
        return $this->bodyHtml;
    }

    /**
     * @param string $bodyHtml
     * @return Email
     */
    public function setBodyHtml(string $bodyHtml): Email
    {
        $this->bodyHtml = $bodyHtml;

        return $this;
    }

    /**
     * @return string
     */
    public function getBodyText(): string
    {
        return $this->bodyText;
    }

    /**
     * @param string $bodyText
     * @return Email
     */
    public function setBodyText(string $bodyText): Email
    {
        $this->bodyText = $bodyText;

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
}
