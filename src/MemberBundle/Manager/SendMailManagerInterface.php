<?php

namespace MemberBundle\Manager;

Interface SendMailManagerInterface
{
    /**
     * @param array $placeholderWords
     * @param string $subjectCode
     * @param string $bodyCode
     * @param string $templatePath
     * @return mixed
     */
    public function prepareCustomData(array $placeholderWords, string $subjectCode, string $bodyCode, string $templatePath);

    /**
     * @param array $dataList
     * @return mixed
     */
    public function send(array $dataList);
}