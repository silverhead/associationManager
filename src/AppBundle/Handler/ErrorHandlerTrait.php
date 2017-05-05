<?php
namespace AppBundle\Handler;


trait ErrorHandlerTrait
{
    /**
     * @var array
     */
    protected $errors = [];

    public function addError($errorMessage)
    {
        $this->errors[] = $errorMessage;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}