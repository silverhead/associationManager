<?php

namespace AccountingBundle\EventListener;

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

        if ($this->securityCheck->isGranted('ACCOUNTING_VIEW')){
            $item = $menu->addChild($this->translator->trans('menu.accounting'),
                [
                    'route' => 'accounting_index',
                    'attributes' => array('class' => 'waves-effect waves-block')
                ]);
            $item->setExtra('icon', 'attach_money');
            $item->setExtra('orderNumber', 4);
        }
    }
}
