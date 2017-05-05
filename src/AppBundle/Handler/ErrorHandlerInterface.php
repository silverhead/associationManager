<?php

namespace AppBundle\Handler;


interface ErrorHandlerInterface
{
    /**
     * Add a error message
     * @param string $errorMessage
     * @return mixed
     */
    public function addError($errorMessage);

    /**
     * Get All errror messages
     * @return mixed
     */
    public function getErrors();
}