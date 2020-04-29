<?php

namespace SubscriptionBundle\EventListener;

use AppBundle\Event\ConfigureMenuEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ConfigureMenuListener
{
    private $translator;
    private $securityCheck;

    public function __construct(TranslatorInterface $translator, AuthorizationCheckerInterface $securityCheck)
    {
        $this->translator = $translator;
        $this->securityCheck = $securityCheck;
    }

    /**
     * @param  $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        if ($this->securityCheck->isGranted('SUBSCRIPTION_MANAGER_MENU')){
            $item = $menu->addChild($this->translator->trans('menu.subscription'),
                [
                    'route' => 'subscription_manager',
                    'attributes' => array('class' => 'waves-effect waves-block')
                ]);
            $item->setExtra('icon', 'cached');
            $item->setExtra('orderNumber', 3);
        }
    }
}
