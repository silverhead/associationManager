<?php


namespace EmailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Email
 * @package EmailBundle\Entity
 * @ORM\Entity(repositoryClass="EmailBundle\Repository\EmailSystemRepository")
 * @ORM\Table(name="email_system")
 */
class EmailSystem extends Email
{
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $code;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bundleLabel;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return EmailSystem
     */
    public function setCode(string $code): EmailSystem
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getBundleLabel():? string
    {
        return $this->bundleLabel;
    }

    /**
     * @param string $bundleLabel
     * @return Email
     */
    public function setBundleLabel(string $bundleLabel = null): Email
    {
        $this->bundleLabel = $bundleLabel;

        return $this;
    }
}