<?php

namespace AppBundle\EventListener;

use AppBundle\Event\ConfigureMenuEvent;
use Symfony\Component\Translation\TranslatorInterface;

class ConfigureMenuListener
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param  $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $item = $menu->addChild($this->translator->trans('menu.dashboard'),
            [
                'route' => 'homepage',
                'attributes' => array('class' => 'waves-effect waves-block')
            ]);
        $item->setExtra('icon', 'dashboard');
        $item->setExtra('orderNumber', 1);
    }
}
