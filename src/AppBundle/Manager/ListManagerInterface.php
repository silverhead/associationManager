<?php
/**
 * Author: Nicolas PIN <pin.nicolas@free.fr>
 * Date: 05/05/2017
 *
 */

namespace AppBundle\Manager;


interface ListManagerInterface
{
    public function findAll(array $options = []);

    public function getRepository();
}