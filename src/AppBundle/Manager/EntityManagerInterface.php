<?php

namespace AppBundle\Manager;

use AppBundle\Handler\ErrorHandlerInterface;

interface EntityManagerInterface extends ErrorHandlerInterface
{
    public function getNewEntity();

    public function find($id = null);

    public function save($entity);

    public function delete($entity);

    public function getRepository();

}