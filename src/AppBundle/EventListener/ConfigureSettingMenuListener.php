<?php

namespace AppBundle\EventListener;

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

        if ($this->securityCheck->isGranted('APP_SETTING_VIEW')){
            $item = $menu->addChild($this->translator->trans('menu.setting'),
                [
                    'route' => 'setting_manager',
                    'attributes' => array('class' => 'waves-effect waves-block')
                ]);
            $item->setExtra('icon', 'settings');
            $item->setExtra('orderNumber', 1);

            $item2 = $menu->addChild($this->translator->trans('dashboard.menu.manager'),
                [
                    'route' => 'dashboard_manager',
                    'attributes' => array('class' => 'waves-effect waves-block')
                ]);
            $item2->setExtra('icon', 'extension');
            $item2->setExtra('orderNumber', 2);
        }
    }
}
