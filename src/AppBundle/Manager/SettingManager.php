<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectRepository;

class SettingManager
{
    private $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getSetting($code)
    {
        return $this->repository->findOneByCode($code);
    }
}