<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Setting;
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
        /**
         * @var Setting
         */
        $setting = $this->getSetting($code);

        if(null === $setting){
            throw new \Exception($code." not found in database!");
        }

        return $setting->getValue();
    }

    /**
     * @param $code
     * @return mixed
     * @throws \Exception
     */
    public function getFormatedSettingValue($code)
    {
        /**
         * @var Setting
         */
        $setting = $this->getSetting($code);

        if(null === $setting){
            throw new \Exception($code." not found in database!");
        }

        switch ($setting->getType()){
            case 'string':
            case 'text':
                return $setting->getValue();
                break;
            case 'integer':
                return (int) $setting->getValue();
                break;
            case 'float':
                return (float) $setting->getValue();
                break;
            case 'array':
                return explode(",",$setting->getValue());
                break;
            default:
                return $setting->getValue();
                break;
        }
    }


    public function save($code, $value)
    {
        $this->repository->save($code, $value);
    }
}