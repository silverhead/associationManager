<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectRepository;

class SettingManager
{
    /**
     * @var ObjectRepository
     */
    private $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getSetting($code)
    {
        return $this->repository->findOneByCode($code);
    }

    public function getSettingValue($code)
    {
        $setting = $this->getSetting($code);

        if(null === $setting){
            throw new \Exception($code." not found in database!");
        }

        return $setting->getValue();
    }



}