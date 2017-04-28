<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 27/04/2017
 * Time: 22:45
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SettingRepository extends EntityRepository
{
    public function save($code, $value)
    {
        $setting = $this->findOneByCode($code);

        if(null === $setting){
            throw new \Exception("the code \“".$code."\" not found!");
        }

        $setting->setValue($value);

        $this->_em->flush();
    }
}