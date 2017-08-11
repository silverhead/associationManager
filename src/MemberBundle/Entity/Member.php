<?php

namespace MemberBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

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
     * @ORM\OneToMany(targetEntity="MemberBundle\Entity\MemberSubscriptionHistorical", mappedBy="member")
     */
    protected $subscriptions;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="MemberBundle\Entity\MemberStatusHistorical", mappedBy="member")
     */
    protected $status;
}