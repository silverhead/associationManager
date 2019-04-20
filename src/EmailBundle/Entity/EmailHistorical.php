<?php


namespace EmailBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;
use MemberBundle\Entity\Member;
use UserBundle\Entity\User;

/**
 * Class EmailHistorical
 * @package EmailBundle\Entity
 * @ORM\Entity()
 */
class EmailHistorical
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="auto")
     */
    private $id;

    /**
     * @var Email
     * @ORM\Column(type="integer")
     */
    private $email;

    /**
     * @var User
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sender;

    /**
     * @var Member
     * @ORM\Column(type="integer")
     */
    private $receiver;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $emailReceiver;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $sendingDate;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return EmailHistorical
     */
    public function setId(int $id): EmailHistorical
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @param Email $email
     * @return EmailHistorical
     */
    public function setEmail(Email $email): EmailHistorical
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return User
     */
    public function getSender(): User
    {
        return $this->sender;
    }

    /**
     * @param User $sender
     * @return EmailHistorical
     */
    public function setSender(User $sender): EmailHistorical
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return Member
     */
    public function getReceiver(): Member
    {
        return $this->receiver;
    }

    /**
     * @param Member $receiver
     * @return EmailHistorical
     */
    public function setReceiver(Member $receiver): EmailHistorical
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailReceiver(): string
    {
        return $this->emailReceiver;
    }

    /**
     * @param string $emailReceiver
     * @return EmailHistorical
     */
    public function setEmailReceiver(string $emailReceiver): EmailHistorical
    {
        $this->emailReceiver = $emailReceiver;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSendingDate(): \DateTime
    {
        return $this->sendingDate;
    }

    /**
     * @param \DateTime $sendingDate
     * @return EmailHistorical
     */
    public function setSendingDate(\DateTime $sendingDate): EmailHistorical
    {
        $this->sendingDate = $sendingDate;

        return $this;
    }
}