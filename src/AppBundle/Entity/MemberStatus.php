<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class MemberStatus
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MemberStatusRepository")
 */
class MemberStatus
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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MemberStatusHistorical", mappedBy="status")
     */
    private $members;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return MemberStatus
     */
    public function setId(int $id): MemberStatus
    {
        $this->id = $id;

        return $this;
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
     * @return MemberStatus
     */
    public function setLabel(string $label): MemberStatus
    {
        $this->label = $label;

        return $this;
    }
}