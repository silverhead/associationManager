<?php

namespace MemberBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use MemberBundle\Entity\MemberSubscriptionFee;

class MemberSubscriptionNotificationSubscriber implements EventSubscriber
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
        $this->updateDate($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->updateDate($args);
    }

    /**
     * Update the date of subscription historical
     * in function of the date start and end of fee subscription
     *
     * @param LifecycleEventArgs $args
     */
    public function updateDate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof MemberSubscriptionFee) {
            if (null === $entity->getSubscription()) {
                return;
            }

            $memberSubscription = $entity->getSubscription();

            $dateChange = false;

            if (null === $memberSubscription->getStartDate() || $memberSubscription->getStartDate() > $entity->getStartDate()){
                $memberSubscription->setStartDate($entity->getStartDate());
                $dateChange = true;
            }

            if (null === $memberSubscription->getEndDate() || $memberSubscription->getEndDate() < $entity->getEndDate()){
                $memberSubscription->setEndDate($entity->getEndDate());
                $dateChange = true;
            }

            if ($dateChange){
                $entityManager = $args->getObjectManager();
                $entityManager->persist($memberSubscription);
                $entityManager->flush();
            }
        }
    }
}