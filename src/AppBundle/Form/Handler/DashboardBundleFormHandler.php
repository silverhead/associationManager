<?php

namespace AppBundle\Form\Handler;

use AppBundle\Entity\DashboardBundleSetting;
use AppBundle\Event\DashboardBundleEvent;
use AppBundle\Form\Model\DashboardBundleCollectionModel;
use AppBundle\Form\Type\DashboardBundleCollectionFormType;
use Symfony\Component\BrowserKit\Request;
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

    public function setForm(DashboardBundleCollectionModel $dashboardBundleCollectionModel)
    {
        $dashboardBundleEvent = new DashboardBundleEvent();
        $this->eventDispatcher->dispatch(
            DashboardBundleEvent::EVENT_NAME,
            $dashboardBundleEvent
        );
        $bundles = $dashboardBundleEvent->getBundlesList();

        foreach($dashboardBundleCollectionModel->getBundles() as $dashboardBundle){
            $bundle = $bundles[$dashboardBundle->getBundleCode()];
            $dashboardBundle->setBundle($bundle);
        }

        $this->form = $this->formFactory->create(DashboardBundleCollectionFormType::class, $dashboardBundleCollectionModel, array(
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
}