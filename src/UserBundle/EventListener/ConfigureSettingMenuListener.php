<?php

namespace UserBundle\EventListener;

use AppBundle\Event\ConfigureSettingMenuEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ConfigureSettingMenuListener
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
    public function onMenuConfigure(ConfigureSettingMenuEvent $event)
    {
        $menu = $event->getMenu();

        if ($this->securityCheck->isGranted('USER_MANAGER_MENU')){
            $item = $menu->addChild($this->translator->trans('menu.user'),
                [
                    'route' => 'user_manager',
                    'attributes' => array('class' => 'waves-effect waves-block')
                ]);
            $item->setExtra('icon', 'group');
            $item->setExtra('orderNumber', 3);
        }
    }
}
