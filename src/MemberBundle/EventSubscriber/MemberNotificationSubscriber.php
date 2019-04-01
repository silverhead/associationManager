<?php

namespace MemberBundle\EventSubscriber;

use MemberBundle\Entity\MemberSubscriptionFee;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Product;
use Doctrine\ORM\Events;


class MemberNotificationSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->activeMember($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->activeMember($args);
    }

    function activeMember(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // perhaps you only want to act on some "Product" entity
        if ($entity instanceof MemberSubscriptionFee) {
            if (null === $entity->getMember()) {
                return;
            }

            if ($entity->getEndDate() >= new \DateTime() && $entity->getPaid()){
                $entity->getMember()->setActive(true);
                $entityManager = $args->getObjectManager();
                $entityManager->persist($entity->getMember());

                $entityManager->flush();
            }
        }
    }
}