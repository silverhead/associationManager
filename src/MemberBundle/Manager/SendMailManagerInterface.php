<?php

namespace MemberBundle\Manager;

Interface SendMailManagerInterface
{
    /**
     * @param array $placeholderWords
     * @param string $subject
     * @param string $body
     * @param string $templatePath
     * @param string $dateFormat
     * @return mixed
     */
    public function prepareData(array $placeholderWords, string $subject, string $body, string $templatePath, string $dateFormat);

    /**
     * @param array $dataList
     * @return mixed
     */
    public function send(array $dataList);
}