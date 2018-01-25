<?php

namespace SubscriptionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SubscriptionPaymentType
 * @package SubscriptionBundle\Entity
 * @ORM\Entity(repositoryClass="SubscriptionBundle\Repository\PaymentTypeRepository")
 */
class SubscriptionPaymentType
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
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set label.
     *
     * @param string $label
     *
     * @return SubscriptionPaymentType
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}
