<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 25/04/2017
 * Time: 22:38
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Setting
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SettingRepository")
 */
class Setting
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     *
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @var string
     * @ORM\Column(type="string", length=40)
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $value;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Setting
     */
    public function setId(int $id): Setting
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Setting
     */
    public function setCode(string $code): Setting
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Setting
     */
    public function setType(string $type): Setting
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Setting
     */
    public function setValue(string $value): Setting
    {
        $this->value = $value;

        return $this;
    }
}