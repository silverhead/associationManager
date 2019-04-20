<?php

namespace AppBundle\Manager;

use AppBundle\Handler\ErrorHandlerInterface;
use AppBundle\Handler\ErrorHandlerTrait;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class ImportManagerBase implements ErrorHandlerInterface
{
    use ErrorHandlerTrait;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var array
     */
    protected $data;

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
        if ($importOK){
            $this->recordData();
        }
        return $importOK;
    }

    public function getData()
    {
        return $this->data;
    }

    private function readFile(string $pathFile): bool
    {
        $readingOk = true;
        try{
            $fp = fopen($pathFile, 'r');
            $numLine = 1;
            while (($data = fgetcsv($fp, 10000, ";")) !== FALSE) {

                if (null === $data || !$data){
                    continue;
                }

                $num = count($data);
                if ($num < $this->getMinColumnLength()){
                    $this->addError($this->translator->trans('app.common.import.error.notEnoughColumns', array('%nbColumn%' => $this->getMinColumnLength()))) ;
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

    private function controlDataFile(): bool
    {
        $controlFileOk = true;

        $errorMessages = array();

        foreach ($this->data as $importDataLine){
            $errors = $this->validator->validate($importDataLine);
            foreach($errors as $error){
                $numLine = $importDataLine->getNumLine();
                $numColumn = $importDataLine->getNumColumnByProperty($error->getPropertyPath());

                $errorMessages[$numLine][$numColumn] = $this->translator->trans(
                    'app.common.import.lineError', [
                    '%numLine%' => $importDataLine->getNumLine(),
                    '%numColumn%' => $importDataLine->getNumColumnByProperty($error->getPropertyPath()),
                    '%message%' => $error->getMessage()
                ]);

                $controlFileOk = false;
            }
        }
        // display error by line and column ascending
        ksort($errorMessages);
        foreach($errorMessages as $errorByLine){
            ksort($errorByLine);

            foreach($errorByLine as $errorByColumn){
                $this->addError($errorByColumn);
            }
        }

        return $controlFileOk;
    }

    abstract protected function getMinColumnLength(): int;

    abstract protected function getDataLine(array $dataLine, int $numLine);

    abstract protected function recordData();
}