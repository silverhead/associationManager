<?php

namespace AppBundle\Form\Handler;

use AppBundle\Entity\DashboardBundleSetting;
use AppBundle\Event\DashboardBundleEvent;
use AppBundle\Form\Model\DashboardBundleCollectionModel;
use AppBundle\Form\Type\DashboardBundleCollectionFormType;
use AppBundle\Form\Type\DashboardBundleFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;

class DashboardBundleFormHandler
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    private $eventDispatcher;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    private $form;

    public function __construct(FormFactory $formFactory, EventDispatcherInterface $eventDispatcher){
        $this->formFactory = $formFactory;

        $this->eventDispatcher = $eventDispatcher;
    }

    public function setForm(DashboardBundleSetting $dashboardBundleSetting)
    {
        $dashboardBundleEvent = new DashboardBundleEvent();
        $this->eventDispatcher->dispatch(
            DashboardBundleEvent::EVENT_NAME,
            $dashboardBundleEvent
        );
        $bundles = $dashboardBundleEvent->getBundlesList();

        if($dashboardBundleSetting->getId() > 0){
            $bundle = $bundles[$dashboardBundleSetting->getBundleCode()];
            $dashboardBundleSetting->setBundle($bundle);
        }

        $this->form = $this->formFactory->create(DashboardBundleFormType::class, $dashboardBundleSetting, array(
            'bundles' => $bundles
        ));
    }

    public function getForm()
    {
        if(null === $this->form){
            throw new Exception("the form is not init, please use ::setForm before!");
        }

        return $this->form;
    }

    public function process(Request $request)
    {
        $this->form->handleRequest($request);

        if($this->form->isSubmitted() && $this->form->isValid()){
            return true;
        }

        return false;
    }

    public function getData()
    {
        $data =  $this->form->getData();

        $data->setBundleCode(
            $data->getBundle()->getBundleCode()
        );

        return $data;
    }
}