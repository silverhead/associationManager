<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 06/05/2017
 * Time: 12:30
 */

namespace AppBundle\Manager;


interface EntityManagerInterface
{
    public function find($id = null);

    public function save($entity);

    public function delete($entity);

    public function getRepository();


}