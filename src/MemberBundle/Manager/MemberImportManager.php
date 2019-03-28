<?php

namespace MemberBundle\Manager;

use AppBundle\Manager\ImportManagerBase;
use AppBundle\Manager\SettingManager;
use Doctrine\ORM\EntityManager;
use MemberBundle\Entity\Member;
use MemberBundle\Entity\MemberImport;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MemberImportManager extends ImportManagerBase
{
    /**
     * @var SettingManager
     */
    private $settingManager;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(EntityManager $entityManager ,TranslatorInterface $translator, ValidatorInterface $validator, SettingManager $settingManager, UserPasswordEncoderInterface $encoder )
    {
        parent::__construct($translator, $validator);

        $this->settingManager = $settingManager;
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
    }

    protected function getMinColumnLength(): int
    {
        return MemberImport::MIN_COLUMN_LENGTH;
    }

    protected function getDataLine(array $dataLine, int $numLine): MemberImport
    {
        $memberImport = new MemberImport();

        $memberImport->setNumLine($numLine);

        foreach ($dataLine as $i => $value) {

            $value = trim($value);

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
                    $memberImport->setOrganization($value);
                    break;
                case 10:
                    $memberImport->setProfession($value);
                    break;
                case 11:
                    $memberImport->setPhone($value);
                    break;
                case 12:
                    $memberImport->setCellular($value);
                    break;
                case 13:
                    $memberImport->setStudyLevel($value);
                    break;
                case 14:
                    $memberImport->setSpecialities($value);
                    break;
                case 15:
                    $memberImport->setComment($value);
                    break;
                case 16:
                    $memberImport->setGroups($value);
                    break;
            }
        }

        return $memberImport;
    }

    protected function recordData()
    {
        try{
            $repo = $this->entityManager->getRepository("MemberBundle:Member");
            $repoMemberGroup = $this->entityManager->getRepository("MemberBundle:MemberGroup");

            foreach ($this->data as $importDataLine){
                $entity = $repo->findOneBy(array(
                    'email' => $importDataLine->getEmail()
                ));

                $importDataLine->setState(MemberImport::STATE_UPDATED);

                if (null === $entity){
                    $importDataLine->setState(MemberImport::STATE_CREATED);

                    $login = substr($importDataLine->getFirstName(), 0,1);// first letter of first name
                    $login .= preg_replace("/_- '/","",$importDataLine->getLastName());// first letter of last name
                    $login = strtolower(trim($login));

                    $entity = new Member();

                    $entity->setUsername($login);

                    //unique key for generate password
                    $entity->setSalt(uniqid());

                    $plainPassword = preg_replace("/_- '/","", trim($importDataLine->getCity()));
                    $plainPassword .= $importDataLine->getZipCode();
                    $entity->setPassword($this->encoder->encodePassword($entity, strtolower($plainPassword)));

                    $entity->setCreateAt(new \DateTime());
                }
                $entity->setUpdatedAt(new \DateTime());

                $entity->setGender($importDataLine->getGender());
                $entity->setFirstName($importDataLine->getFirstName());
                $entity->setLastName($importDataLine->getLastName());
                $entity->setEmail($importDataLine->getEmail());

                $birthday = \DateTime::createFromFormat('Y-m-d', $importDataLine->getBirthday());
                $entity->setBirthday($birthday);

                $entity->setCountry($importDataLine->getCountryCode());
                $entity->setCity($importDataLine->getCity());
                $entity->setZipcode($importDataLine->getZipCode());
                $entity->setAddress($importDataLine->getAddress());

                $entity->setOrganization($importDataLine->getOrganization());
                $entity->setProfession($importDataLine->getProfession());
                $entity->setPhone($importDataLine->getPhone());
                $entity->setCellular($importDataLine->getCellular());
                $entity->setStudy($importDataLine->getStudyLevel());
                $entity->setExpertise( explode(",", $importDataLine->getSpecialities()));

                $entity->setComment( $importDataLine->getComment());

                $memberGroupsLabels = explode(",", $importDataLine->getGroups());
                $memberGroups = $repoMemberGroup->findBy(array('label' => $memberGroupsLabels));
                if (count($memberGroups) > 0 ){
                    $entity->removeAllMemberGroup();
                    foreach ($memberGroups as $memberGroup){
                        $entity->addMemberGroup($memberGroup);
                    }
                }

                $this->entityManager->persist($entity);
                $this->entityManager->flush();
            }
        }
        catch(\Exception $ex)
        {
            $this->addError($ex->getMessage());
        }
    }
}