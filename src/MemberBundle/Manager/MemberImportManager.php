<?php

namespace MemberBundle\Manager;


use AppBundle\Handler\ErrorHandlerInterface;
use AppBundle\Handler\ErrorHandlerTrait;
use MemberBundle\Entity\MemberImport;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MemberImportManager implements ErrorHandlerInterface
{
    use ErrorHandlerTrait;

    /**
     * @var array
     */
    private $data = array();
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var ValidatorInterface
     */
    private $validator;


    public function __construct(TranslatorInterface $translator, ValidatorInterface $validator)
    {
        $this->translator = $translator;
        $this->validator = $validator;
    }

    public function import(string $pathFile): bool
    {
        $importOK = true;

        if (!$this->readFile($pathFile)){
            $importOK = false;
        }

        if (!$this->controlDataFile()){
            $importOK = false;
        }

        return $importOK;
    }

    private function readFile(string $pathFile): bool
    {
        $readingOk = true;

        try{
            $fp = fopen($pathFile, 'r');
            $numLine = 1;
            while (($data = fgetcsv($fp, 10000, ";")) !== FALSE) {
                $num = count($data);

                if ($num < MemberImport::MIN_COLUMN_LENGTH){
                    $this->addError($this->translator->trans('member.member.import.error.notEnoughColumns', array('%numLine%' => $numLine))) ;
                    $readingOk = false;
                    continue;
                }

                $this->data[] = $this->getDataLine($data, $numLine);

                $numLine++;
            }
            fclose($fp);
        }
        catch (\Exception $e){
            $this->addError($e->getMessage()) ;
        }

        return $readingOk;
    }

    public function getData(): array
    {
        return $this->data;
    }

    private function getDataLine($dataLine, $numLine)
    {
        $memberImport = new MemberImport();

        $memberImport->setNumLine($numLine);

        foreach ($dataLine as $i => $value) {
            switch ($i)
            {
                case 0:
                    $memberImport->setGender($value);
                    break;
                case 1:
                    $memberImport->setLastName($value);
                    break;
                case 2:
                    $memberImport->setFirstName($value);
                    break;
                case 3:
                    $memberImport->setEmail($value);
                    break;
                case 4:
                    $memberImport->setBirthday($value);
                    break;
                case 5:
                    $memberImport->setCountryCode($value);
                    break;
                case 6:
                    $memberImport->setCity($value);
                    break;
                case 7:
                    $memberImport->setZipCode($value);
                    break;
                case 8:
                    $memberImport->setAddress($value);
                    break;
                case 9:
                    $memberImport->setPhone($value);
                    break;
                case 10:
                    $memberImport->setCellular($value);
                    break;
                case 11:
                    $memberImport->setStudyLevel($value);
                    break;
                case 12:
                    $memberImport->setSpecialities($value);
                    break;
                case 13:
                    $memberImport->setComment($value);
                    break;
                case 14:
                    $memberImport->setGroups($value);
                    break;
            }
        }

        return $memberImport;

    }

    private function controlDataFile(): bool
    {
        $controlFileOk = true;

        foreach ($this->data as $importDataLine){

            $errors = $this->validator->validate($importDataLine);

            foreach($errors as $error){
                $this->addError($this->translator->trans(
                    'member.member.import.error', [
                    '%numLine%' => $importDataLine->getNumLine(),
                    '%numColumn%' => $importDataLine->getNumColumnOfProperty($error->getPropertyPath()),
                    '%message%' => $error->getMessage()
                ]));

                $controlFileOk = false;
            }
        }

        return $controlFileOk;
    }
}