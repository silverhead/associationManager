<?php

namespace EmailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Email
 * @package EmailBundle\Entity
 * @ORM\Entity(repositoryClass="EmailBundle\Repository\EmailingRepository")
 * @ORM\Table(name="email_emailing")
 */
class Emailing extends Email
{
    public const STATUS_UNKNOWN = 0;
    public const STATUS_DRAFT = 1;
    public const STATUS_NOT_SEND = 2;
    public const STATUS_SENT = 3;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $status;

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
}