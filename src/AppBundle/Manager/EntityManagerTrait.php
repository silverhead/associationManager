<?php

namespace AppBundle\Manager;

trait EntityManagerTrait
{

    public function find($id = null)
    {
        if(null === $id){
            return null;
        }

        return $this->getRepository()->find($id);
    }

    public function save($entity)
    {
        try{
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return true;
        }
        catch(\Exception $e){
            $this->addError($e->getMessage());

            return false;
        }
    }

    public function delete($entity)
    {
        try{
            $this->entityManager->remove($entity);
            $this->entityManager->flush();

            return true;
        }
        catch(\Exception $e){
            $this->addError($e->getMessage());

            return false;
        }
    }

    private function checkExistanceOfEntityManagerProperty()
    {
        if( !isset($this->entityManager) || !($this->entityManager instanceof \Doctrine\ORM\EntityManagerInterface)){
            throw new \Exception("You need to create a property named \"entityManager\" which implements \Doctrine\ORM\EntityManagerInterface!");
        }
    }
}