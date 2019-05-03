<?php

namespace EmailBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Class EmailKeyword
 * @package EmailBundle\Entity
 * @ORM\Entity(repositoryClass="EmailBundle\Repository\EmailKeywordRepository")
 */
class EmailKeyword
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
    private $keyword;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $entityProperty;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $entityName;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * Set keyword.
     *
     * @param string $keyword
     *
     * @return EmailKeyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Get keyword.
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set entityProperty.
     *
     * @param string $entityProperty
     *
     * @return EmailKeyword
     */
    public function setEntityProperty($entityProperty)
    {
        $this->entityProperty = $entityProperty;

        return $this;
    }

    /**
     * Get entityProperty.
     *
     * @return string
     */
    public function getEntityProperty()
    {
        return $this->entityProperty;
    }

    /**
     * Set entityName.
     *
     * @param string $entityName
     *
     * @return EmailKeyword
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;

        return $this;
    }

    /**
     * Get entityName.
     *
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return EmailKeyword
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
