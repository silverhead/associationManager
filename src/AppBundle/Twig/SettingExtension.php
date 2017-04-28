<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 28/04/2017
 * Time: 11:35
 */

namespace AppBundle\Twig;


use Doctrine\Common\Persistence\ObjectRepository;

class SettingExtension extends \Twig_Extension
{
    /**
     * @var ObjectRepository
     */
    private $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('toValue', array($this, 'toValueFilters'))
        ];
    }

    public function toValueFilters($code)
    {
        $setting = $this->repository->findOneByCode($code);
        if(null === $setting){
            return null;
        }
        return $setting->getValue();
    }
}