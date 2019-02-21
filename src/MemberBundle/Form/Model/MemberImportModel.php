<?php

namespace MemberBundle\Form\Model;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class MemberImportModel
{
    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @return UploadedFile
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     * @return MemberImportModel
     */
    public function setFile(UploadedFile $file): MemberImportModel
    {
        $this->file = $file;

        return $this;
    }
}