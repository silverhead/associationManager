<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Periodicity
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PeriodicityRepository")
 */
class Periodicity
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $label;

    /**
     * @var integer
     * @ORM\Column(type="integer", length=3, nullable=false)
     */
    private $duration;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $active;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Periodicity
     */
    public function setId(int $id): Periodicity
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
     * @return Periodicity
     */
    public function setLabel(string $label): Periodicity
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     * @return Periodicity
     */
    public function setDuration(int $duration): Periodicity
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return Periodicity
     */
    public function setActive(bool $active): Periodicity
    {
        $this->active = $active;

        return $this;
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

}
