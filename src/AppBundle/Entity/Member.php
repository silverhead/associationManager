<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Member
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 */
class Member extends User
{
    protected $discr = 'member';

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MemberSubscriptionHistorical", mappedBy="member")
     */
    protected $subscriptions;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MemberStatusHistorical", mappedBy="member")
     */
    protected $status;
}