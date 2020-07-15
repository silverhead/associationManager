<?php
namespace MemberBundle\Validator\Entity;


interface IsUniqueEmailEntity
{
    public function getFirstName(): ?string;

    public function getLastName(): ?string;
}