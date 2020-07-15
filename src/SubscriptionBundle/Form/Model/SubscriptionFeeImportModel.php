<?php

namespace SubscriptionBundle\Form\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SubscriptionFeeImportModel
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
     * @return SubscriptionFeeImportModel
     */
    public function setFile(UploadedFile $file): SubscriptionFeeImportModel
    {
        $this->file = $file;

        return $this;
    }
}